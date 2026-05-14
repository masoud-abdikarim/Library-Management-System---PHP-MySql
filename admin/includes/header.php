
<?php if($_SESSION['alogin'] != '') { ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3 style="color: #fff; margin: 0; font-size: 20px;">LIBRARY <span style="color: #3b82f6;">ERP</span></h3>
    </div>
    <div class="sidebar-menu">
        <ul>
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> DASHBOARD</a></li>
            
            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-list"></i> CATEGORIES <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="add-category.php">Add Category</a></li>
                    <li><a href="manage-categories.php">Manage Categories</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-book"></i> BOOKS <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="add-book.php">Add Book</a></li>
                    <li><a href="manage-books.php">Manage Books</a></li>
                </ul>
            </li>

            <li class="has-submenu">
                <a href="javascript:void(0);" class="dropdown-toggle"><i class="fa fa-exchange"></i> BORROW MANAGEMENT <i class="fa fa-angle-down pull-right"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="issue-book.php">Issue New Book</a></li>
                    <li><a href="manage-issued-books.php">Borrowed Books</a></li>
                    <li><a href="pending-returns.php">Pending Returns</a></li>
                    <li><a href="return-history.php">Return History</a></li>
                </ul>
            </li>

            <li><a href="reg-students.php"><i class="fa fa-users"></i> LIST OF USERS</a></li>
            <li><a href="report.php"><i class="fa fa-file-text"></i> REPORTS</a></li>
            <li><a href="change-password.php"><i class="fa fa-lock"></i> CHANGE PASSWORD</a></li>
            <li><a href="logout.php" style="color: #ef4444;"><i class="fa fa-sign-out"></i> LOGOUT</a></li>
        </ul>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="top-header">
    <div class="mobile-toggle" id="sidebarToggle">
        <i class="fa fa-bars"></i>
    </div>
    <div class="page-title">
        Online Library Management System
    </div>
    <div class="user-actions">
        <span class="hidden-xs" style="font-weight: 500;">Admin Dashboard</span>
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
<?php } ?>