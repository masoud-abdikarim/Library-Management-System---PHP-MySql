<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:../../index.php');
}
else{ 
    // Handle Return Approval
    if(isset($_GET['reid'])) {
        $rid = intval($_GET['reid']);
        
        // Get Book ID first to update stock
        $sql_get = "SELECT BookId FROM tblissuedbookdetails WHERE id=:rid";
        $query_get = $dbh->prepare($sql_get);
        $query_get->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query_get->execute();
        $res = $query_get->fetch(PDO::FETCH_OBJ);
        $bookid = $res->BookId;

        // Update status to Returned (1)
        $sql = "UPDATE tblissuedbookdetails SET ReturnStatus=1, ReturnDate=CURRENT_TIMESTAMP WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();

        // Increase stock
        $sql_stock = "UPDATE tblbooks SET IssuedCopies = IssuedCopies - 1 WHERE id=:bookid";
        $query_stock = $dbh->prepare($sql_stock);
        $query_stock->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query_stock->execute();

        $_SESSION['msg'] = "Return approved successfully and stock updated.";
        header('location:pending-returns.php');
    }
    ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Pending Returns';
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
                <h4 class="header-line">PENDING RETURN APPROVALS</h4>
            </div>
        </div>

        <?php if($_SESSION['msg']!="") { ?>
            <div class="row"><div class="col-md-12"><div class="alert alert-success"><?php echo htmlentities($_SESSION['msg']); $_SESSION['msg']=""; ?></div></div></div>
        <?php } ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Requests from Users</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Book ID</th>
                                            <th>Book Name</th>
                                            <th>Borrow Date</th>
                                            <th>Request Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php 
// Status 2: Pending Return Approval
$sql = "SELECT tblstudents.FullName,tblbooks.BookName,tblbooks.id as bookid,tblissuedbookdetails.IssuesDate,tblissuedbookdetails.ReturnRequestDate,tblissuedbookdetails.id as rid 
        FROM tblissuedbookdetails 
        JOIN tblstudents ON tblstudents.StudentId=tblissuedbookdetails.StudentId 
        JOIN tblbooks ON tblbooks.id=tblissuedbookdetails.BookId 
        WHERE tblissuedbookdetails.ReturnStatus = 2 
        ORDER BY tblissuedbookdetails.ReturnRequestDate DESC";
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
                                            <td class="center"><?php echo htmlentities($result->FullName);?></td>
                                            <td class="center">BK-<?php echo htmlentities($result->bookid);?></td>
                                            <td class="center"><?php echo htmlentities($result->BookName);?></td>
                                            <td class="center"><?php echo htmlentities($result->IssuesDate);?></td>
                                            <td class="center"><?php echo htmlentities($result->ReturnRequestDate);?></td>
                                            <td class="center">
                                                <a href="javascript:void(0);" data-href="pending-returns.php?reid=<?php echo htmlentities($result->rid);?>" data-action="confirm" data-title="Approve Return" data-msg="Confirm book return and update stock?" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Approve Return</a>
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
