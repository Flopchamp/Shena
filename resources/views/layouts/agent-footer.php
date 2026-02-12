    </div>
    
    <!-- Footer -->
    <footer class="bg-white mt-5 py-4 border-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5>Shena Companion Welfare Association</h5>
                    <p class="mb-0 text-muted">Providing affordable funeral services and burial expense coverage to our members.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1"><strong>Agent Portal</strong></p>
                    <p class="mb-0 text-muted">For agent support, contact administration</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center">
                    <small class="text-muted">
                        © <?php echo date('Y'); ?> Shena Companion Welfare Association. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Sidebar Toggle Functionality
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;
            
            sidebar.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (sidebarCollapsed) {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar) {
                    sidebar.classList.add('collapsed');
                    document.body.classList.add('sidebar-collapsed');
                }
            }
        });

        // Logout Confirmation
        function confirmLogout() {
            if (window.ShenaApp && typeof ShenaApp.confirmAction === 'function') {
                ShenaApp.confirmAction(
                    'Are you sure you want to logout?',
                    function() { window.location.href = '/logout'; },
                    null,
                    { type: 'warning', title: 'Confirm Logout', confirmText: 'Logout' }
                );
                return;
            }
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/logout';
            }
        }

        // Global Search Functionality
        const searchFeatures = [
            { name: 'Dashboard', description: 'View agent dashboard and statistics', icon: 'fa-th-large', url: '/agent/dashboard' },
            { name: 'Members', description: 'View and manage registered members', icon: 'fa-users', url: '/agent/members' },
            { name: 'Register Member', description: 'Register a new member', icon: 'fa-user-plus', url: '/agent/register-member' },
            { name: 'Payouts', description: 'View commissions and request payouts', icon: 'fa-money-check-alt', url: '/agent/payouts' },
            { name: 'Resources', description: 'Training materials and documents', icon: 'fa-book', url: '/agent/resources' },
            { name: 'Support', description: 'Get help and contact admin', icon: 'fa-headset', url: '/agent/support' },
            { name: 'Profile', description: 'Manage your agent profile', icon: 'fa-user-circle', url: '/agent/profile' },
            { name: 'Settings', description: 'Account and security settings', icon: 'fa-cog', url: '/agent/profile' },
            { name: 'Change Password', description: 'Update your password', icon: 'fa-lock', url: '/agent/profile' },
        ];

        const searchInput = document.getElementById('globalSearch');
        const searchResults = document.getElementById('searchResults');

        if (searchInput && searchResults) {
            searchInput.addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase().trim();
                
                if (query.length === 0) {
                    searchResults.classList.remove('active');
                    return;
                }

                const filtered = searchFeatures.filter(feature => 
                    feature.name.toLowerCase().includes(query) || 
                    feature.description.toLowerCase().includes(query)
                );

                if (filtered.length > 0) {
                    searchResults.innerHTML = filtered.map(feature => `
                        <div class="search-result-item" onclick="window.location.href='${feature.url}'">
                            <div class="search-result-icon">
                                <i class="fas ${feature.icon}"></i>
                            </div>
                            <div class="search-result-content">
                                <h6>${feature.name}</h6>
                                <p>${feature.description}</p>
                            </div>
                        </div>
                    `).join('');
                    searchResults.classList.add('active');
                } else {
                    searchResults.innerHTML = `
                        <div class="search-no-results">
                            <i class="fas fa-search" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                            No results found for "${query}"
                        </div>
                    `;
                    searchResults.classList.add('active');
                }
            });

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.remove('active');
                }
            });

            // Close search results on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchResults.classList.remove('active');
                    searchInput.blur();
                }
            });
        }

        // Initialize DataTables if present
        $(document).ready(function() {
            if ($('#membersTable').length) {
                $('#membersTable').DataTable({
                    order: [[6, 'desc']],
                    pageLength: 25
                });
            }
            if ($('#commissionsTable').length) {
                $('#commissionsTable').DataTable({
                    order: [[0, 'desc']],
                    pageLength: 25
                });
            }
        });
    </script>
    <script src="/public/js/app.js"></script>
</body>
</html>
