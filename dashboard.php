<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>NEW HARGEISA LIBRARY | User Dash Board</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">USER DASHBOARD</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 12px; padding: 30px; margin-bottom: 30px;">
                    <h2 style="margin: 0; font-weight: 700;">Welcome back, <?php echo htmlentities($_SESSION['login']);?>!</h2>
                    <p style="margin-top: 10px; opacity: 0.9; font-size: 16px;">Explore the library, check your issued books, or request new ones from your dashboard.</p>
                </div>
            </div>
        </div>
             
             <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert back-widget-set text-center" style="background-color: #3b82f6; color: #fff; border: none;">
                            <i class="fa fa-book fa-3x"></i>
<?php 
$sid=$_SESSION['stdid'];
$sql1 ="SELECT id from tblissuedbookdetails where StudentID=:sid AND ReturnStatus IN (0,2)";
$query1 = $dbh -> prepare($sql1);
$query1->bindParam(':sid',$sid,PDO::PARAM_STR);
$query1->execute();
$currentlyBorrowed=$query1->rowCount();
?>
                            <h3><?php echo htmlentities($currentlyBorrowed);?> </h3>
                            <p>Currently Borrowed</p>
                        </div>
                    </div>
             
               <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert back-widget-set text-center" style="background-color: #f59e0b; color: #fff; border: none;">
                            <i class="fa fa-clock-o fa-3x"></i>
<?php 
$sql2 ="SELECT id from tblissuedbookdetails where StudentID=:sid and ReturnStatus=2";
$query2 = $dbh -> prepare($sql2);
$query2->bindParam(':sid',$sid,PDO::PARAM_STR);
$query2->execute();
$pendingReturn=$query2->rowCount();
?>
                            <h3><?php echo htmlentities($pendingReturn);?></h3>
                           <p>Pending Return Approval</p>
                        </div>
                    </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert back-widget-set text-center" style="background-color: #10b981; color: #fff; border: none;">
                            <i class="fa fa-check-circle fa-3x"></i>
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
        </div>

        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Quick Actions
                    </div>
                    <div class="panel-body" style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <a href="browse-books.php" class="btn btn-primary"><i class="fa fa-search"></i> Browse Books</a>
                        <a href="issued-books.php" class="btn btn-warning"><i class="fa fa-list"></i> My Borrowed Books</a>
                        <a href="borrow-history.php" class="btn btn-success"><i class="fa fa-history"></i> Borrow History</a>
                        <a href="my-profile.php" class="btn btn-info"><i class="fa fa-user"></i> Update Profile</a>
                    </div>
                </div>
            </div>
        </div>
            
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
