<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:../index.php');
}
else{
$page_title = 'NEW HARGEISA LIBRARY | Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('includes/head.php'); ?>
</head>
<body>
<?php include('includes/header.php');?>
    <main class="content-wrapper">
        <div class="container">
            <h1 class="page-header">User Dashboard</h1>

            <div class="welcome-banner">
                <h2>Welcome back, <?php echo htmlentities($_SESSION['login']);?>!</h2>
                <p>Explore the library, check your issued books, or update your profile from your dashboard.</p>
            </div>

            <div class="stat-grid">
                <div class="stat-card stat-card--primary">
                    <i class="fa fa-book"></i>
<?php 
$sid=$_SESSION['stdid'];
$sql1 ="SELECT id from tblissuedbookdetails where StudentID=:sid AND ReturnStatus IN (0,2)";
$query1 = $dbh -> prepare($sql1);
$query1->bindParam(':sid',$sid,PDO::PARAM_STR);
$query1->execute();
$currentlyBorrowed=$query1->rowCount();
?>
                    <h3><?php echo htmlentities($currentlyBorrowed);?></h3>
                    <p>Currently Borrowed</p>
                </div>
                <div class="stat-card stat-card--warning">
                    <i class="fa fa-clock-o"></i>
<?php 
$sql2 ="SELECT id from tblissuedbookdetails where StudentID=:sid and ReturnStatus=2";
$query2 = $dbh -> prepare($sql2);
$query2->bindParam(':sid',$sid,PDO::PARAM_STR);
$query2->execute();
$pendingReturn=$query2->rowCount();
?>
                    <h3><?php echo htmlentities($pendingReturn);?></h3>
                    <p>Pending Return</p>
                </div>
                <div class="stat-card stat-card--success">
                    <i class="fa fa-check-circle"></i>
<?php 
$sql3 ="SELECT id from tblissuedbookdetails where StudentID=:sid and ReturnStatus=1";
$query3 = $dbh -> prepare($sql3);
$query3->bindParam(':sid',$sid,PDO::PARAM_STR);
$query3->execute();
$returnedBooks=$query3->rowCount();
?>
                    <h3><?php echo htmlentities($returnedBooks);?></h3>
                    <p>Books Returned</p>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Quick Actions</div>
                <div class="panel-body quick-actions">
                    <a href="browse-books.php" class="btn btn-primary"><i class="fa fa-search"></i> Browse Books</a>
                    <a href="issued-books.php" class="btn btn-warning"><i class="fa fa-list"></i> My Borrowed Books</a>
                    <a href="borrow-history.php" class="btn btn-success"><i class="fa fa-history"></i> Borrow History</a>
                    <a href="my-profile.php" class="btn btn-info"><i class="fa fa-user"></i> Update Profile</a>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/jquery-1.10.2.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
<?php } ?>
