<?php
session_start();
include('includes/config.php');
error_reporting(0);
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 
if(isset($_POST['change']))
  {
$password=md5($_POST['password']);
$newpassword=md5($_POST['newpassword']);
$username=$_SESSION['alogin'];
  $sql ="SELECT Password FROM admin where UserName=:username and Password=:password";
$query= $dbh -> prepare($sql);
$query-> bindParam(':username', $username, PDO::PARAM_STR);
$query-> bindParam(':password', $password, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query -> rowCount() > 0)
{
$con="update admin set Password=:newpassword where UserName=:username";
$chngpwd1 = $dbh->prepare($con);
$chngpwd1-> bindParam(':username', $username, PDO::PARAM_STR);
$chngpwd1-> bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
$chngpwd1->execute();
$msg="Your Password succesfully changed";
}
else {
$error="Your current password is wrong";  
}
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY |';
?>
<?php include('includes/head.php'); ?>
</head>
<script type="text/javascript">
function valid() {
    var newPass = document.chngpwd.newpassword.value;
    var confirmPass = document.chngpwd.confirmpassword.value;
    var errorSpan = document.getElementById('pass-error');
    
    if(newPass != confirmPass) {
        errorSpan.style.display = 'block';
        document.chngpwd.confirmpassword.style.borderColor = '#f56565';
        document.chngpwd.confirmpassword.focus();
        return false;
    }
    errorSpan.style.display = 'none';
    return true;
}
</script>

<body>
    <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
<div class="content-wrapper">
<div class="container">
    <div class="row pad-botm">
        <div class="col-md-12">
            <h4 class="header-line">Security Settings</h4>
        </div>
    </div>

    <div class="password-wrapper">
        <div class="password-card">
            <h3>Change Password</h3>
            <p>Update your administrator credentials below.</p>

            <?php if($error){?><div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div><?php } 
            else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div><?php }?>            

            <form role="form" method="post" onSubmit="return valid();" name="chngpwd">
                <div class="form-group">
                    <label>Current Password</label>
                    <div class="input-group">
                        <input class="form-control" type="password" name="password" placeholder="Enter current password" autocomplete="off" required  />
                    </div>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <div class="input-group">
                        <input class="form-control" type="password" name="newpassword" placeholder="Enter new password" autocomplete="off" required  />
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="input-group">
                        <input class="form-control" type="password" name="confirmpassword" placeholder="Re-type new password" autocomplete="off" required  />
                    </div>
                    <span id="pass-error" class="error-text">Passwords do not match!</span>
                </div>

                <button type="submit" name="change" class="btn-submit">Update Password</button> 
            </form>
        </div>
    </div>
</div>
</div>  
<!---LOGIN PABNEL END-->            
             
 
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
