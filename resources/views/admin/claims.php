<?php include_once __DIR__ . '/../layouts/admin-header.php'; ?>

<style>
    /* Page Header */
    .page-header {
        margin-bottom: 24px;
    }

    .page-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
        margin: 0 0 4px 0;
    }

    .page-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin: 0;
    }

    /* Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #E5E7EB;
        transition: all 0.2s;
    }

    .stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .stat-icon.blue {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .stat-icon.orange {
        background: #FED7AA;
        color: #F97316;
    }

    .stat-icon.green {
        background: #D1FAE5;
        color: #10B981;
    }

    .stat-icon.red {
        background: #FEE2E2;
        color: #EF4444;
    }

    .stat-label {
        font-size: 11px;
        color: #9CA3AF;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1F2937;
    }

    /* Main Content Layout */
    .content-layout {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 24px;
        margin-bottom: 30px;
    }

    /* Active Claims */
    .claims-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .claims-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 20px;
    }

    .claim-item {
        padding: 16px;
        background: #F9FAFB;
        border-radius: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .claim-item:hover {
        background: #F3F4F6;
    }

    .claim-item.active {
        background: white;
        border: 2px solid #8B5CF6;
    }

    .claim-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .claim-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .claim-badge.doc-review {
        background: #DBEAFE;
        color: #3B82F6;
    }

    .claim-badge.logistics {
        background: #FED7AA;
        color: #F97316;
    }

    .claim-badge.settled {
        background: #D1FAE5;
        color: #10B981;
    }

    .claim-number {
        font-size: 11px;
        color: #9CA3AF;
    }

    .claim-name {
        font-size: 15px;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 4px;
    }

    .claim-beneficiary {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 8px;
    }

    .claim-progress {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #8B5CF6;
    }

    .claim-progress i {
        font-size: 14px;
    }

    .claim-logistics-info {
        font-size: 12px;
        color: #6B7280;
        margin-bottom: 4px;
    }

    .claim-pickup {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #F97316;
    }

    .claim-settled-info {
        font-size: 12px;
        color: #10B981;
    }

    /* Verification Panel */
    .verification-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #E5E7EB;
    }

    .verification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .verification-title {
        font-size: 22px;
        font-weight: 700;
        color: #1F2937;
    }

    .verification-subtitle {
        font-size: 13px;
        color: #9CA3AF;
        margin-bottom: 24px;
    }

    .claim-amount-label {
        font-size: 11px;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .claim-amount-value {
        font-size: 18px;
        font-weight: 700;
        color: #8B5CF6;
    }

    /* Document Upload Grid */
    .document-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 30px;
    }

    .document-slot {
        aspect-ratio: 1;
        border: 2px dashed #E5E7EB;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #F9FAFB;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        background-image: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(139, 92, 246, 0.05) 10px,
            rgba(139, 92, 246, 0.05) 20px
        );
    }

    .document-slot:hover {
        border-color: #8B5CF6;
        background: rgba(139, 92, 246, 0.02);
    }

    .document-slot.verified {
        border-color: #10B981;
        border-style: solid;
        background: #F0FDF4;
        background-image: none;
    }

    .document-slot.review {
        border-color: #8B5CF6;
        border-style: solid;
        background: #FAF5FF;
        background-image: none;
    }

    .document-icon {
        font-size: 36px;
        color: #D1D5DB;
        margin-bottom: 12px;
    }

    .document-slot.verified .document-icon {
        color: #10B981;
    }

    .document-slot.review .document-icon {
        color: #8B5CF6;
    }

    .document-label {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 8px;
        text-align: center;
    }

    .document-status {
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .document-status.verified {
        background: #10B981;
        color: white;
    }

    .document-status.review {
        background: #8B5CF6;
        color: white;
    }

    /* Logistics Section */
    .logistics-section {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .logistics-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .logistics-icon {
        width: 40px;
        height: 40px;
        background: #8B5CF6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .logistics-title {
        font-size: 18px;
        font-weight: 700;
        color: #1F2937;
    }

    .form-section {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }

    .form-select {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        font-size: 14px;
        color: #1F2937;
        background: white;
        cursor: pointer;
    }

    .form-select:focus {
        outline: none;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    /* Checklist */
    .checklist-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .checklist-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checklist-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #8B5CF6;
    }

    .checklist-label {
        font-size: 13px;
        color: #1F2937;
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .content-layout {
            grid-template-columns: 1fr;
        }

        .document-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }

        .checklist-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">Claims & Logistics Hub</h1>
    <p class="page-subtitle">Verification and funeral coordination management</p>
</div>

<!-- Statistics Cards -->
<div class="stats-row">
    <!-- Doc Review -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="fas fa-file-alt"></i>
            </div>
        </div>
        <div class="stat-label">Doc Review</div>
        <div class="stat-value">12 Claims</div>
    </div>

    <!-- In Logistics -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="fas fa-truck"></i>
            </div>
        </div>
        <div class="stat-label">In Logistics</div>
        <div class="stat-value">08 Active</div>
    </div>

    <!-- Settled MTD -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-label">Settled (MTD)</div>
        <div class="stat-value">45 Total</div>
    </div>

    <!-- Action Needed -->
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="stat-label">Action Needed</div>
        <div class="stat-value">03 Critical</div>
    </div>
</div>

<!-- Main Content Layout -->
<div class="content-layout">
    <!-- Left Column: Active Claims -->
    <div>
        <div class="claims-card">
            <div class="claims-title">Active Claims</div>

            <!-- Document Review Claim -->
            <div class="claim-item active">
                <div class="claim-header">
                    <span class="claim-badge doc-review">DOCUMENT REVIEW</span>
                    <span class="claim-number">#CLM-8902</span>
                </div>
                <div class="claim-name">John Doe (Main Member)</div>
                <div class="claim-beneficiary">Beneficiary: Mary Doe (Spouse)</div>
                <div class="claim-progress">
                    <i class="fas fa-file-check"></i>
                    <span>1/3 Docs verified</span>
                </div>
            </div>

            <!-- Logistics Claim -->
            <div class="claim-item">
                <div class="claim-header">
                    <span class="claim-badge logistics">LOGISTICS</span>
                    <span class="claim-number">#CLM-8891</span>
                </div>
                <div class="claim-name">Alice Wanjiru</div>
                <div class="claim-logistics-info">Coordination: Transport & Coffin</div>
                <div class="claim-pickup">
                    <i class="fas fa-clock"></i>
                    <span>Pickup Tomorrow 9:00 AM</span>
                </div>
            </div>

            <!-- Settled Claim -->
            <div class="claim-item">
                <div class="claim-header">
                    <span class="claim-badge settled">SETTLED</span>
                    <span class="claim-number">#CLM-8825</span>
                </div>
                <div class="claim-name">Robert King'ara</div>
                <div class="claim-settled-info">Grant Disbursed: Oct 20</div>
            </div>
        </div>
    </div>

    <!-- Right Column: Claim Verification -->
    <div>
        <div class="verification-card">
            <div class="verification-header">
                <div>
                    <div class="verification-title">Claim Verification: #CLM-8902</div>
                    <div class="verification-subtitle">Verify mandatory documents to proceed to logistics</div>
                </div>
                <div style="text-align: right;">
                    <div class="claim-amount-label">Claim Amount</div>
                    <div class="claim-amount-value">KES 80,000</div>
                </div>
            </div>

            <!-- Document Upload Grid -->
            <div class="document-grid">
                <!-- ID Copy -->
                <div class="document-slot verified">
                    <i class="fas fa-id-card document-icon"></i>
                    <div class="document-label">1. ID Copy</div>
                    <span class="document-status verified">VERIFIED</span>
                </div>

                <!-- Chief's Letter -->
                <div class="document-slot review">
                    <i class="fas fa-file-signature document-icon"></i>
                    <div class="document-label">2. Chief's Letter</div>
                    <span class="document-status review">REVIEW REQ</span>
                </div>

                <!-- Mortuary Invoice -->
                <div class="document-slot review">
                    <i class="fas fa-file-invoice document-icon"></i>
                    <div class="document-label">3. Mortuary Invoice</div>
                    <span class="document-status review">REVIEW REQ</span>
                </div>
            </div>

            <!-- Logistics Section -->
            <div class="logistics-section">
                <div class="logistics-header">
                    <div class="logistics-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="logistics-title">Approve & Dispatch Logistics</div>
                </div>

                <!-- Coffin Selection -->
                <div class="form-section">
                    <label class="form-label">Coffin Selection</label>
                    <select class="form-select">
                        <option>Executive Mahogany Finish</option>
                        <option>Standard Oak</option>
                        <option>Premium Walnut</option>
                    </select>
                </div>

                <!-- Equipment Checklist -->
                <div class="form-section">
                    <label class="form-label">Equipment Checklist</label>
                    <div class="checklist-grid">
                        <div class="checklist-item">
                            <input type="checkbox" class="checklist-checkbox" id="tents" checked>
                            <label for="tents" class="checklist-label">Tents & Chairs</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" class="checklist-checkbox" id="lowering" checked>
                            <label for="lowering" class="checklist-label">Lowering Gear</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" class="checklist-checkbox" id="sound">
                            <label for="sound" class="checklist-label">Sound System</label>
                        </div>
                        <div class="checklist-item">
                            <input type="checkbox" class="checklist-checkbox" id="programs">
                            <label for="programs" class="checklist-label">Programs Print</label>
                        </div>
                    </div>
                </div>

                <!-- Transport Allocation -->
                <div class="form-section" style="margin-bottom: 0;">
                    <label class="form-label">Transport Allocation</label>
                    <select class="form-select">
                        <option>Hearse Unit #04 - KCH 234D</option>
                        <option>Hearse Unit #02 - KBZ 123A</option>
                        <option>Hearse Unit #06 - KAA 456C</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layouts/admin-footer.php'; ?>
