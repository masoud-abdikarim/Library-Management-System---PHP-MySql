<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:index.php');
}
else{ 
    if(isset($_GET['reqid'])) {
        $rid = intval($_GET['reqid']);
        $sql = "UPDATE tblissuedbookdetails SET ReturnStatus=2, ReturnRequestDate=CURRENT_TIMESTAMP WHERE id=:rid AND StudentId=:sid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->bindParam(':sid', $_SESSION['stdid'], PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg'] = "Return request sent successfully. Please return the book to the library.";
        header('location:issued-books.php');
    }
    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>NEW HARGEISA LIBRARY | My Borrowed Books</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">MY BORROWED BOOKS</h4>
            </div>
        </div>

        <?php if($_SESSION['msg']!="") { ?>
            <div class="row"><div class="col-md-12"><div class="alert alert-success"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?></div></div></div>
        <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Active Loans</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book ID</th>
                                            <th>Book Name</th>
                                            <th>Issued Date</th>
                                            <th>Expected Return</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$sid=$_SESSION['stdid'];
$sql="SELECT tblbooks.BookName,tblbooks.id as bookid,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ExpectedReturnDate,tblissuedbookdetails.ReturnStatus,tblissuedbookdetails.id as rid 
      FROM tblissuedbookdetails 
      JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
      WHERE tblissuedbookdetails.StudentId=:sid AND tblissuedbookdetails.ReturnStatus IN (0, 2) 
      ORDER BY tblissuedbookdetails.id DESC";
$query = $dbh -> prepare($sql);
$query-> bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center">BK-<?php echo htmlentities($result->bookid);?></td>
                                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                            <td class="center"><?php echo htmlentities($result->IssuesDate);?></td>
                                            <td class="center"><?php echo htmlentities($result->ExpectedReturnDate);?></td>
                                            <td class="center">
                                                <?php if($result->ReturnStatus==0) { 
                                                    $today = date('Y-m-d');
                                                    if($result->ExpectedReturnDate < $today) {
                                                        echo '<span class="label label-danger">Overdue</span>';
                                                    } else {
                                                        echo '<span class="label label-primary">Borrowed</span>';
                                                    }
                                                } else if($result->ReturnStatus==2) {
                                                    echo '<span class="label label-warning">Pending Return Approval</span>';
                                                } ?>
                                            </td>
                                            <td class="center">
                                                <?php if($result->ReturnStatus==0) { ?>
                                                    <a href="javascript:void(0);" data-href="issued-books.php?reqid=<?php echo htmlentities($result->rid);?>" data-action="confirm" data-title="Request Return" data-msg="Do you want to request return for this book?" class="btn btn-warning btn-sm">Request Return</a>
                                                <?php } else { ?>
                                                    <button class="btn btn-default btn-sm" disabled>Requested</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
 <?php $cnt=$cnt+1;}} ?>                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
