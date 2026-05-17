<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:../../index.php');
}
else{ 

if(isset($_POST['add']))
{
$bookname=$_POST['bookname'];
$category=$_POST['category'];
$author=NULL;
$isbn=NULL;
$price=$_POST['price'];
$copies=$_POST['copies'];
$sql="INSERT INTO  tblbooks(BookName,CatId,AuthorId,ISBNNumber,BookPrice,Copies,IssuedCopies) VALUES(:bookname,:category,:author,:isbn,:price,:copies,0)";
$query = $dbh->prepare($sql);
$query->bindParam(':bookname',$bookname,PDO::PARAM_STR);
$query->bindParam(':category',$category,PDO::PARAM_STR);
$query->bindValue(':author',$author,PDO::PARAM_NULL);
$query->bindValue(':isbn',$isbn,PDO::PARAM_NULL);
$query->bindParam(':price',$price,PDO::PARAM_STR);
$query->bindParam(':copies',$copies,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$_SESSION['msg']="Book Listed successfully";
header('location:manage-books.php');
}
else 
{
$_SESSION['error']="Something went wrong. Please try again";
header('location:manage-books.php');
}

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Add Book';
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
                <h4 class="header-line">Add Book</h4>
                
                            </div>

</div>
<div class="row">
<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
<div class="panel panel-info">
<div class="panel-heading">
Book Info
</div>
<div class="panel-body">
<form role="form" method="post">
<div class="form-group">
<label>Book Name<span style="color:red;">*</span></label>
<input class="form-control" type="text" name="bookname" autocomplete="off"  required />
</div>

<div class="form-group">
<label> Category<span style="color:red;">*</span></label>
<select class="form-control" name="category" required="required">
<option value=""> Select Category</option>
<?php 
$status=1;
$sql = "SELECT * from  tblcategory where Status=:status";
$query = $dbh -> prepare($sql);
$query -> bindParam(':status',$status, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>  
<option value="<?php echo htmlentities($result->id);?>"><?php echo htmlentities($result->CategoryName);?></option>
 <?php }} ?> 
</select>
</div>


 
 <div class="form-group">
 <label>Available Books<span style="color:red;">*</span></label>
 <input class="form-control" type="text" name="copies" autocomplete="off"   required="required" />
 </div>
 
 <div class="form-group">
 <label>Price<span style="color:red;">*</span></label>
 <input class="form-control" type="text" name="price" autocomplete="off"   required="required" />
 </div>
<button type="submit" name="add" class="btn btn-info">Add </button>

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
    <script src="../../js/admin/jquery-1.10.2.js"></script>
    <!-- BOOTSTRAP SCRIPTS  -->      <!-- CUSTOM SCRIPTS  -->
    <script src="../../js/admin/custom.js"></script>
</body>
</html>
<?php } ?>
