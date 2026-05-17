import re

p = r'c:\xampp\htdocs\Library-Management-System\admin\dashboard.php'
with open(p, encoding='utf-8') as f:
    t = f.read()

start = t.find('<motion class="content-wrapper">')
if start == -1:
    start = t.find('<motion class="content-wrapper">')
if start == -1:
    start = t.find('<div class="content-wrapper">')
end = t.find('<div class="row">', t.find('Overdue Books'))

# find carousel row
carousel_start = t.find('             <div class="row">', t.find('Overdue Books'))
if carousel_start == -1:
    carousel_start = t.find('<motion class="row">', t.find('Overdue Books'))

new_main = '''    <main class="content-wrapper">
        <div class="container">
            <h1 class="page-header">Admin Dashboard</h1>

            <div class="stat-grid">
                <motion class="stat-card stat-card--success">
                    <i class="fa fa-book"></i>
<?php 
$sql ="SELECT id from tblbooks ";
$query = $dbh -> prepare($sql);
$query->execute();
$listdbooks=$query->rowCount();
?>
                    <h3><?php echo htmlentities($listdbooks);?></h3>
                    <p>Total Books</p>
                </div>
                <div class="stat-card stat-card--primary">
                    <i class="fa fa-file-archive-o"></i>
<?php 
$sql5 ="SELECT id from tblcategory ";
$query5 = $dbh -> prepare($sql5);
$query5->execute();
$listdcats=$query5->rowCount();
?>
                    <h3><?php echo htmlentities($listdcats);?></h3>
                    <p>Categories</p>
                </div>
                <div class="stat-card stat-card--danger">
                    <i class="fa fa-users"></i>
<?php 
$sql3 ="SELECT id from tblstudents ";
$query3 = $dbh -> prepare($sql3);
$query3->execute();
$regstds=$query3->rowCount();
?>
                    <h3><?php echo htmlentities($regstds);?></h3>
                    <p>Registered Users</p>
                </div>
                <div class="stat-card stat-card--primary">
                    <i class="fa fa-exchange"></i>
<?php 
$sql_b ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0";
$query_b = $dbh -> prepare($sql_b);
$query_b->execute();
$borrowed=$query_b->rowCount();
?>
                    <h3><?php echo htmlentities($borrowed);?></h3>
                    <p>Currently Borrowed</p>
                </motion>
                <div class="stat-card stat-card--warning">
                    <i class="fa fa-clock-o"></i>
<?php 
$sql_p ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=2";
$query_p = $dbh -> prepare($sql_p);
$query_p->execute();
$pending=$query_p->rowCount();
?>
                    <h3><?php echo htmlentities($pending);?></h3>
                    <p>Pending Returns</p>
                </div>
                <div class="stat-card stat-card--success">
                    <i class="fa fa-check-circle"></i>
<?php 
$sql_r ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=1";
$query_r = $dbh -> prepare($sql_r);
$query_r->execute();
$returned=$query_r->rowCount();
?>
                    <h3><?php echo htmlentities($returned);?></h3>
                    <p>Returned Books</p>
                </div>
                <div class="stat-card stat-card--danger">
                    <i class="fa fa-exclamation-triangle"></i>
<?php 
$today = date('Y-m-d');
$sql_o ="SELECT id from tblissuedbookdetails WHERE ReturnStatus=0 AND ExpectedReturnDate < :today";
$query_o = $dbh -> prepare($sql_o);
$query_o->bindParam(':today', $today, PDO::PARAM_STR);
$query_o->execute();
$overdue=$query_o->rowCount();
?>
                    <h3><?php echo htmlentities($overdue);?></h3>
                    <p>Overdue Books</p>
                </div>
            </div>

'''

wrong = chr(109)+chr(111)+chr(116)+chr(105)+chr(111)+chr(110)
right = chr(100)+chr(105)+chr(118)
new_main = new_main.replace(wrong, right)

# Replace from content-wrapper to carousel row
pattern = r'<div class="content-wrapper">.*?(?=\s*<div class="row">\s*\n\s*<motion class="col-md-10)'
pattern2 = r'<div class="content-wrapper">.*?(?=\s*<div class="row">\s*\n\s*<div class="col-md-10)'
m = re.search(pattern2, t, re.DOTALL)
if m:
    t = t[:m.start()] + new_main + t[m.end():]
    t = t.replace('    </div>\n    </motion>', '        </div>\n    </main>', 1)
    with open(p, 'w', encoding='utf-8') as f:
        f.write(t)
    print('updated')
else:
    print('pattern not found')
