<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['login'])==0)
  { 
header('location:index.php');
}
else{

$sql = "SELECT tblbooks.BookName, tblbooks.Copies, tblbooks.IssuedCopies, tblcategory.CategoryName, tblcategory.id as catid, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid 
        FROM tblbooks 
        JOIN tblcategory ON tblcategory.id=tblbooks.CatId 
        LEFT JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId 
        ORDER BY tblbooks.id DESC";

$query = $dbh->prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Browse Books</title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <style>
        .book-card {
            background: #fff;
            border-radius: 15px;
            padding: 0;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            height: 100%;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .book-cover {
            height: 160px;
            background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }
        .book-info {
            padding: 20px;
        }
        .book-title {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 10px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .book-meta {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .book-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-available { background-color: #d1fae5; color: #065f46; }
        .status-lowstock { background-color: #fef3c7; color: #92400e; }
        .status-unavailable { background-color: #fee2e2; color: #991b1b; }
        
        .search-container {
            position: relative;
        }
        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        .search-container input {
            padding-left: 45px !important;
            border-radius: 12px !important;
            height: 50px !important;
            font-size: 15px !important;
        }

        .category-select {
            border-radius: 12px !important;
            height: 50px !important;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
            padding-right: 40px !important;
        }

        .filter-section {
            background: #fff;
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #f3f4f6;
        }

        #no-results {
            display: none;
            padding: 60px 0;
            text-align: center;
        }
        
        .btn-reset {
            background-color: #f3f4f6;
            color: #4b5563;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        .btn-reset:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php');?>
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">LIBRARY CATALOG</h4>
                </div>
            </div>

            <!-- Enhanced Filter Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="filter-section">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="font-weight: 600; color: #374151; margin-bottom: 12px; display: block;">Quick Search</label>
                                    <div class="search-container">
                                        <i class="fa fa-search"></i>
                                        <input type="text" id="bookSearch" class="form-control" placeholder="Search by book name..." autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <label style="font-weight: 600; color: #374151; margin-bottom: 12px; display: block;">Category</label>
                                    <select id="categoryFilter" class="form-control category-select">
                                        <option value="">All Categories</option>
                                        <?php 
                                        $sql_cat = "SELECT * from tblcategory where Status=1";
                                        $query_cat = $dbh->prepare($sql_cat);
                                        $query_cat->execute();
                                        $categories=$query_cat->fetchAll(PDO::FETCH_OBJ);
                                        foreach($categories as $cat) {
                                            echo "<option value='".htmlentities($cat->id)."'>".htmlentities($cat->CategoryName)."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-12" style="padding-top: 31px; margin-bottom: 15px;">
                                <button type="button" id="resetFilter" class="btn btn-reset">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div class="row" id="bookList">
                <?php if(count($results) > 0) {
                    foreach($results as $result) { 
                        $available = $result->Copies - $result->IssuedCopies;
                        ?>
                        <div class="col-md-3 col-sm-6 col-xs-12 book-item" 
                             data-title="<?php echo strtolower(htmlentities($result->BookName));?>" 
                             data-category="<?php echo htmlentities($result->catid);?>">
                            <div class="book-card">
                                <div class="book-cover">
                                    <i class="fa fa-book fa-4x"></i>
                                </div>
                                <div class="book-info">
                                    <h5 class="book-title" title="<?php echo htmlentities($result->BookName);?>"><?php echo htmlentities($result->BookName);?></h5>
                                    <div class="book-meta"><strong>Book ID:</strong> BK-<?php echo htmlentities($result->bookid);?></div>
                                    <div class="book-meta"><strong>Category:</strong> <?php echo htmlentities($result->CategoryName);?></div>
                                    <div class="book-meta"><strong>Author:</strong> <?php echo htmlentities($result->AuthorName ? $result->AuthorName : "N/A");?></div>
                                    <div class="book-meta"><strong>Price:</strong> <?php echo htmlentities($result->BookPrice);?></div>
                                    
                                    <?php if($available > 2) { ?>
                                        <span class="book-status status-available">Available (<?php echo $available; ?>)</span>
                                    <?php } else if($available > 0) { ?>
                                        <span class="book-status status-lowstock">Low Stock (<?php echo $available; ?>)</span>
                                    <?php } else { ?>
                                        <span class="book-status status-unavailable">Out of Stock</span>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } 
                } ?>
                
                <div id="no-results" class="col-md-12">
                    <i class="fa fa-search fa-4x" style="color: #d1d5db; margin-bottom: 20px;"></i>
                    <h3 style="color: #6b7280; font-weight: 600;">No books found matching your search.</h3>
                    <p style="color: #9ca3af;">Try adjusting your filters or search keywords.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/custom.js"></script>
    
    <script>
        $(document).ready(function() {
            const $searchInput = $('#bookSearch');
            const $categoryFilter = $('#categoryFilter');
            const $bookItems = $('.book-item');
            const $noResults = $('#no-results');

            function performFilter() {
                const searchTerm = $searchInput.val().toLowerCase().trim();
                const selectedCat = $categoryFilter.val();
                let visibleCount = 0;

                $bookItems.each(function() {
                    const $item = $(this);
                    const title = $item.attr('data-title');
                    const category = $item.attr('data-category');

                    const matchesSearch = title.includes(searchTerm);
                    const matchesCategory = selectedCat === "" || category === selectedCat;

                    if (matchesSearch && matchesCategory) {
                        $item.fadeIn(300);
                        visibleCount++;
                    } else {
                        $item.fadeOut(200);
                    }
                });

                if (visibleCount === 0) {
                    $noResults.delay(300).fadeIn(400);
                } else {
                    $noResults.hide();
                }
            }

            // Live event listeners
            $searchInput.on('input', performFilter);
            $categoryFilter.on('change', performFilter);

            // Reset functionality
            $('#resetFilter').on('click', function() {
                $searchInput.val('');
                $categoryFilter.val('');
                performFilter();
            });
        });
    </script>
</body>
</html>
<?php } ?>
