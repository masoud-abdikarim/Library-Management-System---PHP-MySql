<?php 
session_start();
include('config.php');
error_reporting(0);
if(strlen($_SESSION['login'])==0)
    {   
header('location:../index.php');
}
else{ 
if(isset($_POST['update']))
{    
$sid=$_SESSION['stdid'];  
$fname=$_POST['fullanme'];
$mobileno=$_POST['mobileno'];

$sql="update tblstudents set FullName=:fname,MobileNumber=:mobileno where StudentId=:sid";
$query = $dbh->prepare($sql);
$query->bindParam(':sid',$sid,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->execute();

echo '<script>alert("Your profile has been updated")</script>';
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | My Profile';
?>
<?php include('head.php'); ?>
</head>
<body>
    <!------MENU SECTION START-->
<?php include('header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">MY PROFILE</h4>
            </div>
        </div>
             <div class="row">
           
<div class="col-md-8 col-md-offset-2">
               <div class="panel panel-default">
                        <div class="panel-heading">
                           Personal Information
                        </div>
                        <div class="panel-body">
                            <form name="signup" method="post">
<?php 
$sid=$_SESSION['stdid'];
$sql="SELECT StudentId,FullName,EmailId,MobileNumber,RegDate,UpdationDate,Status from  tblstudents  where StudentId=:sid ";
$query = $dbh -> prepare($sql);
$query-> bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <label>Student ID : </label>
        <p class="form-control-static" style="font-weight: 600; color: #3b82f6;"><?php echo htmlentities($result->StudentId);?></p>
    </div>
    <div class="col-md-6">
        <label>Profile Status : </label>
        <p class="form-control-static">
            <?php if($result->Status==1){?>
            <span class="label label-success">Active</span>
            <?php } else { ?>
            <span class="label label-danger">Blocked</span>
            <?php }?>
        </p>
    </div>
</div>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <label>Registration Date : </label>
        <p class="form-control-static" style="color: #6b7280;"><?php echo htmlentities($result->RegDate);?></p>
    </div>
    <?php if($result->UpdationDate!=""){?>
    <div class="col-md-6">
        <label>Last Updated : </label>
        <p class="form-control-static" style="color: #6b7280;"><?php echo htmlentities($result->UpdationDate);?></p>
    </div>
    <?php } ?>
</div>

<div class="form-group">
<label>Full Name</label>
<input class="form-control" type="text" name="fullanme" value="<?php echo htmlentities($result->FullName);?>" autocomplete="off" required />
</div>

<div class="form-group">
<label>Mobile Number</label>
<input class="form-control" type="text" name="mobileno" maxlength="10" value="<?php echo htmlentities($result->MobileNumber);?>" autocomplete="off" required />
</div>
                                        
<div class="form-group">
<label>Email Address</label>
<input class="form-control" type="email" name="email" id="emailid" value="<?php echo htmlentities($result->EmailId);?>"  autocomplete="off" required readonly />
<p class="help-block" style="font-size: 12px;">Email cannot be changed.</p>
</div>
<?php }} ?>
                               
<button type="submit" name="update" class="btn btn-primary btn-block" id="submit" style="margin-top: 20px; padding: 12px;">Update Profile </button>

                                    </form>
                            </div>
                        </div>
                            </div>
        </div>
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <script src="../js/jquery-1.10.2.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
<?php } ?>
