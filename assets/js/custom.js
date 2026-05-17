/**
 * Library Management System - Pure JS utilities (no Bootstrap)
 */
(function () {
    'use strict';

    var backdropEl = null;

    function getBackdrop() {
        if (!backdropEl) {
            backdropEl = document.createElement('div');
            backdropEl.className = 'modal-backdrop';
            backdropEl.setAttribute('aria-hidden', 'true');
            document.body.appendChild(backdropEl);
            backdropEl.addEventListener('click', closeAllModals);
        }
        return backdropEl;
    }

    function closeAllModals() {
        document.querySelectorAll('.modal.is-open').forEach(function (modal) {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
        });
        if (backdropEl) {
            backdropEl.classList.remove('is-open');
        }
        document.body.style.overflow = '';
    }

    function openModal(modal) {
        if (!modal) return;
        getBackdrop().classList.add('is-open');
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function qs(sel, root) {
        return (root || document).querySelector(sel);
    }

    function qsa(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    window.LibraryModal = {
        open: openModal,
        close: closeAllModals,

        showConfirm: function (options) {
            var modal = document.getElementById('globalConfirmModal');
            if (!modal) return;

            var title = options.title || 'Confirm';
            var msg = options.message || 'Are you sure?';
            var href = options.href || '#';
            var confirmText = options.confirmText || 'Confirm';
            var iconClass = options.iconClass || 'fa-exclamation-triangle';
            var iconColor = options.iconColor || '#ef4444';
            var btnClass = options.btnClass || 'btn-danger';
            var alertMode = options.alertMode || false;

            var labelEl = qs('#confirmModalLabel', modal);
            var msgEl = qs('#confirmModalMsg', modal);
            var iconEl = qs('.modal-icon', modal);
            var confirmBtn = qs('#globalConfirmBtn', modal);
            var cancelBtn = qs('[data-modal-close]', modal);

            if (labelEl) labelEl.textContent = title;
            if (msgEl) msgEl.textContent = msg;
            if (iconEl) {
                iconEl.innerHTML = '<i class="fa ' + iconClass + '"></i>';
                iconEl.style.color = iconColor;
            }

            if (confirmBtn) {
                if (alertMode) {
                    confirmBtn.style.display = 'none';
                } else {
                    confirmBtn.style.display = '';
                    confirmBtn.setAttribute('href', href);
                    confirmBtn.textContent = confirmText;
                    confirmBtn.className = 'btn ' + btnClass;
                    if (btnClass === 'btn-danger') {
                        confirmBtn.style.backgroundColor = '#ef4444';
                        confirmBtn.style.borderColor = '#ef4444';
                    } else {
                        confirmBtn.style.backgroundColor = '';
                        confirmBtn.style.borderColor = '';
                    }
                }
            }

            if (cancelBtn) {
                if (alertMode) {
                    cancelBtn.textContent = 'OK';
                    cancelBtn.className = 'btn btn-primary';
                } else {
                    cancelBtn.textContent = 'Cancel';
                    cancelBtn.className = 'btn btn-default';
                }
            }

            openModal(modal);
        },

        init: function () {
            document.addEventListener('click', function (e) {
                var closeTrigger = e.target.closest('[data-modal-close]');
                if (closeTrigger) {
                    e.preventDefault();
                    closeAllModals();
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeAllModals();
            });

            qsa('[data-action="confirm"]').forEach(function (el) {
                el.addEventListener('click', function (e) {
                    e.preventDefault();
                    var href = this.getAttribute('data-href');
                    var title = this.getAttribute('data-title') || 'Delete Item';
                    var msg = this.getAttribute('data-msg') || 'Are you sure you want to delete this item? This action cannot be undone.';

                    var iconClass = 'fa-exclamation-triangle';
                    var iconColor = '#ef4444';
                    var btnClass = 'btn-danger';
                    var confirmText = 'Confirm';

                    var t = title.toLowerCase();
                    if (t.indexOf('delete') !== -1) {
                        confirmText = 'Delete';
                    } else if (t.indexOf('block') !== -1) {
                        confirmText = 'Block';
                    } else if (t.indexOf('approve') !== -1) {
                        iconClass = 'fa-check-circle';
                        iconColor = '#10b981';
                        btnClass = 'btn-success';
                        confirmText = 'Approve';
                    } else if (t.indexOf('activate') !== -1) {
                        iconClass = 'fa-check-circle';
                        iconColor = '#10b981';
                        btnClass = 'btn-success';
                        confirmText = 'Activate';
                    } else if (t.indexOf('request') !== -1) {
                        iconClass = 'fa-question-circle';
                        iconColor = '#f59e0b';
                        btnClass = 'btn-warning';
                        confirmText = 'Request';
                    }

                    LibraryModal.showConfirm({
                        title: title,
                        message: msg,
                        href: href,
                        confirmText: confirmText,
                        iconClass: iconClass,
                        iconColor: iconColor,
                        btnClass: btnClass
                    });
                });
            });

            window.originalAlert = window.alert;
            window.alert = function (msg) {
                LibraryModal.showConfirm({
                    title: 'Alert',
                    message: msg,
                    iconClass: 'fa-info-circle',
                    iconColor: '#3b82f6',
                    alertMode: true
                });
            };
        }
    };

    function initCarousel() {
        var carousel = document.getElementById('carousel-example');
        if (!carousel) return;

        var items = carousel.querySelectorAll('.carousel-inner .item');
        var indicators = carousel.querySelectorAll('.carousel-indicators li');
        var prevBtn = carousel.querySelector('[data-slide="prev"]');
        var nextBtn = carousel.querySelector('[data-slide="next"]');
        var current = 0;
        var timer;

        function goTo(index) {
            if (!items.length) return;
            current = (index + items.length) % items.length;
            items.forEach(function (item, i) {
                item.classList.toggle('active', i === current);
            });
            indicators.forEach(function (dot, i) {
                dot.classList.toggle('active', i === current);
            });
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function startAuto() {
            stopAuto();
            timer = setInterval(next, 3000);
        }

        function stopAuto() {
            if (timer) clearInterval(timer);
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function (e) {
                e.preventDefault();
                prev();
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function (e) {
                e.preventDefault();
                next();
            });
        }

        indicators.forEach(function (dot, i) {
            dot.addEventListener('click', function (e) {
                e.preventDefault();
                goTo(i);
            });
        });

        carousel.addEventListener('mouseenter', stopAuto);
        carousel.addEventListener('mouseleave', startAuto);
        startAuto();
    }

    function initDataTables() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.dataTable) return;

        jQuery('#dataTables-example').each(function () {
            if (jQuery.fn.dataTable.isDataTable(this)) return;
            jQuery(this).dataTable({
                bLengthChange: false,
                lengthChange: false,
                pagingType: 'simple_numbers'
            });
        });
    }

    function initSearchableSelects() {
        var selects = document.querySelectorAll('.disabled-custom-select');
        selects.forEach(function(select) {
            if (select.dataset.customSelectInitialized) return;
            select.dataset.customSelectInitialized = "true";

            select.style.display = 'none';

            var wrapper = document.createElement('div');
            wrapper.className = 'custom-select-wrapper';
            select.parentNode.insertBefore(wrapper, select);
            wrapper.appendChild(select);

            var trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            var triggerText = document.createElement('span');
            var selectedOption = select.options[select.selectedIndex];
            triggerText.textContent = selectedOption ? selectedOption.textContent : 'Select an option...';
            trigger.appendChild(triggerText);
            
            var triggerIcon = document.createElement('i');
            triggerIcon.className = 'fa fa-angle-down';
            trigger.appendChild(triggerIcon);

            wrapper.appendChild(trigger);

            var container = document.createElement('div');
            container.className = 'custom-options-container';

            var searchContainer = document.createElement('div');
            searchContainer.className = 'custom-select-search-container';
            var searchIcon = document.createElement('i');
            searchIcon.className = 'fa fa-search';
            searchContainer.appendChild(searchIcon);
            var searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.placeholder = 'Type name, email or ID to search...';
            searchContainer.appendChild(searchInput);
            container.appendChild(searchContainer);

            var optionsList = document.createElement('ul');
            optionsList.className = 'custom-options';
            container.appendChild(optionsList);

            wrapper.appendChild(container);

            function renderOptions(filterText) {
                optionsList.innerHTML = '';
                var filter = (filterText || '').toLowerCase();
                var count = 0;
                
                var frag = document.createDocumentFragment();

                Array.from(select.options).forEach(function(option, index) {
                    if (filter && option.textContent.toLowerCase().indexOf(filter) === -1) return;
                    if (option.value === "" && !filter && select.required && option.disabled) return;
                    
                    var li = document.createElement('li');
                    li.className = 'custom-option';
                    if (option.selected) li.classList.add('is-selected');
                    
                    if (option.dataset.name) {
                        li.innerHTML = '<div class="custom-option-layout">' +
                            '<div class="col-name"><i class="fa fa-user-o"></i> ' + option.dataset.name + '</div>' +
                            '<div class="col-id">ID: ' + option.dataset.id + '</div>' +
                            '<div class="col-email">' + option.dataset.email + '</div>' +
                        '</div>';
                    } else {
                        li.textContent = option.textContent;
                    }
                    
                    li.dataset.value = option.value;
                    li.dataset.index = index;

                    li.addEventListener('click', function(e) {
                        e.stopPropagation();
                        select.selectedIndex = index;
                        triggerText.textContent = option.textContent;
                        closeDropdown();
                        
                        var event = document.createEvent('HTMLEvents');
                        event.initEvent('change', true, false);
                        select.dispatchEvent(event);
                    });

                    frag.appendChild(li);
                    count++;
                });

                if (count === 0) {
                    var li = document.createElement('li');
                    li.className = 'custom-option';
                    li.textContent = 'No results found';
                    li.style.pointerEvents = 'none';
                    li.style.color = '#94a3b8';
                    frag.appendChild(li);
                }

                optionsList.appendChild(frag);
            }

            function openDropdown() {
                document.querySelectorAll('.custom-options-container.is-open').forEach(function(el) {
                    if (el !== container) {
                        el.classList.remove('is-open');
                        if (el.previousElementSibling) el.previousElementSibling.classList.remove('is-open');
                    }
                });
                container.classList.add('is-open');
                trigger.classList.add('is-open');
                renderOptions('');
                searchInput.value = '';
                searchInput.focus();
                
                var selected = optionsList.querySelector('.is-selected');
                if (selected) {
                    optionsList.scrollTop = selected.offsetTop - optionsList.offsetTop;
                }
            }

            function closeDropdown() {
                container.classList.remove('is-open');
                trigger.classList.remove('is-open');
            }

            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                if (container.classList.contains('is-open')) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            });

            searchInput.addEventListener('input', function() {
                renderOptions(this.value);
            });

            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });

            searchInput.addEventListener('keydown', function(e) {
                var focused = optionsList.querySelector('.is-focused');
                var items = optionsList.querySelectorAll('.custom-option:not([style*="pointer-events: none"])');
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!items.length) return;
                    if (!focused) {
                        items[0].classList.add('is-focused');
                        items[0].scrollIntoView({block: 'nearest'});
                    } else {
                        var next = focused.nextElementSibling;
                        while(next && next.style.pointerEvents === 'none') next = next.nextElementSibling;
                        if (next) {
                            focused.classList.remove('is-focused');
                            next.classList.add('is-focused');
                            next.scrollIntoView({block: 'nearest'});
                        }
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (!items.length) return;
                    if (focused) {
                        var prev = focused.previousElementSibling;
                        while(prev && prev.style.pointerEvents === 'none') prev = prev.previousElementSibling;
                        if (prev) {
                            focused.classList.remove('is-focused');
                            prev.classList.add('is-focused');
                            prev.scrollIntoView({block: 'nearest'});
                        }
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (focused) {
                        focused.click();
                    } else if (items.length === 1) {
                        items[0].click();
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    closeDropdown();
                    trigger.focus();
                }
            });

            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    closeDropdown();
                }
            });
            
            select.addEventListener('change', function() {
                var selectedOption = select.options[select.selectedIndex];
                if (selectedOption) {
                    triggerText.textContent = selectedOption.textContent;
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        LibraryModal.init();
        initCarousel();
        initDataTables();
        initSearchableSelects();
    });
})();
