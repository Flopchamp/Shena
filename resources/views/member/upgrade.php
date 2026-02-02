<?php
$page = 'upgrade';
include __DIR__ . '/../layouts/member-header.php';

$memberData = $member ?? [];
$calculation = $calculation ?? [];
$pendingUpgrades = $pendingUpgrades ?? [];
$upgradeHistory = $upgradeHistory ?? [];
?>

<style>
main {
    padding: 0 !important;
    margin: 0 !important;
}

.upgrade-container {
    padding: 30px;
    background: #F8F9FA;
    min-height: calc(100vh - 80px);
    max-width: 100%;
    overflow-x: hidden;
}

.page-header {
    margin-bottom: 32px;
}

.page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 4px 0;
}

.page-header p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.packages-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 32px;
}

@media (max-width: 968px) {
    .packages-grid {
        grid-template-columns: 1fr;
    }
}

.package-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: relative;
    transition: transform 0.3s, box-shadow 0.3s;
}

.package-card.current {
    border: 2px solid #E5E7EB;
}

.package-card.premium {
    border: 2px solid #7F20B0;
    background: linear-gradient(135deg, rgba(127, 32, 176, 0.03) 0%, rgba(94, 43, 122, 0.05) 100%);
}

.package-card.premium:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(127, 32, 176, 0.2);
}

.package-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.package-badge.current-badge {
    background: #E5E7EB;
    color: #4B5563;
}

.package-badge.recommended {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.package-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
    font-size: 28px;
}

.package-icon.basic {
    background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
    color: white;
}

.package-icon.premium {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
}

.package-name {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 8px 0;
    text-transform: uppercase;
}

.package-price {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 700;
    color: #7F20B0;
    margin: 0 0 4px 0;
}

.package-price span {
    font-size: 16px;
    color: #6B7280;
    font-family: 'Manrope', sans-serif;
    font-weight: 500;
}

.package-description {
    font-size: 14px;
    color: #6B7280;
    margin: 0 0 24px 0;
}

.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0 0 24px 0;
}

.benefits-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 14px;
    color: #4B5563;
}

.benefits-list li i {
    color: #10B981;
    font-size: 16px;
    margin-top: 2px;
}

.upgrade-btn {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 14px;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.upgrade-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(127, 32, 176, 0.4);
}

.upgrade-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.calculation-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.calculation-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: #1F2937;
    margin: 0 0 24px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.calculation-card h3 i {
    color: #7F20B0;
}

.cost-breakdown {
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.cost-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #E5E7EB;
}

.cost-row:last-child {
    border-bottom: none;
}

.cost-row.total {
    background: linear-gradient(135deg, #7F20B0 0%, #5E2B7A 100%);
    color: white;
}

.cost-label {
    font-size: 14px;
    font-weight: 600;
    color: #4B5563;
}

.cost-row.total .cost-label {
    color: white;
    font-size: 16px;
}

.cost-value {
    font-size: 18px;
    font-weight: 700;
    color: #1F2937;
}

.cost-row.total .cost-value {
    color: white;
    font-size: 24px;
}

.info-alert {
    background: #EFF6FF;
    border-left: 4px solid #3B82F6;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
    display: flex;
    gap: 12px;
}

.info-alert i {
    color: #3B82F6;
    font-size: 20px;
    flex-shrink: 0;
}

.info-alert-content {
    flex: 1;
}

.info-alert-content h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0 0 4px 0;
}

.info-alert-content p {
    font-size: 13px;
    color: #1E40AF;
    margin: 0;
    line-height: 1.6;
}

.upgrade-form {
    margin-top: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #7F20B0;
    box-shadow: 0 0 0 3px rgba(127, 32, 176, 0.1);
}

.form-text {
    font-size: 12px;
    color: #6B7280;
    margin-top: 6px;
    display: block;
}

.form-check {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 20px;
}

.form-check-input {
    margin-top: 3px;
    cursor: pointer;
}

.form-check-label {
    font-size: 13px;
    color: #4B5563;
    line-height: 1.6;
}

.pending-alert {
    background: #FEF3C7;
    border-left: 4px solid #F59E0B;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 24px;
}

.pending-alert h5 {
    font-size: 16px;
    font-weight: 700;
    color: #92400E;
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.pending-info {
    font-size: 13px;
    color: #78350F;
    margin-bottom: 8px;
}

.cancel-btn {
    background: #EF4444;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 12px;
    transition: all 0.2s;
}

.cancel-btn:hover {
    background: #DC2626;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table thead th {
    background: #F9FAFB;
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #E5E7EB;
}

.history-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
    font-size: 14px;
    color: #4B5563;
}

.history-table tbody tr:last-child td {
    border-bottom: none;
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.modal-overlay.show {
    display: flex;
}

.modal-content-custom {
    background: white;
    border-radius: 16px;
    padding: 40px;
    text-align: center;
    max-width: 400px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.spinner {
    width: 60px;
    height: 60px;
    border: 4px solid #E5E7EB;
    border-top-color: #7F20B0;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<div class="upgrade-container">
    <div class="page-header">
        <h1>Upgrade Your Plan</h1>
        <p>Unlock premium benefits and enhanced coverage for your family</p>
    </div>

    <div class="packages-grid">
        <!-- Current Package -->
        <div class="package-card current">
            <span class="package-badge current-badge">CURRENT</span>
            <div class="package-icon basic">
                <i class="fas fa-user"></i>
            </div>
            <div class="package-name"><?php echo strtoupper($memberData['package'] ?? 'Basic'); ?></div>
            <div class="package-price">
                KES <?php echo number_format($calculation['current_monthly_fee'] ?? 500, 2); ?>
                <span>/month</span>
            </div>
            <p class="package-description">Your current membership plan with essential coverage</p>
            
            <ul class="benefits-list">
                <li><i class="fas fa-check-circle"></i>Funeral Service Coverage</li>
                <li><i class="fas fa-check-circle"></i>Basic Burial Expenses</li>
                <li><i class="fas fa-check-circle"></i>Up to 3 Dependents</li>
                <li><i class="fas fa-check-circle"></i>Standard Support</li>
            </ul>
        </div>

        <!-- Premium Package -->
        <div class="package-card premium">
            <span class="package-badge recommended">RECOMMENDED</span>
            <div class="package-icon premium">
                <i class="fas fa-crown"></i>
            </div>
            <div class="package-name">Premium</div>
            <div class="package-price">
                KES <?php echo number_format($calculation['new_monthly_fee'] ?? 1000, 2); ?>
                <span>/month</span>
            </div>
            <p class="package-description">Enhanced protection with comprehensive benefits</p>
            
            <ul class="benefits-list">
                <li><i class="fas fa-check-circle"></i>All Basic Benefits</li>
                <li><i class="fas fa-check-circle"></i>Extended Burial Coverage</li>
                <li><i class="fas fa-check-circle"></i>Up to 5 Dependents</li>
                <li><i class="fas fa-check-circle"></i>Priority Processing</li>
                <li><i class="fas fa-check-circle"></i>24/7 Support Hotline</li>
                <li><i class="fas fa-check-circle"></i>Enhanced Claim Benefits</li>
            </ul>
            
            <?php if (empty($pendingUpgrades)): ?>
                <button class="upgrade-btn" onclick="document.getElementById('upgradeForm').scrollIntoView({behavior: 'smooth'})">
                    <i class="fas fa-arrow-up"></i>
                    Upgrade Now
                </button>
            <?php else: ?>
                <button class="upgrade-btn" disabled>
                    <i class="fas fa-clock"></i>
                    Upgrade Pending
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($pendingUpgrades)): ?>
        <!-- Pending Upgrade Alert -->
        <div class="pending-alert">
            <h5><i class="fas fa-clock"></i>Pending Upgrade Request</h5>
            <?php foreach ($pendingUpgrades as $pending): ?>
                <p class="pending-info"><strong>Status:</strong> <?php echo ucwords(str_replace('_', ' ', $pending['status'])); ?></p>
                <p class="pending-info"><strong>Amount:</strong> KES <?php echo number_format($pending['prorated_amount'], 2); ?></p>
                <p class="pending-info"><strong>Requested:</strong> <?php echo date('M d, Y H:i', strtotime($pending['requested_at'])); ?></p>
                <?php if ($pending['status'] === 'pending'): ?>
                    <button class="cancel-btn" onclick="cancelUpgrade(<?php echo $pending['id']; ?>)">
                        <i class="fas fa-times"></i> Cancel Request
                    </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Upgrade Cost Breakdown -->
        <div class="calculation-card" id="upgradeForm">
            <h3><i class="fas fa-calculator"></i>Upgrade Cost Breakdown</h3>
            
            <div class="cost-breakdown">
                <div class="cost-row">
                    <span class="cost-label">Current Monthly Fee</span>
                    <span class="cost-value">KES <?php echo number_format($calculation['current_monthly_fee'] ?? 500, 2); ?></span>
                </div>
                <div class="cost-row">
                    <span class="cost-label">Premium Monthly Fee</span>
                    <span class="cost-value">KES <?php echo number_format($calculation['new_monthly_fee'] ?? 1000, 2); ?></span>
                </div>
                <div class="cost-row">
                    <span class="cost-label">Monthly Difference</span>
                    <span class="cost-value">+KES <?php echo number_format(($calculation['new_monthly_fee'] ?? 1000) - ($calculation['current_monthly_fee'] ?? 500), 2); ?></span>
                </div>
                <div class="cost-row">
                    <span class="cost-label">Days Remaining in <?php echo date('F'); ?></span>
                    <span class="cost-value"><?php echo $calculation['days_remaining'] ?? 15; ?> / <?php echo $calculation['total_days_in_month'] ?? 30; ?> days</span>
                </div>
                <div class="cost-row total">
                    <span class="cost-label">Pay Today (Prorated)</span>
                    <span class="cost-value">KES <?php echo number_format($calculation['prorated_amount'] ?? 250, 2); ?></span>
                </div>
            </div>
            
            <div class="info-alert">
                <i class="fas fa-info-circle"></i>
                <div class="info-alert-content">
                    <h6>How It Works</h6>
                    <p>You only pay for the remaining days of <?php echo date('F'); ?>. Starting <?php echo date('F 1, Y', strtotime('first day of next month')); ?>, your regular monthly contribution will be KES <?php echo number_format($calculation['new_monthly_fee'] ?? 1000, 2); ?>.</p>
                </div>
            </div>
            
            <form class="upgrade-form" id="upgradeFormSubmit">
                <div class="form-group">
                    <label class="form-label">M-Pesa Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                           value="<?php echo htmlspecialchars($memberData['phone'] ?? ''); ?>" 
                           placeholder="0712345678 or 254712345678" required>
                    <small class="form-text">You will receive an M-Pesa prompt to complete the payment</small>
                </div>
                
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="agree_terms" required>
                    <label class="form-check-label" for="agree_terms">
                        I understand that by upgrading, my monthly contribution will increase to 
                        KES <?php echo number_format($calculation['new_monthly_fee'] ?? 1000, 2); ?> starting next month.
                    </label>
                </div>
                
                <button type="submit" class="upgrade-btn" id="upgradeBtn">
                    <i class="fas fa-arrow-up"></i>
                    Upgrade to Premium (KES <?php echo number_format($calculation['prorated_amount'] ?? 250, 2); ?>)
                </button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Upgrade History -->
    <?php if (!empty($upgradeHistory)): ?>
        <div class="calculation-card">
            <h3><i class="fas fa-history"></i>Upgrade History</h3>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>From Package</th>
                        <th>To Package</th>
                        <th>Amount Paid</th>
                        <th>Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($upgradeHistory as $history): ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($history['upgraded_at'])); ?></td>
                        <td><?php echo ucfirst($history['from_package']); ?></td>
                        <td><?php echo ucfirst($history['to_package']); ?></td>
                        <td>KES <?php echo number_format($history['amount_paid'], 2); ?></td>
                        <td><?php echo strtoupper($history['payment_method']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Processing Modal -->
<div class="modal-overlay" id="processingModal">
    <div class="modal-content-custom">
        <div class="spinner"></div>
        <h5 style="margin: 0 0 12px 0; font-size: 18px; color: #1F2937;">Processing Upgrade</h5>
        <p style="margin: 0; font-size: 14px; color: #6B7280;">Please check your phone for the M-Pesa prompt...</p>
        <p id="processingStatus" style="margin: 12px 0 0 0; font-size: 13px; color: #7F20B0;"></p>
    </div>
</div>

<script>
document.getElementById('upgradeFormSubmit')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (!document.getElementById('agree_terms').checked) {
        alert('Please agree to the terms before proceeding');
        return;
    }
    
    const phone = document.getElementById('phone_number').value;
    const upgradeBtn = document.getElementById('upgradeBtn');
    
    upgradeBtn.disabled = true;
    upgradeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    
    try {
        const response = await fetch('/member/upgrade/request', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ phone_number: phone })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('processingModal').classList.add('show');
            document.getElementById('processingStatus').textContent = 'Request ID: ' + data.upgrade_request_id;
            
            const checkInterval = setInterval(() => {
                checkUpgradeStatus(data.upgrade_request_id, checkInterval);
            }, 5000);
            
            setTimeout(() => {
                clearInterval(checkInterval);
                document.getElementById('processingModal').classList.remove('show');
                alert('Payment timeout. Please check your payment status.');
                location.reload();
            }, 120000);
        } else {
            alert(data.message || 'Failed to initiate upgrade');
            upgradeBtn.disabled = false;
            upgradeBtn.innerHTML = '<i class="fas fa-arrow-up"></i> Upgrade to Premium';
        }
    } catch (error) {
        alert('Failed to process upgrade request');
        upgradeBtn.disabled = false;
        upgradeBtn.innerHTML = '<i class="fas fa-arrow-up"></i> Upgrade to Premium';
    }
});

async function checkUpgradeStatus(upgradeRequestId, interval) {
    try {
        const response = await fetch('/member/upgrade/status?upgrade_request_id=' + upgradeRequestId);
        const data = await response.json();
        
        if (data.success) {
            if (data.status === 'completed') {
                clearInterval(interval);
                document.getElementById('processingModal').classList.remove('show');
                alert('Upgrade completed successfully! Redirecting...');
                setTimeout(() => { window.location.href = '/member/dashboard'; }, 2000);
            } else if (data.status === 'failed') {
                clearInterval(interval);
                document.getElementById('processingModal').classList.remove('show');
                alert('Payment failed. Please try again.');
                location.reload();
            }
        }
    } catch (error) {
        console.error('Status check error:', error);
    }
}

function cancelUpgrade(upgradeRequestId) {
    if (!confirm('Are you sure you want to cancel this upgrade request?')) return;
    
    fetch('/member/upgrade/cancel', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ upgrade_request_id: upgradeRequestId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Upgrade request cancelled');
            location.reload();
        } else {
            alert(data.error || 'Failed to cancel upgrade');
        }
    })
    .catch(() => alert('Failed to cancel upgrade'));
}
</script>

<?php include __DIR__ . '/../layouts/member-footer.php'; ?>
