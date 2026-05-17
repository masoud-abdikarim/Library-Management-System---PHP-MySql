<?php
session_start();
error_reporting(0);
include('config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:../index.php');
} else {

    $sql = "SELECT tblbooks.BookName, tblbooks.Copies, tblbooks.IssuedCopies, tblcategory.CategoryName, tblcategory.id as catid, tblauthors.AuthorName, tblbooks.ISBNNumber, tblbooks.BookPrice, tblbooks.id as bookid 
        FROM tblbooks 
        JOIN tblcategory ON tblcategory.id=tblbooks.CatId 
        LEFT JOIN tblauthors ON tblauthors.id=tblbooks.AuthorId 
        ORDER BY tblbooks.id DESC";

    $query = $dbh->prepare($sql);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
<?php
$page_title = 'NEW HARGEISA LIBRARY | Browse Books';
?>
<?php include('head.php'); ?>
    <link href="../css/catalog.css" rel="stylesheet" />
</head>

    <body>
        <?php include('header.php'); ?>

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
                                            $categories = $query_cat->fetchAll(PDO::FETCH_OBJ);
                                            foreach ($categories as $cat) {
                                                echo "<option value='" . htmlentities($cat->id) . "'>" . htmlentities($cat->CategoryName) . "</option>";
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
                    <?php if (count($results) > 0) {
                        foreach ($results as $result) {
                            $available = $result->Copies - $result->IssuedCopies;
                    ?>
                            <div class="col-md-3 col-sm-6 col-xs-12 book-item"
                                data-title="<?php echo strtolower(htmlentities($result->BookName)); ?>"
                                data-category="<?php echo htmlentities($result->catid); ?>">
                                <div class="book-card">
                                    <div class="book-cover">
                                        <i class="fa fa-book fa-4x"></i>
                                    </div>
                                    <div class="book-info">
                                        <h5 class="book-title" title="<?php echo htmlentities($result->BookName); ?>"><?php echo htmlentities($result->BookName); ?></h5>
                                        <div class="book-meta"><strong>Book ID:</strong> BK-<?php echo htmlentities($result->bookid); ?></div>
                                        <div class="book-meta"><strong>Category:</strong> <?php echo htmlentities($result->CategoryName); ?></div>
                                        <div class="book-meta"><strong>Author:</strong> <?php echo htmlentities($result->AuthorName ? $result->AuthorName : "N/A"); ?></div>
                                        <div class="book-meta"><strong>Price:</strong> <?php echo htmlentities($result->BookPrice); ?></div>

                                        <?php if ($available > 2) { ?>
                                            <span class="book-status status-available">Available (<?php echo $available; ?>)</span>
                                        <?php } else if ($available > 0) { ?>
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

        <script src="../js/jquery-1.10.2.js"></script>
    <script src="../js/custom.js"></script>
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
                        const title = $item.attr('data-title') || '';
                        const category = $item.attr('data-category') || '';

                        const matchesSearch = searchTerm === "" || title.indexOf(searchTerm) !== -1;
                        const matchesCategory = selectedCat === "" || category === selectedCat;

                        if (matchesSearch && matchesCategory) {
                            $item.css('display', 'block');
                            visibleCount++;
                        } else {
                            $item.css('display', 'none');
                        }
                    });

                    if (visibleCount === 0) {
                        $noResults.css('display', 'block');
                    } else {
                        $noResults.css('display', 'none');
                    }
                }

                // Live event listeners - trigger instantly on input and change
                $searchInput.on('input', performFilter);
                $categoryFilter.on('change', performFilter);

                // Reset functionality
                $('#resetFilter').on('click', function() {
                    $searchInput.val('');
                    $categoryFilter.val('');
                    performFilter();
                });

                // Run once on load just in case
                performFilter();
            });
        </script>
    </body>

    </html>
<?php } ?>