
<?php if($_SESSION['alogin'] != '') { ?>
<div class="sidebar">
    <div class="sidebar-brand">
        <h3 style="color: #fff; margin: 0; font-size: 20px;">NEW HARGEISA <span style="color: #3b82f6;">LIBRARY</span></h3>
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
        NEW HARGEISA LIBRARY
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

<!-- Global Confirmation Modal -->
<div class="modal fade" id="globalConfirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document" style="margin-top: 15vh;">
    <div class="modal-content" style="border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
      <div class="modal-body text-center" style="padding: 30px 20px;">
        <div class="modal-icon" style="font-size: 40px; color: #ef4444; margin-bottom: 15px;">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <h4 id="confirmModalLabel" style="font-weight: 600; color: #1a202c; margin-top: 0;">Delete Item</h4>
        <p id="confirmModalMsg" style="color: #4b5563; font-size: 14px; margin-bottom: 25px;">Are you sure you want to delete this item? This action cannot be undone.</p>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="min-width: 90px; border-radius: 4px; font-weight: 500;">Cancel</button>
            <a href="#" id="globalConfirmBtn" class="btn btn-danger" style="min-width: 90px; border-radius: 4px; font-weight: 500; background-color: #ef4444; border-color: #ef4444;">Delete</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Override default alert
    window.originalAlert = window.alert;
    window.alert = function(msg) {
        var modal = $('#globalConfirmModal');
        modal.find('#confirmModalLabel').text('Alert');
        modal.find('#confirmModalMsg').text(msg);
        modal.find('.modal-icon').html('<i class="fa fa-info-circle"></i>').css('color', '#3b82f6');
        modal.find('#globalConfirmBtn').hide();
        modal.find('.btn-default').text('OK').removeClass('btn-default').addClass('btn-primary');
        modal.modal('show');
    };

    // Handle data-action="confirm" clicks
    document.querySelectorAll('[data-action="confirm"]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            e.preventDefault();
            var href = this.getAttribute('data-href');
            var title = this.getAttribute('data-title') || 'Delete Item';
            var msg = this.getAttribute('data-msg') || 'Are you sure you want to delete this item? This action cannot be undone.';
            
            var modal = $('#globalConfirmModal');
            modal.find('#confirmModalLabel').text(title);
            modal.find('#confirmModalMsg').text(msg);
            
            var btn = modal.find('#globalConfirmBtn');
            btn.show().attr('href', href).text('Confirm');
            
            // reset cancel button
            modal.find('button[data-dismiss="modal"]').text('Cancel').removeClass('btn-primary').addClass('btn-default');
            
            var iconClass = 'fa-exclamation-triangle';
            var color = '#ef4444';
            var btnClass = 'btn-danger';
            
            if (title.toLowerCase().includes('delete') || title.toLowerCase().includes('block')) {
                btn.text(title.toLowerCase().includes('delete') ? 'Delete' : 'Block');
            } else if (title.toLowerCase().includes('approve') || title.toLowerCase().includes('activate')) {
                iconClass = 'fa-check-circle';
                color = '#10b981';
                btnClass = 'btn-success';
                btn.text(title.toLowerCase().includes('approve') ? 'Approve' : 'Activate');
            } else if (title.toLowerCase().includes('request')) {
                iconClass = 'fa-question-circle';
                color = '#f59e0b';
                btnClass = 'btn-warning';
                btn.text('Request');
            }
            
            modal.find('.modal-icon').html('<i class="fa ' + iconClass + '"></i>').css('color', color);
            btn.removeClass('btn-danger btn-success btn-warning btn-primary').addClass('btn ' + btnClass);
            
            if (btnClass === 'btn-danger') {
                btn.css({'background-color': '#ef4444', 'border-color': '#ef4444'});
            } else {
                btn.css({'background-color': '', 'border-color': ''});
            }
            
            modal.modal('show');
        });
    });
});
</script>
<?php } ?>