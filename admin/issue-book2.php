<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:index.php');
}
else{ 

if(isset($_POST['issue']))
{

$studentid=strtoupper($_POST['studentid']);
$bookid=$_POST['bookdetails'];
$sql="INSERT INTO  tblissuedbookdetails(StudentID,BookId) VALUES(:studentid,:bookid)";
$query = $dbh->prepare($sql);
$query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
$query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();

$bookid=$_GET['ISBNNumber'];
$studentid=$_GET['StudentID'];
$sql="DELETE FROM tblrequestedbookdetails WHERE StudentID=:studentid and ISBNNumber=:bookid";
$query = $dbh->prepare($sql);
$query -> bindParam(':studentid',$studentid, PDO::PARAM_STR);
$query -> bindParam(':bookid',$bookid, PDO::PARAM_STR);
$query->execute();

$sql="update tblbooks set IssuedCopies=IssuedCopies+1 where ISBNNumber=:bookid";
$query = $dbh->prepare($sql);
$query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
$query->execute();

$_SESSION['msg']="Book issued successfully";
header('location:manage-issued-books.php');

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Issue a new Book';
?>
<?php include('includes/head.php'); ?>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('includes/header.php');?>
<!-- MENU SECTION END-->
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">Issue a New Book</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-10 col-sm-6 col-xs-12 col-md-offset-1">
<div class="panel panel-info">
<div class="panel-heading">
Issue a New Book
</div>
<div class="panel-body">





<form method="post" name="chngpwd" class="form-horizontal" onSubmit="return valid();">
										
<?php	
$bookid=$_GET['ISBNNumber'];
$stdid=$_GET['StudentID'];
?>										
<div class="form-group">
<label>Student Name<span style="color:red;">*</span></label>
<select class="form-control select2" name="studentid" id="studentid" onChange="getstudent()" required>
    <option value="">Search and select student...</option>
    <?php 
    $sql_std = "SELECT StudentId, FullName FROM tblstudents WHERE Status=1";
    $query_std = $dbh->prepare($sql_std);
    $query_std->execute();
    $results_std = $query_std->fetchAll(PDO::FETCH_OBJ);
    if($query_std->rowCount() > 0) {
        foreach($results_std as $result_std) {
            $selected = ($stdid == $result_std->StudentId) ? 'selected' : '';
            echo '<option value="'.htmlentities($result_std->StudentId).'" '.$selected.'>'.htmlentities($result_std->FullName).' (ID: '.htmlentities($result_std->StudentId).')</option>';
        }
    }
    ?>
</select>
</div>

<div class="form-group">
<span id="get_student_name" style="font-size:16px;"></span> 
</div>

<div class="form-group">
<label>BookID<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="booikid" id="bookid" value="<?php echo htmlentities($bookid);?>" onBlur="getbook()"  required="required" />
</div>

 <div class="form-group">
  Book Title<select  class="form-control" name="bookdetails" id="get_book_name" readonly> 
 </select>
 </div>
											
<?php if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
else if($msg){?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php }?>

<button type="submit" name="issue" id="submit" class="btn btn-info">Issue Book </button>

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
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->    <!-- SELECT2 SCRIPTS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Search and select student...",
                allowClear: true
            });
            // Trigger change if prefilled from GET parameters
            if ($('#studentid').val() !== '') {
                getstudent();
            }
        });
    </script>

</body>
</html>
<?php } ?>
