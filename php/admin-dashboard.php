<?php
session_start();
error_reporting(0);
include('config.php');
if(strlen($_SESSION['alogin'])==0)
  { 
header('location:../index.php');
}
else{
$page_title = 'NEW HARGEISA LIBRARY | Admin Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php include('admin-head.php'); ?>
</head>
<body>
<?php include('admin-header.php');?>
    <main class="content-wrapper">
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
$today = date('Y-m-d');
$sql_o ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0 AND ExpectedReturnDate < :today";
$query_o = $dbh -> prepare($sql_o);
$query_o->bindParam(':today', $today, PDO::PARAM_STR);
$query_o->execute();
$overdue=$query_o->rowCount();
?>
                    <h3><?php echo htmlentities($overdue);?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>

            <div class="row">
              <div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-1">
                    <div id="carousel-example" class="carousel slide slide-bdr">
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="../images/1.jpg" alt="Library" />
                        </div>
                        <div class="item">
                            <img src="../images/2.jpg" alt="Library" />
                        </div>
                        <div class="item">
                            <img src="../images/3.jpg" alt="Library" />
                        </div>
                    </div>
                     <ol class="carousel-indicators">
                        <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example" data-slide-to="1"></li>
                        <li data-target="#carousel-example" data-slide-to="2"></li>
                    </ol>
                     <a class="left carousel-control" href="#carousel-example" data-slide="prev" aria-label="Previous">
                        <i class="fa fa-chevron-left"></i>
                     </a>
                     <a class="right carousel-control" href="#carousel-example" data-slide="next" aria-label="Next">
                        <i class="fa fa-chevron-right"></i>
                     </a>
                </div>
              </div>
             </div>
        </div>
    </main>
    <script src="../js/jquery-1.10.2.js"></script>
    <script src="../js/admin-custom.js"></script>
</body>
</html>
<?php } ?>
