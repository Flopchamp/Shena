<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/dashboard">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/members">
                            <i class="fas fa-users"></i> Members
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/payments">
                            <i class="fas fa-money-bill-wave"></i> Payments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/claims">
                            <i class="fas fa-file-medical"></i> Claims
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/agents">
                            <i class="fas fa-user-tie"></i> Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/communications">
                            <i class="fas fa-envelope"></i> Communications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/plan-upgrades">
                            <i class="fas fa-arrow-up"></i> Plan Upgrades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/financial-dashboard">
                            <i class="fas fa-chart-line"></i> Financial Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/mpesa-config">
                            <i class="fas fa-cog"></i> M-Pesa Configuration
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">M-Pesa Configuration</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">M-Pesa Daraja API Configuration</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="/admin/mpesa-config">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

                                <div class="form-group">
                                    <label for="environment">Environment</label>
                                    <select class="form-control" id="environment" name="environment" required>
                                        <option value="sandbox" <?php echo ($data['config']['environment'] ?? 'sandbox') === 'sandbox' ? 'selected' : ''; ?>>
                                            Sandbox (Testing)
                                        </option>
                                        <option value="production" <?php echo ($data['config']['environment'] ?? '') === 'production' ? 'selected' : ''; ?>>
                                            Production (Live)
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">Use Sandbox for testing, Production for live transactions</small>
                                </div>

                                <div class="form-group">
                                    <label for="consumer_key">Consumer Key</label>
                                    <input type="text" class="form-control" id="consumer_key" name="consumer_key" 
                                           value="<?php echo htmlspecialchars($data['config']['consumer_key'] ?? ''); ?>" required>
                                    <small class="form-text text-muted">Get from Safaricom Daraja Portal</small>
                                </div>

                                <div class="form-group">
                                    <label for="consumer_secret">Consumer Secret</label>
                                    <input type="password" class="form-control" id="consumer_secret" name="consumer_secret" 
                                           value="<?php echo htmlspecialchars($data['config']['consumer_secret'] ?? ''); ?>" required>
                                    <small class="form-text text-muted">Keep this secret and secure</small>
                                </div>

                                <div class="form-group">
                                    <label for="short_code">Business Short Code</label>
                                    <input type="text" class="form-control" id="short_code" name="short_code" 
                                           value="<?php echo htmlspecialchars($data['config']['short_code'] ?? ''); ?>" required>
                                    <small class="form-text text-muted">Your M-Pesa Paybill/Till Number</small>
                                </div>

                                <div class="form-group">
                                    <label for="pass_key">Pass Key (Lipa Na M-Pesa Online)</label>
                                    <input type="password" class="form-control" id="pass_key" name="pass_key" 
                                           value="<?php echo htmlspecialchars($data['config']['pass_key'] ?? ''); ?>" required>
                                    <small class="form-text text-muted">Required for STK Push functionality</small>
                                </div>

                                <div class="form-group">
                                    <label for="callback_url">Callback URL</label>
                                    <input type="url" class="form-control" id="callback_url" name="callback_url" 
                                           value="<?php echo htmlspecialchars($data['config']['callback_url'] ?? ''); ?>" 
                                           placeholder="https://yourdomain.com/payment/callback" required>
                                    <small class="form-text text-muted">URL to receive payment notifications</small>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                               <?php echo ($data['config']['is_active'] ?? 0) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_active">
                                            Active Configuration
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable/disable M-Pesa payments</small>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Configuration
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Test Connection -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Test Connection</h5>
                        </div>
                        <div class="card-body">
                            <p>Test your M-Pesa configuration before going live.</p>
                            <form method="POST" action="/admin/mpesa-config/test">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                                <button type="submit" class="btn btn-secondary">
                                    <i class="fas fa-plug"></i> Test Connection
                                </button>
                            </form>
                            
                            <?php if (isset($data['test_result'])): ?>
                                <div class="alert alert-<?php echo $data['test_result']['success'] ? 'success' : 'danger'; ?> mt-3">
                                    <h6><?php echo $data['test_result']['success'] ? 'Connection Successful' : 'Connection Failed'; ?></h6>
                                    <p class="mb-0"><?php echo htmlspecialchars($data['test_result']['message']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Information Sidebar -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Configuration Status</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($data['config'])): ?>
                                <div class="mb-3">
                                    <i class="fas fa-circle text-<?php echo $data['config']['is_active'] ? 'success' : 'danger'; ?>"></i>
                                    <strong>Status:</strong> 
                                    <?php echo $data['config']['is_active'] ? 'Active' : 'Inactive'; ?>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-server"></i>
                                    <strong>Environment:</strong> 
                                    <span class="badge badge-<?php echo $data['config']['environment'] === 'production' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($data['config']['environment']); ?>
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <i class="fas fa-clock"></i>
                                    <strong>Last Updated:</strong><br>
                                    <small><?php echo date('M d, Y H:i', strtotime($data['config']['updated_at'])); ?></small>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No configuration found. Please set up M-Pesa credentials.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Setup Instructions</h5>
                        </div>
                        <div class="card-body">
                            <ol class="small">
                                <li>Register on <a href="https://developer.safaricom.co.ke/" target="_blank">Safaricom Daraja Portal</a></li>
                                <li>Create a new app (Sandbox or Production)</li>
                                <li>Copy Consumer Key and Consumer Secret</li>
                                <li>Get your Business Short Code</li>
                                <li>Generate Lipa Na M-Pesa Pass Key</li>
                                <li>Configure callback URL (must be HTTPS)</li>
                                <li>Save and test configuration</li>
                                <li>Switch to Production when ready</li>
                            </ol>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Security Notes</h5>
                        </div>
                        <div class="card-body">
                            <ul class="small text-muted mb-0">
                                <li>Never share your Consumer Secret</li>
                                <li>Use HTTPS for callback URLs</li>
                                <li>Test thoroughly in Sandbox</li>
                                <li>Monitor transactions regularly</li>
                                <li>Keep credentials up to date</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
