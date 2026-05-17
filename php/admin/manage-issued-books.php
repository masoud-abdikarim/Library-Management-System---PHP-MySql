<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:../../index.php');
}
else{ 
    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Borrowed Books';
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
                <h4 class="header-line">CURRENTLY BORROWED BOOKS</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Borrowed Books List
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Student Name</th>
                                        <th>Book ID</th>
                                        <th>Book Name</th>
                                        <th>Issued Date</th>
                                        <th>Expected Return</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 
// Status 0: Borrowed, 2: Pending Return Approval
$sql = "SELECT tblstudents.FullName,tblstudents.EmailId,tblbooks.BookName,tblbooks.id as bookid,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ExpectedReturnDate,tblissuedbookdetails.ReturnStatus,tblissuedbookdetails.id as rid 
        FROM tblissuedbookdetails 
        JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId 
        JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
        WHERE tblissuedbookdetails.ReturnStatus IN (0, 2) 
        ORDER BY tblissuedbookdetails.id DESC";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $result)
{               ?>                                      
                                    <tr class="odd gradeX">
                                        <td class="center"><?php echo htmlentities($cnt);?></td>
                                        <td class="center">
                                            <div><?php echo htmlentities($result->FullName);?></div>
                                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;"><i class="fa fa-envelope" style="margin-right: 4px;"></i><?php echo htmlentities($result->EmailId);?></div>
                                        </td>
                                        <td class="center">BK-<?php echo htmlentities($result->bookid);?></td>
                                        <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                        <td class="center"><?php echo htmlentities($result->IssuesDate);?></td>
                                        <td class="center"><?php echo htmlentities($result->ExpectedReturnDate);?></td>
                                        <td class="center">
                                            <?php if($result->ReturnStatus==0) { 
                                                $today = date('Y-m-d');
                                                if($result->ExpectedReturnDate < $today) {
                                                    echo '<span class="label label-danger">Overdue</span>';
                                                } else {
                                                    echo '<span class="label label-primary">Borrowed</span>';
                                                }
                                            } else if($result->ReturnStatus==2) {
                                                echo '<span class="label label-warning">Pending Return</span>';
                                            } ?>
                                        </td>
                                        <td class="center">
                                            <a href="update-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Details</a>
                                        </td>
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
    <script src="../../js/admin/jquery-1.10.2.js"></script>
    <script src="../../js/admin/dataTables/jquery.dataTables.js"></script>
    <script src="../../js/admin/custom.js"></script>
</body>
</html>
<?php } ?>
