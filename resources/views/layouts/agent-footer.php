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
