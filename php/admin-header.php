
<?php if($_SESSION['alogin'] != '') { ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3>NEW HARGEISA <span>LIBRARY</span></h3>
    </div>
    <nav class="sidebar-menu">
        <ul>
            <li><a href="admin-dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-list"></i> Categories <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="add-category.php">Add Category</a></li>
                    <li><a href="manage-categories.php">Manage Categories</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-book"></i> Books <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="add-book.php">Add Book</a></li>
                    <li><a href="manage-books.php">Manage Books</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-exchange"></i> Borrow Management <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="issue-book.php">Issue Book</a></li>
                    <li><a href="manage-issued-books.php">Borrowed Books</a></li>
                    <li><a href="pending-returns.php">Pending Returns</a></li>
                    <li><a href="return-history.php">Return History</a></li>
                </ul>
            </li>
            <li><a href="reg-students.php"><i class="fa fa-users"></i> Users</a></li>
            <li><a href="report.php"><i class="fa fa-file-text"></i> Reports</a></li>
            <li><a href="admin-change-password.php"><i class="fa fa-lock"></i> Change Password</a></li>
            <li class="nav-logout"><a href="admin-logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </nav>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<header class="top-header">
    <button type="button" class="mobile-toggle" id="sidebarToggle" aria-label="Toggle menu"><i class="fa fa-bars"></i></button>
    <div class="page-title">Admin Panel</div>
    <div class="user-actions">
        <span class="user-greeting hidden-xs">Admin Dashboard</span>
        <a href="admin-logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.has-submenu > a').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            this.parentElement.classList.toggle('open');
        });
    });
    var toggle = document.getElementById('sidebarToggle');
    var overlay = document.getElementById('sidebarOverlay');
    var sidebar = document.querySelector('.sidebar');
    if (toggle && sidebar) toggle.addEventListener('click', function() { sidebar.classList.toggle('active'); });
    if (overlay && sidebar) overlay.addEventListener('click', function() { sidebar.classList.remove('active'); });
    var path = window.location.pathname.split('/').pop() || 'dashboard.php';
    document.querySelectorAll('.sidebar-menu a[href]').forEach(function(link) {
        if (link.getAttribute('href') === path) {
            link.classList.add('menu-top-active');
            var sub = link.closest('.dropdown-menu');
            if (sub) sub.parentElement.classList.add('open');
        }
    });
});
</script>

<div class="modal" id="globalConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="modal-icon"><i class="fa fa-exclamation-triangle"></i></div>
        <h4 id="confirmModalLabel">Delete Item</h4>
        <p id="confirmModalMsg">Are you sure you want to delete this item? This action cannot be undone.</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-default" data-modal-close>Cancel</button>
            <a href="#" id="globalConfirmBtn" class="btn btn-danger">Delete</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
