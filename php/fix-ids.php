<?php
include('config.php');
$sql = "UPDATE tblstudents SET StudentId = CONCAT('SID', LPAD(StudentId, 3, '0')) WHERE StudentId REGEXP '^[0-9]+$'";
$query = $dbh->prepare($sql);
$query->execute();
echo 'Updated ' . $query->rowCount() . ' rows.';
?>
