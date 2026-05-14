<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
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
    <title>Online Library Management System | Admin Dash Board</title>
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
                <h4 class="header-line">ADMIN DASHBOARD</h4>
            </div>
        </div>
             
             <div class="row">

                <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-3x"></i>
<?php 
$sql ="SELECT id from tblbooks ";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$listdbooks=$query->rowCount();
?>
                            <h3><?php echo htmlentities($listdbooks);?></h3>
                      Total Books
                        </div>
                    </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert alert-info back-widget-set text-center">
                            <i class="fa fa-file-archive-o fa-3x"></i>
<?php 
$sql5 ="SELECT id from tblcategory ";
$query5 = $dbh -> prepare($sql5);
$query5->execute();
$results5=$query5->fetchAll(PDO::FETCH_OBJ);
$listdcats=$query5->rowCount();
?>
                            <h3><?php echo htmlentities($listdcats);?> </h3>
                           Total Categories
                        </div>
                    </div>

               <div class="col-md-4 col-sm-6 col-xs-12">
                      <div class="alert alert-danger back-widget-set text-center">
                            <i class="fa fa-users fa-3x"></i>
                            <?php 
$sql3 ="SELECT id from tblstudents ";
$query3 = $dbh -> prepare($sql3);
$query3->execute();
$results3=$query3->fetchAll(PDO::FETCH_OBJ);
$regstds=$query3->rowCount();
?>
                            <h3><?php echo htmlentities($regstds);?></h3>
                            List of Users
                        </div>
                    </div>

        </div> 

        <!-- Borrowing Statistics -->
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="alert alert-primary back-widget-set text-center" style="background-color: #3b82f6; color: #fff; border: none;">
                    <i class="fa fa-exchange fa-3x"></i>
                    <?php 
                    $sql_b ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0";
                    $query_b = $dbh -> prepare($sql_b);
                    $query_b->execute();
                    $borrowed=$query_b->rowCount();
                    ?>
                    <h3><?php echo htmlentities($borrowed);?></h3>
                    Currently Borrowed
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="alert back-widget-set text-center" style="background-color: #f59e0b; color: #fff; border: none;">
                    <i class="fa fa-clock-o fa-3x"></i>
                    <?php 
                    $sql_p ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=2";
                    $query_p = $dbh -> prepare($sql_p);
                    $query_p->execute();
                    $pending=$query_p->rowCount();
                    ?>
                    <h3><?php echo htmlentities($pending);?></h3>
                    Pending Returns
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="alert back-widget-set text-center" style="background-color: #10b981; color: #fff; border: none;">
                    <i class="fa fa-check-circle fa-3x"></i>
                    <?php 
                    $sql_r ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=1";
                    $query_r = $dbh -> prepare($sql_r);
                    $query_r->execute();
                    $returned=$query_r->rowCount();
                    ?>
                    <h3><?php echo htmlentities($returned);?></h3>
                    Returned Books
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="alert back-widget-set text-center" style="background-color: #ef4444; color: #fff; border: none;">
                    <i class="fa fa-exclamation-triangle fa-3x"></i>
                    <?php 
                    $today = date('Y-m-d');
                    $sql_o ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0 AND ExpectedReturnDate < :today";
                    $query_o = $dbh -> prepare($sql_o);
                    $query_o->bindParam(':today', $today, PDO::PARAM_STR);
                    $query_o->execute();
                    $overdue=$query_o->rowCount();
                    ?>
                    <h3><?php echo htmlentities($overdue);?></h3>
                    Overdue Books
                </div>
            </div>
        </div>

             <div class="row">
              <div class="col-md-10 col-sm-8 col-xs-12 col-md-offset-1">
                    <div id="carousel-example" class="carousel slide slide-bdr" data-ride="carousel" >
                    <div class="carousel-inner">
                        <div class="item active">
                            <img src="assets/img/1.jpg" alt="" />
                        </div>
                        <div class="item">
                            <img src="assets/img/2.jpg" alt="" />
                        </div>
                        <div class="item">
                            <img src="assets/img/3.jpg" alt="" />
                        </div>
                    </div>
                     <ol class="carousel-indicators">
                        <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example" data-slide-to="1"></li>
                        <li data-target="#carousel-example" data-slide-to="2"></li>
                    </ol>
                     <a class="left carousel-control" href="#carousel-example" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#carousel-example" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
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
