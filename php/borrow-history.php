<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
    {   
header('location:../index.php');
}
else{ 
    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Borrow History';
$use_datatables = true;
?>
<?php include('includes/head.php'); ?>
</head>
<body>
<?php include('includes/header.php');?>
    <div class="content-wrapper">
         <div class="container">
        <div class="row pad-botm">
            <div class="col-md-12">
                <h4 class="header-line">MY BORROW HISTORY</h4>
            </div>
        </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Returned Books</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Book ID</th>
                                            <th>Book Name</th>
                                            <th>Borrowed Date</th>
                                            <th>Returned Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
$sid=$_SESSION['stdid'];
$sql="SELECT tblbooks.BookName,tblbooks.id as bookid,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnDate 
      FROM tblissuedbookdetails 
      JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
      WHERE tblissuedbookdetails.StudentId=:sid AND tblissuedbookdetails.ReturnStatus = 1 
      ORDER BY tblissuedbookdetails.ReturnDate DESC";
$query = $dbh -> prepare($sql);
$query-> bindParam(':sid', $sid, PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                        <tr class="odd gradeX">
                                            <td class="center"><?php echo htmlentities($cnt);?></td>
                                            <td class="center">BK-<?php echo htmlentities($result->bookid);?></td>
                                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                            <td class="center"><?php echo htmlentities($result->IssuesDate);?></td>
                                            <td class="center"><?php echo htmlentities($result->ReturnDate);?></td>
                                            <td class="center"><span class="label label-success">Returned</span></td>
                                        </tr>
 <?php $cnt=$cnt+1;}} ?>                                      
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    <script src="../js/jquery-1.10.2.js"></script>
    <script src="../js/dataTables/jquery.dataTables.js"></script>
    <script src="../js/custom.js"></script>
</body>
</html>
<?php } ?>
