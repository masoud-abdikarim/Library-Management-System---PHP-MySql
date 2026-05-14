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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="Library Management System - Sign in to access your account" />
    <title>Library ERP | Sign In</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap' rel='stylesheet' />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background-color: #0f172a;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Left Panel - Image */
        .login-image-panel {
            flex: 1;
            position: relative;
            background: url('assets/img/library-hero.png') center/cover no-repeat;
            display: flex;
            align-items: flex-end;
            padding: 60px;
        }

        .login-image-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15,23,42,0.3) 0%, rgba(15,23,42,0.85) 100%);
        }

        .image-content {
            position: relative;
            z-index: 2;
            color: #fff;
            max-width: 500px;
        }

        .image-content .brand {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 20px;
        }

        .image-content h1 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .image-content p {
            font-size: 17px;
            color: #cbd5e1;
            line-height: 1.7;
            max-width: 420px;
        }

        .image-stats {
            display: flex;
            gap: 40px;
            margin-top: 35px;
            padding-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }

        .image-stats .stat h3 {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin: 0;
        }

        .image-stats .stat span {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Right Panel - Form */
        .login-form-panel {
            width: 520px;
            min-width: 520px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            background-color: #fff;
        }

        .form-container {
            width: 100%;
            max-width: 380px;
        }

        .form-logo {
            margin-bottom: 40px;
        }

        .form-logo h2 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .form-logo h2 span {
            color: #3b82f6;
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header h3 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
        }

        .form-header p {
            font-size: 15px;
            color: #64748b;
            margin: 0;
        }

        .form-field {
            margin-bottom: 22px;
        }

        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            letter-spacing: 0.02em;
        }

        .form-field .input-wrap {
            position: relative;
        }

        .form-field .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 16px;
        }

        .form-field input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .form-field input:focus {
            outline: none;
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }

        .form-field input::placeholder {
            color: #94a3b8;
        }

        .form-extras {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .form-extras label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #475569;
            cursor: pointer;
        }

        .form-extras a {
            font-size: 14px;
            color: #3b82f6;
            font-weight: 600;
            text-decoration: none;
        }

        .form-extras a:hover {
            color: #2563eb;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.02em;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.35);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #f1f5f9;
        }

        .form-footer span {
            color: #64748b;
            font-size: 14px;
        }

        .form-footer a {
            color: #0f172a;
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
        }

        .form-footer a:hover {
            color: #3b82f6;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-image-panel { display: none; }
            .login-form-panel {
                width: 100%;
                min-width: 100%;
                min-height: 100vh;
            }
        }

        @media (max-width: 576px) {
            .login-form-panel { padding: 30px 20px; }
            .form-header h3 { font-size: 24px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Left: Library Image Panel -->
        <div class="login-image-panel">
            <div class="image-content">
                <div class="brand">LIBRARY ERP</div>
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
                    <h2>LIBRARY <span>ERP</span></h2>
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
                            <input type="password" name="password" placeholder="Enter your password" required />
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
</body>
</html>
