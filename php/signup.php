<?php 
session_start();
include('config.php');
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
$StudentId= "SID" . str_pad($hits[0], 3, "0", STR_PAD_LEFT);   
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
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>NEW HARGEISA LIBRARY | Create Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <link href="../css/font-awesome.css" rel="stylesheet" />
    <link href="../css/framework.css" rel="stylesheet" />
    <link href="../css/auth.css" rel="stylesheet" />
</head>
<body class="auth-body">
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
                        <a href="../index.php">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
