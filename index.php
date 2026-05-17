<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Clear any existing sessions
if($_SESSION['login']!=''){
    $_SESSION['login']='';
}

$error = '';
$success = '';

if(isset($_POST['login']))
{
    $identifier = $_POST['identifier'];
    $password = md5($_POST['password']);

    // Step 1: Check Admin table first
    $sql_admin = "SELECT UserName, Password FROM admin WHERE UserName=:identifier AND Password=:password";
    $query_admin = $dbh->prepare($sql_admin);
    $query_admin->bindParam(':identifier', $identifier, PDO::PARAM_STR);
    $query_admin->bindParam(':password', $password, PDO::PARAM_STR);
    $query_admin->execute();

    if($query_admin->rowCount() > 0)
    {
        // Admin login successful
        $_SESSION['alogin'] = $identifier;
        echo "<script type='text/javascript'> document.location ='admin/dashboard.php'; </script>";
    }
    else
    {
        // Step 2: Check Student table
        $sql_user = "SELECT FullName, EmailId, Password, StudentId, Status FROM tblstudents WHERE EmailId=:identifier AND Password=:password";
        $query_user = $dbh->prepare($sql_user);
        $query_user->bindParam(':identifier', $identifier, PDO::PARAM_STR);
        $query_user->bindParam(':password', $password, PDO::PARAM_STR);
        $query_user->execute();
        $results = $query_user->fetchAll(PDO::FETCH_OBJ);

        if($query_user->rowCount() > 0)
        {
            foreach ($results as $result) {
                $_SESSION['stdid'] = $result->StudentId;
                $_SESSION['username'] = $result->FullName;
                if($result->Status == 1)
                {
                    $_SESSION['login'] = $identifier;
                    echo "<script type='text/javascript'> document.location ='dashboard.php'; </script>";
                } else {
                    $error = "Your account has been blocked. Please contact the administrator.";
                }
            }
        }
        else
        {
            $error = "Invalid email or password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NEW HARGEISA LIBRARY | Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/framework.css" rel="stylesheet" />
    <link href="assets/css/auth.css" rel="stylesheet" />
</head>
<body class="auth-body">
    <div class="login-wrapper">
        <!-- Left: Library Image Panel -->
        <div class="login-image-panel">
            <div class="image-content">
                <div class="brand">NEW HARGEISA LIBRARY</div>
                <h1>Discover a World of Knowledge</h1>
                <p>Access thousands of books, manage your borrowing, and explore an ever-growing collection — all from one powerful platform.</p>
                <div class="image-stats">
                    <div class="stat">
                        <?php 
                        $sql_bk = "SELECT id FROM tblbooks";
                        $query_bk = $dbh->prepare($sql_bk);
                        $query_bk->execute();
                        $total_books = $query_bk->rowCount();
                        ?>
                        <h3><?php echo $total_books; ?>+</h3>
                        <span>Books Available</span>
                    </div>
                    <div class="stat">
                        <?php 
                        $sql_cat = "SELECT id FROM tblcategory WHERE Status=1";
                        $query_cat = $dbh->prepare($sql_cat);
                        $query_cat->execute();
                        $total_cats = $query_cat->rowCount();
                        ?>
                        <h3><?php echo $total_cats; ?>+</h3>
                        <span>Categories</span>
                    </div>
                    <div class="stat">
                        <?php 
                        $sql_usr = "SELECT id FROM tblstudents";
                        $query_usr = $dbh->prepare($sql_usr);
                        $query_usr->execute();
                        $total_users = $query_usr->rowCount();
                        ?>
                        <h3><?php echo $total_users; ?>+</h3>
                        <span>Active Users</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Login Form Panel -->
        <div class="login-form-panel">
            <div class="form-container">
                <div class="form-logo">
                    <h2>NEW HARGEISA <span>LIBRARY</span></h2>
                </div>

                <div class="form-header">
                    <h3>Welcome back</h3>
                    <p>Sign in to your account to continue</p>
                </div>

                <?php if($error != '') { ?>
                    <div class="alert-error">
                        <i class="fa fa-exclamation-circle"></i>
                        <?php echo htmlentities($error); ?>
                    </div>
                <?php } ?>

                <form method="post" autocomplete="off">
                    <div class="form-field">
                        <label>Email or Username</label>
                        <div class="input-wrap">
                            <i class="fa fa-user-o"></i>
                            <input type="text" name="identifier" placeholder="Enter your email or username" required />
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Password</label>
                        <div class="input-wrap">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" id="password" placeholder="Enter your password" style="padding-right: 48px;" required />
                            <i class="fa fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <div class="form-extras">
                        <label>
                            <input type="checkbox" /> Remember me
                        </label>
                        <a href="user-forgot-password.php">Forgot password?</a>
                    </div>

                    <button type="submit" name="login" class="btn-login">
                        Sign In
                    </button>

                    <div class="form-footer">
                        <span>Don't have an account?</span>
                        <a href="signup.php">Create Account</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
