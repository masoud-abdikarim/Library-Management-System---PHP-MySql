<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){
    header('location:index.php');
    exit();
}

// Date filter handling
$startDate = $_POST['start_date'] ?? '';
$endDate = $_POST['end_date'] ?? '';
$dateCondition = '';
if($startDate && $endDate){
    $dateCondition = "AND i.IssueDate BETWEEN :start AND :end";
}

// Statistics queries
$totBooksStmt = $dbh->prepare('SELECT COUNT(*) FROM tblbooks');
$totBooksStmt->execute();
$totalBooks = $totBooksStmt->fetchColumn();

$borrowedStmt = $dbh->prepare('SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=0');
$borrowedStmt->execute();
$totalBorrowed = $borrowedStmt->fetchColumn();

$returnedStmt = $dbh->prepare('SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=1');
$returnedStmt->execute();
$totalReturned = $returnedStmt->fetchColumn();

$overdueStmt = $dbh->prepare('SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=0 AND ExpectedReturnDate < CURDATE()');
$overdueStmt->execute();
$totalOverdue = $overdueStmt->fetchColumn();

$pendingStmt = $dbh->prepare('SELECT COUNT(*) FROM tblissuedbookdetails WHERE ReturnStatus=2');
$pendingStmt->execute();
$totalPending = $pendingStmt->fetchColumn();

$usersStmt = $dbh->prepare('SELECT COUNT(*) FROM tblstudents');
$usersStmt->execute();
$totalUsers = $usersStmt->fetchColumn();

$totalAvailable = $totalBooks - $totalBorrowed;

// Most borrowed books (top 5)
$mostBorrowedSQL = "SELECT b.BookName, COUNT(*) as borrow_count, c.CategoryName, p.PublicationName
    FROM tblissuedbookdetails i
    JOIN tblbooks b ON i.BookId = b.id
    LEFT JOIN tblcategory c ON b.CatId = c.id
    LEFT JOIN tblpublisher p ON b.PublisherId = p.id
    $dateCondition
    GROUP BY b.id
    ORDER BY borrow_count DESC
    LIMIT 5";
$mostBorrowedStmt = $dbh->prepare($mostBorrowedSQL);
if($dateCondition){
    $mostBorrowedStmt->bindParam(':start', $startDate);
    $mostBorrowedStmt->bindParam(':end', $endDate);
}
$mostBorrowedStmt->execute();
$mostBorrowed = $mostBorrowedStmt->fetchAll(PDO::FETCH_ASSOC);

// Borrow activity over last 30 days
$activitySQL = "SELECT DATE(IssueDate) as day, COUNT(*) as cnt FROM tblissuedbookdetails WHERE IssueDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY day ORDER BY day";
$activityStmt = $dbh->prepare($activitySQL);
$activityStmt->execute();
$activityData = $activityStmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Category popularity
$categorySQL = "SELECT c.CategoryName, COUNT(*) as cnt FROM tblissuedbookdetails i JOIN tblbooks b ON i.BookId = b.id JOIN tblcategory c ON b.CatId = c.id GROUP BY c.id ORDER BY cnt DESC";
$categoryStmt = $dbh->prepare($categorySQL);
$categoryStmt->execute();
$categoryData = $categoryStmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Library Reports Dashboard</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card{background:#fff;border-radius:12px;padding:20px;box-shadow:0 2px 6px rgba(0,0,0,0.1);margin-bottom:20px;text-align:center;}
        .stat-card h3{margin:0;font-size:24px;color:#111827;}
        .stat-card p{margin:5px 0 0;color:#6b7280;}
        @media(max-width:768px){.stat-card{margin-bottom:15px;}}
    </style>
</head>
<body>
<?php include('includes/header.php'); ?>
<div class="content-wrapper">
    <div class="container-fluid">
        <h4 class="header-line">Library Analytics Dashboard</h4>
        <form method="post" class="form-inline mb-4">
            <div class="form-group mr-2">
                <label for="start_date" class="mr-2">From:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo htmlspecialchars($startDate); ?>" />
            </div>
            <div class="form-group mr-2">
                <label for="end_date" class="mr-2">To:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo htmlspecialchars($endDate); ?>" />
            </div>
            <button type="submit" class="btn btn-primary">Apply</button>
        </form>
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card" style="background:#3b82f6;color:#fff;">
                    <i class="fa fa-book fa-3x"></i>
                    <h3><?php echo $totalBooks; ?></h3>
                    <p>Total Books</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card" style="background:#10b981;color:#fff;">
                    <i class="fa fa-exchange fa-3x"></i>
                    <h3><?php echo $totalBorrowed; ?></h3>
                    <p>Borrowed Books</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card" style="background:#ef4444;color:#fff;">
                    <i class="fa fa-exclamation-triangle fa-3x"></i>
                    <h3><?php echo $totalOverdue; ?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stat-card" style="background:#f59e0b;color:#fff;">
                    <i class="fa fa-users fa-3x"></i>
                    <h3><?php echo $totalUsers; ?></h3>
                    <p>Active Users</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Borrow Activity (Last 30 Days)</div>
                    <div class="panel-body">
                        <canvas id="borrowChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="panel panel-default">
                    <div class="panel-heading">Popular Categories</div>
                    <div class="panel-body">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Top 5 Borrowed Books</div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr><th>#</th><th>Book Name</th><th>Borrow Count</th><th>Category</th><th>Publication</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach($mostBorrowed as $i=>$row): ?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo htmlspecialchars($row['BookName']); ?></td>
                                        <td><?php echo $row['borrow_count']; ?></td>
                                        <td><?php echo htmlspecialchars($row['CategoryName'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($row['PublicationName'] ?? 'N/A'); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-right">
                <button class="btn btn-success mr-2" onclick="exportCSV()">Export CSV</button>
                <button class="btn btn-danger" onclick="window.print()">Print Report</button>
            </div>
        </div>
    </div>
</div>
<script>
// Borrow Chart
const borrowCtx = document.getElementById('borrowChart').getContext('2d');
new Chart(borrowCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($activityData)); ?>,
        datasets: [{
            label: 'Borrows',
            data: <?php echo json_encode(array_values($activityData)); ?>,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: { responsive: true }
});
// Category Chart
const catCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(catCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode(array_keys($categoryData)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($categoryData)); ?>,
            backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#2563eb']
        }]
    },
    options: { responsive: true }
});
function exportCSV(){
    let csv = 'Rank,Book Name,Borrow Count,Category,Publication\n';
    <?php foreach($mostBorrowed as $i=>$row): ?>
    csv += '<?php echo $i+1; ?>,"<?php echo addslashes($row["BookName"]); ?>",<?php echo $row["borrow_count"]; ?>,"<?php echo addslashes($row["CategoryName"] ?? ""); ?>","<?php echo addslashes($row["PublicationName"] ?? ""); ?>' + "\n";
    <?php endforeach; ?>
    const blob = new Blob([csv], {type:'text/csv;charset=utf-8;'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'top_borrowed_books.csv';
    link.click();
}
</script>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/custom.js"></script>
</body>
</html>
