        </div><!-- .dashboard-content -->
    </main><!-- .dashboard-main -->
</div><!-- .dashboard-wrapper -->

<script src="/public/js/app.js"></script>
<?php if (isset($additionalJS)): ?>
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($inlineJS)): ?>
    <script>
        <?php echo $inlineJS; ?>
    </script>
<?php endif; ?>

<style>
    /* Mobile Sidebar Toggle */
    @media (max-width: 768px) {
        [data-sidebar-toggle] {
            display: block !important;
        }
        
        .dashboard-sidebar {
            transform: translateX(-100%);
        }
        
        .dashboard-sidebar.active {
            transform: translateX(0);
        }
    }
</style>

<script>
    // Sidebar toggle for mobile
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('[data-sidebar-toggle]');
        const sidebar = document.querySelector('.dashboard-sidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        }
    });
</script>

</body>
</html>
