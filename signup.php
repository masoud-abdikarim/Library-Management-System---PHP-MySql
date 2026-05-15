<?php 
session_start();
include('includes/config.php');
error_reporting(0);

$error = '';
$success = '';

if(isset($_POST['signup']))
{
//Code for student ID
$count_my_page = ("studentid.txt");
$hits = file($count_my_page);
$hits[0] ++;
$fp = fopen($count_my_page , "w");
 fputs($fp , "$hits[0]");
 fclose($fp); 
$StudentId= $hits[0];   
$fname=$_POST['fullanme'];
$mobileno=$_POST['mobileno'];
$email=$_POST['email']; 
$password=md5($_POST['password']); 
$status=1;
$sql="INSERT INTO  tblstudents(StudentId,FullName,MobileNumber,EmailId,Password,Status) VALUES(:StudentId,:fname,:mobileno,:email,:password,:status)";
$query = $dbh->prepare($sql);
$query->bindParam(':StudentId',$StudentId,PDO::PARAM_STR);
$query->bindParam(':fname',$fname,PDO::PARAM_STR);
$query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
$query->bindParam(':email',$email,PDO::PARAM_STR);
$query->bindParam(':password',$password,PDO::PARAM_STR);
$query->bindParam(':status',$status,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
    $success = "Registration successful! Your Student ID is: " . $StudentId;
}
else 
{
    $error = "Something went wrong. Please try again.";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="NEW HARGEISA LIBRARY - Create your account" />
    <title>Library ERP | Create Account</title>
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

        .signup-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .signup-image-panel {
            flex: 1;
            position: relative;
            background: url('assets/img/library-hero.png') center/cover no-repeat;
            display: flex;
            align-items: center;
            padding: 60px;
        }

        .signup-image-panel::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(30,64,175,0.7) 100%);
        }

        .image-content {
            position: relative;
            z-index: 2;
            color: #fff;
            max-width: 480px;
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
            font-size: 40px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .image-content p {
            font-size: 17px;
            color: #cbd5e1;
            line-height: 1.7;
        }

        .benefits {
            margin-top: 40px;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
        }

        .benefit-icon {
            width: 42px;
            height: 42px;
            background: rgba(59,130,246,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #93c5fd;
            font-size: 18px;
            flex-shrink: 0;
        }

        .benefit-text {
            font-size: 15px;
            color: #e2e8f0;
            font-weight: 500;
        }

        .signup-form-panel {
            width: 540px;
            min-width: 540px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 50px 60px;
            background-color: #fff;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
        }

        .form-logo h2 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 35px 0;
        }

        .form-logo h2 span { color: #3b82f6; }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h3 {
            font-size: 26px;
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
            margin-bottom: 18px;
        }

        .form-field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
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
            font-size: 15px;
        }

        .form-field input {
            width: 100%;
            padding: 13px 16px 13px 46px;
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

        .form-field input::placeholder { color: #94a3b8; }

        .btn-signup {
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
            margin-top: 8px;
        }

        .btn-signup:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.35);
        }

        .form-footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }

        .form-footer span { color: #64748b; font-size: 14px; }
        .form-footer a { color: #0f172a; font-weight: 700; text-decoration: none; margin-left: 4px; }
        .form-footer a:hover { color: #3b82f6; }

        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
            padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 500;
            margin-bottom: 18px; display: flex; align-items: center; gap: 10px;
        }

        .alert-success-custom {
            background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534;
            padding: 12px 16px; border-radius: 10px; font-size: 14px; font-weight: 500;
            margin-bottom: 18px; display: flex; align-items: center; gap: 10px;
        }

        @media (max-width: 992px) {
            .signup-image-panel { display: none; }
            .signup-form-panel { width: 100%; min-width: 100%; }
        }

        @media (max-width: 576px) {
            .signup-form-panel { padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="signup-wrapper">
        <!-- Left: Library Image Panel -->
        <div class="signup-image-panel">
            <div class="image-content">
                <div class="brand">LIBRARY ERP</div>
                <h1>Start Your Reading Journey Today</h1>
                <p>Create a free account and gain access to our entire collection of books, journals, and digital resources.</p>

                <div class="benefits">
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="fa fa-book"></i></div>
                        <div class="benefit-text">Browse and borrow from thousands of titles</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="fa fa-clock-o"></i></div>
                        <div class="benefit-text">Track your borrowing history and due dates</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="fa fa-search"></i></div>
                        <div class="benefit-text">Search by title, author, or category instantly</div>
                    </div>
                    <div class="benefit-item">
                        <div class="benefit-icon"><i class="fa fa-shield"></i></div>
                        <div class="benefit-text">Secure account with personal dashboard</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Signup Form -->
        <div class="signup-form-panel">
            <div class="form-container">
                <div class="form-logo">
                    <h2>LIBRARY <span>ERP</span></h2>
                </div>

                <div class="form-header">
                    <h3>Create Account</h3>
                    <p>Fill in your details to get started</p>
                </div>

                <?php if($error != '') { ?>
                    <div class="alert-error">
                        <i class="fa fa-exclamation-circle"></i>
                        <?php echo htmlentities($error); ?>
                    </div>
                <?php } ?>

                <?php if($success != '') { ?>
                    <div class="alert-success-custom">
                        <i class="fa fa-check-circle"></i>
                        <?php echo htmlentities($success); ?>
                    </div>
                <?php } ?>

                <form method="post" autocomplete="off">
                    <div class="form-field">
                        <label>Full Name</label>
                        <div class="input-wrap">
                            <i class="fa fa-user-o"></i>
                            <input type="text" name="fullanme" placeholder="John Doe" required />
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Mobile Number</label>
                        <div class="input-wrap">
                            <i class="fa fa-phone"></i>
                            <input type="text" name="mobileno" placeholder="+1 234 567 890" required />
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Email Address</label>
                        <div class="input-wrap">
                            <i class="fa fa-envelope-o"></i>
                            <input type="email" name="email" placeholder="you@example.com" required />
                        </div>
                    </div>

                    <div class="form-field">
                        <label>Password</label>
                        <div class="input-wrap">
                            <i class="fa fa-lock"></i>
                            <input type="password" name="password" placeholder="Create a strong password" required />
                        </div>
                    </div>

                    <button type="submit" name="signup" class="btn-signup">
                        Create Account
                    </button>

                    <div class="form-footer">
                        <span>Already have an account?</span>
                        <a href="index.php">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
