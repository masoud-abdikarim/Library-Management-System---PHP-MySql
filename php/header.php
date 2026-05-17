<?php if($_SESSION['login'] != '') { ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3>NEW HARGEISA <span>LIBRARY</span></h3>
    </div>
    <nav class="sidebar-menu">
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="browse-books.php"><i class="fa fa-search"></i> Browse Books</a></li>
            <li><a href="issued-books.php"><i class="fa fa-book"></i> My Borrowed Books</a></li>
            <li><a href="borrow-history.php"><i class="fa fa-history"></i> Borrow History</a></li>
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-user"></i> My Account <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="my-profile.php">My Profile</a></li>
                    <li><a href="change-password.php">Change Password</a></li>
                </ul>
            </li>
            <li class="nav-logout"><a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
        </ul>
    </nav>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<header class="top-header">
    <button type="button" class="mobile-toggle" id="sidebarToggle" aria-label="Toggle menu"><i class="fa fa-bars"></i></button>
    <div class="page-title">NEW HARGEISA LIBRARY</div>
    <div class="user-actions">
        <span class="user-greeting hidden-xs">Welcome back!</span>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
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
<?php } else { ?>
<header class="guest-navbar">
    <div class="container">
        <div class="navbar-header">
            <a href="../index.php" class="brand-link"><h3>NEW HARGEISA <span>LIBRARY</span></h3></a>
        </div>
        <div class="nav-links">
            <a href="signup.php" class="btn btn-link">Sign Up</a>
            <a href="../index.php" class="btn btn-primary">Sign In</a>
        </div>
    </div>
</header>
<?php } ?>
