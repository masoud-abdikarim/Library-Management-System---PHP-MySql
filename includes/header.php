<?php if($_SESSION['login'] != '') { ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3 style="color: #fff; margin: 0; font-size: 20px;">NEW HARGEISA <span style="color: #3b82f6;">LIBRARY</span></h3>
    </div>
    <div class="sidebar-menu">
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> DASHBOARD</a></li>
            <li><a href="browse-books.php"><i class="fa fa-search"></i> BROWSE BOOKS</a></li>
            <li><a href="issued-books.php"><i class="fa fa-book"></i> MY BORROWED BOOKS</a></li>
            <li><a href="borrow-history.php"><i class="fa fa-history"></i> BORROW HISTORY</a></li>
            
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-user"></i> MY ACCOUNT <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="my-profile.php">My Profile</a></li>
                    <li><a href="change-password.php">Change Password</a></li>
                </ul>
            </li>

            <li><a href="logout.php" style="color: #ef4444;"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
        </ul>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="top-header">
    <div class="mobile-toggle" id="sidebarToggle">
        <i class="fa fa-bars"></i>
    </div>
    <div class="page-title" style="font-weight: 600; font-size: 18px; color: #1a202c;">
        NEW HARGEISA LIBRARY
    </div>
    <div class="user-actions">
        <span class="hidden-xs" style="font-weight: 500; margin-right: 15px;">Welcome Back!</span>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Sidebar Submenus
        var dropdowns = document.querySelectorAll('.has-submenu > a');
        dropdowns.forEach(function(dropdown) {
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                var parent = this.parentElement;
                parent.classList.toggle('open');
            });
        });

        // Mobile Toggle
        var sidebarToggle = document.getElementById('sidebarToggle');
        var sidebarOverlay = document.getElementById('sidebarOverlay');
        if(sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
            });
        }
        if(sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.remove('active');
            });
        }

        // Auto-active menu based on current URL
        var currentPath = window.location.pathname.split('/').pop();
        if(!currentPath) currentPath = 'dashboard.php';
        var menuLinks = document.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach(function(link) {
            var href = link.getAttribute('href');
            if (href === currentPath) {
                link.classList.add('menu-top-active');
                var parentSub = link.closest('.dropdown-menu');
                if (parentSub) {
                    parentSub.parentElement.classList.add('open');
                }
            }
        });
    });
</script>
<?php } else { ?>
    <!-- Header for non-logged in users (Login/Signup) -->
    <div class="navbar navbar-inverse set-radius-zero" style="min-height: 70px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; height: 70px;">
            <div class="navbar-header">
                <a href="index.php" style="text-decoration: none;">
                    <h3 style="color: #1a202c; margin: 0; font-weight: 700;">NEW HARGEISA <span style="color: #3b82f6;">LIBRARY</span></h3>
                </a>
            </div>
            <div class="nav-links">
                <a href="signup.php" class="btn btn-link" style="color: #4b5563; text-decoration: none; font-weight: 500;">Sign Up</a>
                <a href="index.php" class="btn btn-primary" style="margin-left: 10px;">Sign In</a>
            </div>
        </div>
    </div>
<?php } ?>