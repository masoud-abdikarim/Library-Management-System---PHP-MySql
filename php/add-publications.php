<?php
session_start();
error_reporting(0);
include('config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:../index.php');
}
else{ 

if(isset($_POST['create']))
{
$author=$_POST['author'];
$sql="INSERT INTO  tblauthors(AuthorName) VALUES(:author)";
$query = $dbh->prepare($sql);
$query->bindParam(':author',$author,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$_SESSION['msg']="Publication Listed successfully";
header('location:manage-publications.php');
}
else 
{
$_SESSION['error']="Something went wrong. Please try again";
header('location:manage-publications.php');
}

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Add Author';
?>
<?php include('admin-head.php'); ?>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('admin-header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Add Publication</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<div class="panel panel-info">
<div class="panel-heading">
Publication Info
</div>
<div class="panel-body">
<form role="form" method="post">
<div class="form-group">
<label>Publication Name</label>
<input class="form-control" type="text" name="author" autocomplete="off"  required />
</div>

<button type="submit" name="create" class="btn btn-info">Add </button>

                                    </form>
                            </div>
                        </div>
                            </div>

        </div>
   
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CORE JQUERY  -->
    <script src="../js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->      <!-- CUSTOM SCRIPTS  -->
    <script src="../js/admin-custom.js"></script>
</body>
</html>
<?php } ?>
