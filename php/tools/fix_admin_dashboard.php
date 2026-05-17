<?php
// PHP CLI utility to format and fix the admin dashboard markup and metrics
$root = dirname(__DIR__, 2);
$filePath = $root . '/php/admin/dashboard.php';

if (!file_exists($filePath)) {
    die("Error: admin dashboard.php not found at {$filePath}\n");
}

$t = file_get_contents($filePath);

$new_main = '    <main class="content-wrapper">
        <div class="container">
            <h1 class="page-header">Admin Dashboard</h1>

            <div class="stat-grid">
                <div class="stat-card stat-card--success">
                    <i class="fa fa-book"></i>
<?php 
$sql ="SELECT id from tblbooks ";
$query = $dbh -> prepare($sql);
$query->execute();
$listdbooks=$query->rowCount();
?>
                    <h3><?php echo htmlentities($listdbooks);?></h3>
                    <p>Total Books</p>
                </div>
                <div class="stat-card stat-card--primary">
                    <i class="fa fa-file-archive-o"></i>
<?php 
$sql5 ="SELECT id from tblcategory ";
$query5 = $dbh -> prepare($sql5);
$query5->execute();
$listdcats=$query5->rowCount();
?>
                    <h3><?php echo htmlentities($listdcats);?></h3>
                    <p>Categories</p>
                </div>
                <div class="stat-card stat-card--danger">
                    <i class="fa fa-users"></i>
<?php 
$sql3 ="SELECT id from tblstudents ";
$query3 = $dbh -> prepare($sql3);
$query3->execute();
$regstds=$query3->rowCount();
?>
                    <h3><?php echo htmlentities($regstds);?></h3>
                    <p>Registered Users</p>
                </div>
                <div class="stat-card stat-card--primary">
                    <i class="fa fa-exchange"></i>
<?php 
$sql_b ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0";
$query_b = $dbh -> prepare($sql_b);
$query_b->execute();
$borrowed=$query_b->rowCount();
?>
                    <h3><?php echo htmlentities($borrowed);?></h3>
                    <p>Currently Borrowed</p>
                </div>
                <div class="stat-card stat-card--warning">
                    <i class="fa fa-clock-o"></i>
<?php 
$sql_p ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=2";
$query_p = $dbh -> prepare($sql_p);
$query_p->execute();
$pending=$query_p->rowCount();
?>
                    <h3><?php echo htmlentities($pending);?></h3>
                    <p>Pending Returns</p>
                </div>
                <div class="stat-card stat-card--success">
                    <i class="fa fa-check-circle"></i>
<?php 
$sql_r ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=1";
$query_r = $dbh -> prepare($sql_r);
$query_r->execute();
$returned=$query_r->rowCount();
?>
                    <h3><?php echo htmlentities($returned);?></h3>
                    <p>Returned Books</p>
                </div>
                <div class="stat-card stat-card--danger">
                    <i class="fa fa-exclamation-triangle"></i>
<?php 
$today = date(\'Y-m-d\');
$sql_o ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0 AND ExpectedReturnDate < :today";
$query_o = $dbh -> prepare($sql_o);
$query_o->bindParam(\':today\', $today, PDO::PARAM_STR);
$query_o->execute();
$overdue=$query_o->rowCount();
?>
                    <h3><?php echo htmlentities($overdue);?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>

';

// Replace from content-wrapper to carousel row
$pattern2 = '/<div class="content-wrapper">.*?(?=\s*<div class="row">\s*\n\s*<div class="col-md-10)/s';
if (preg_match($pattern2, $t, $matches, PREG_OFFSET_CAPTURE)) {
    $t = substr_replace($t, $new_main, $matches[0][1], strlen($matches[0][0]));
    $t = str_replace("    </div>\n    </div>", "        </div>\n    </main>", $t);
    file_put_contents($filePath, $t);
    echo "Admin dashboard layout and stats successfully updated!\n";
} else {
    echo "Pattern for dashboard layout replacement not found. (It may have already been formatted)\n";
}
?>
