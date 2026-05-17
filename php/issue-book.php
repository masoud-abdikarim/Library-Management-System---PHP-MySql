<?php
session_start();
error_reporting(0);
include('config.php');
if(strlen($_SESSION['alogin'])==0)
    {   
header('location:../index.php');
}
else{ 

if(isset($_POST['issue']))
{
$studentid=strtoupper($_POST['studentid']);
$bookid=$_POST['bookdetails'];
$expectedreturndate = $_POST['expectedreturndate'];
$status = 0; // 0 = Borrowed

// Check availability
$sql_check = "SELECT Copies, IssuedCopies FROM tblbooks WHERE id=:bookid";
$query_check = $dbh->prepare($sql_check);
$query_check->bindParam(':bookid',$bookid,PDO::PARAM_STR);
$query_check->execute();
$book_data = $query_check->fetch(PDO::FETCH_OBJ);

if($book_data->IssuedCopies < $book_data->Copies) {
    $sql="INSERT INTO tblissuedbookdetails(StudentID,BookId,ExpectedReturnDate,ReturnStatus) VALUES(:studentid,:bookid,:expectedreturndate,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
    $query->bindParam(':bookid',$bookid,PDO::PARAM_STR);
    $query->bindParam(':expectedreturndate',$expectedreturndate,PDO::PARAM_STR);
    $query->bindParam(':status',$status,PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if($lastInsertId)
    {
        // Update issued copies count
        $sql_update="UPDATE tblbooks SET IssuedCopies = IssuedCopies + 1 WHERE id=:bookid";
        $query_update = $dbh->prepare($sql_update);
        $query_update->bindParam(':bookid',$bookid,PDO::PARAM_STR);
        $query_update->execute();

        $_SESSION['msg']="Book issued successfully";
        header('location:manage-issued-books.php');
    }
    else 
    {
        $_SESSION['error']="Something went wrong. Please try again";
        header('location:manage-issued-books.php');
    }
} else {
    $_SESSION['error']="Book out of stock!";
    header('location:manage-issued-books.php');
}
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Issue a new Book';
?>
<?php include('admin-head.php'); ?>
</head>
<body>
      <!------MENU SECTION START-->
<?php include('admin-header.php');?>
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
                        <form role="form" method="post">

                            <div class="form-group">
                                <label>Student Name / Email<span style="color:red;">*</span></label>
                                <input type="hidden" name="studentid" id="studentid" required>
                                
                                <div class="student-search-container" style="position:relative;">
                                    <div class="custom-select-search-container" style="border:none; padding:0; background:transparent;">
                                        <i class="fa fa-search"></i>
                                        <input type="text" id="studentSearchInput" class="form-control" placeholder="Type name, email or ID to search..." autocomplete="off">
                                    </div>
                                    <ul id="studentSearchResults" class="custom-options" style="display:none; position:absolute; z-index:1000; width:100%; background:#fff; border:1px solid var(--border); border-top:none; border-radius:0 0 10px 10px; box-shadow:var(--shadow-lg); max-height:250px; overflow-y:auto; padding:0; margin:0; list-style:none;"></ul>
                                </div>

                                <?php 
                                $sql_std = "SELECT StudentId, FullName, EmailId FROM tblstudents WHERE Status=1";
                                $query_std = $dbh->prepare($sql_std);
                                $query_std->execute();
                                $results_std = $query_std->fetchAll(PDO::FETCH_ASSOC);
                                ?>
                                <script>
                                    var studentsList = <?php echo json_encode($results_std); ?>;
                                </script>
                            </div>

                            <div class="form-group">
                                <span id="get_student_name" style="font-size:16px;"></span> 
                            </div>

                            <div class="form-group">
                                <label>Book ID<span style="color:red;">*</span></label>
                                <input class="form-control" type="text" name="bookid" id="bookid" onBlur="getbook()"  required="required" />
                            </div>

                            <div class="form-group">
                                <label>Book Title<span style="color:red;">*</span></label>
                                <select  class="form-control" name="bookdetails" id="get_book_name" readonly>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Expected Return Date<span style="color:red;">*</span></label>
                                <input class="form-control" type="date" name="expectedreturndate" required />
                            </div>

                            <div class="form-group">
                                <button type="submit" name="issue" id="submit" class="btn btn-primary" style="padding: 12px 30px;">Issue Book </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
     <!-- CONTENT-WRAPPER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <script src="../js/jquery-1.10.2.js"></script>
      <!-- CUSTOM SCRIPTS  -->
    <script src="../js/admin-custom.js"></script>
    <script>
        // global AJAX lookup functions
        function getstudent() {
            jQuery.ajax({
                url: "get_student.php",
                data: 'studentid=' + jQuery("#studentid").val(),
                type: "POST",
                success: function(data) {
                    jQuery("#get_student_name").html(data);
                },
                error: function() {}
            });
        }

        function getbook() {
            jQuery.ajax({
                url: "get_book.php",
                data: 'bookid=' + jQuery("#bookid").val(),
                type: "POST",
                success: function(data) {
                    jQuery("#get_book_name").html(data);
                },
                error: function() {}
            });
        }

        // Custom student search functionality
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('studentSearchInput');
            var resultsContainer = document.getElementById('studentSearchResults');
            var hiddenInput = document.getElementById('studentid');
            var selectedStudent = null;

            function renderResults(filter) {
                resultsContainer.innerHTML = '';
                var filterLower = (filter || '').toLowerCase();
                var count = 0;
                var frag = document.createDocumentFragment();

                for (var i = 0; i < studentsList.length; i++) {
                    var student = studentsList[i];
                    if (filterLower && 
                        student.FullName.toLowerCase().indexOf(filterLower) === -1 && 
                        student.EmailId.toLowerCase().indexOf(filterLower) === -1 && 
                        student.StudentId.toLowerCase().indexOf(filterLower) === -1) {
                        continue;
                    }

                    var li = document.createElement('li');
                    li.className = 'custom-option';
                    
                    li.innerHTML = '<div class="custom-option-layout">' +
                        '<div class="col-name"><i class="fa fa-user-o"></i> ' + student.FullName + '</div>' +
                        '<div class="col-id">ID: ' + student.StudentId + '</div>' +
                        '<div class="col-email">' + student.EmailId + '</div>' +
                    '</div>';

                    li.onclick = (function(s) {
                        return function() {
                            hiddenInput.value = s.StudentId;
                            searchInput.value = s.FullName; // show name in input
                            selectedStudent = s;
                            resultsContainer.style.display = 'none';
                            getstudent(); // Call the existing backend functionality
                            searchInput.classList.remove('is-open');
                        };
                    })(student);

                    frag.appendChild(li);
                    count++;
                }

                if (count === 0) {
                    var li = document.createElement('li');
                    li.className = 'custom-option';
                    li.textContent = 'No student found';
                    li.style.pointerEvents = 'none';
                    li.style.color = '#94a3b8';
                    frag.appendChild(li);
                }

                resultsContainer.appendChild(frag);
                resultsContainer.style.display = 'block';
                searchInput.classList.add('is-open');
            }

            searchInput.addEventListener('input', function() {
                renderResults(this.value);
            });

            searchInput.addEventListener('focus', function() {
                renderResults(this.value);
            });

            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && !resultsContainer.contains(e.target)) {
                    resultsContainer.style.display = 'none';
                    searchInput.classList.remove('is-open');
                }
            });
            
            // clear hidden input if search is cleared
            searchInput.addEventListener('change', function() {
               if(this.value.trim() === '') {
                   hiddenInput.value = '';
               }
            });
        });
    </script>

</body>
</html>
<?php } ?>
