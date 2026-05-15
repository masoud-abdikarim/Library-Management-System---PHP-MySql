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
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>NEW HARGEISA LIBRARY | </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
  <style>
    .password-wrapper {
        display: flex;
        justify-content: center;
        padding: 40px 0;
    }
    .password-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 40px;
        width: 100%;
        max-width: 480px;
        border: 1px solid #edf2f7;
    }
    .password-card h3 {
        margin: 0 0 10px;
        font-weight: 700;
        color: #1a202c;
        font-size: 24px;
        text-align: center;
    }
    .password-card p {
        color: #718096;
        text-align: center;
        margin-bottom: 30px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        font-weight: 600;
        color: #4a5568;
        display: block;
        margin-bottom: 8px;
    }
    .form-control {
        height: 48px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: #3182ce;
        box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        outline: none;
    }
    .btn-submit {
        background: #3182ce;
        color: #fff;
        font-weight: 700;
        padding: 12px;
        border-radius: 10px;
        border: none;
        width: 100%;
        margin-top: 10px;
        transition: all 0.3s ease;
        font-size: 16px;
    }
    .btn-submit:hover {
        background: #2b6cb0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(49, 130, 206, 0.2);
    }
    .errorWrap {
        padding: 15px;
        margin-bottom: 25px;
        background: #fff5f5;
        border-left: 5px solid #f56565;
        color: #c53030;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .succWrap {
        padding: 15px;
        margin-bottom: 25px;
        background: #f0fff4;
        border-left: 5px solid #48bb78;
        color: #2f855a;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .input-group-text {
        background: none;
        border-right: none;
    }
    .has-error .form-control {
        border-color: #f56565;
    }
    .error-text {
        color: #f56565;
        font-size: 13px;
        margin-top: 5px;
        display: none;
    }
    </style>
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
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="assets/js/bootstrap.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>
