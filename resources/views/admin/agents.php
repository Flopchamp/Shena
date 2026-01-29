<?php
$page = 'agents';
$pageTitle = 'Agent Management';
$pageSubtitle = 'Manage agents, commissions, and recruitment performance';
include VIEWS_PATH . '/layouts/dashboard-header.php';
?>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-primary);">
            <i class="bi bi-person-badge-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($total_agents ?? 0); ?></div>
            <div class="stat-label">Total Agents</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-success);">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($active_agents ?? 0); ?></div>
            <div class="stat-label">Active Agents</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-info);">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value"><?php echo number_format($total_agent_members ?? 0); ?></div>
            <div class="stat-label">Members Recruited</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--gradient-warning);">
            <i class="bi bi-cash-stack"></i>
        </div>
        <div class="stat-details">
            <div class="stat-value">KES <?php echo number_format($pending_commissions ?? 0); ?></div>
            <div class="stat-label">Pending Commissions</div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0 1.5rem;">
    <div></div>
    <a href="/admin/agents/create" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Register New Agent
    </a>
</div>

<!-- Search and Filter Card -->
<div class="card">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-search"></i> Search & Filter</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="/admin/agents" style="display: grid; grid-template-columns: 2fr 1fr auto auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="search">Search Agents</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       class="form-control" 
                       placeholder="Name, agent number, or phone" 
                       value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
            </div>
            
            <div class="form-group" style="margin: 0;">
                <label class="form-label" for="status">Status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" <?php echo ($filters['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="suspended" <?php echo ($filters['status'] ?? '') === 'suspended' ? 'selected' : ''; ?>>Suspended</option>
                    <option value="inactive" <?php echo ($filters['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
            
            <a href="/admin/agents" class="btn btn-outline">
                <i class="bi bi-x-circle"></i> Clear
            </a>
        </form>
    </div>
</div>

<!-- Agents Table -->
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 style="margin: 0;"><i class="bi bi-table"></i> Agents List</h4>
            <button class="btn btn-success btn-sm" onclick="window.location.href='/admin/export/agents'">
                <i class="bi bi-download"></i> Export CSV
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($agents)): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Agent #</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Performance</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agents as $agent): ?>
                    <tr>
                        <td>
                            <span style="font-family: var(--font-mono); font-weight: 600; color: var(--primary-purple);">
                                <?php echo htmlspecialchars($agent['agent_number']); ?>
                            </span>
                        </td>
                        <td>
                            <div>
                                <div style="font-weight: 600; color: var(--secondary-violet);">
                                    <?php echo htmlspecialchars($agent['first_name'] . ' ' . $agent['last_name']); ?>
                                </div>
                                <?php if (!empty($agent['id_number'])): ?>
                                <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                    ID: <?php echo htmlspecialchars($agent['id_number']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <div><i class="bi bi-phone"></i> <?php echo htmlspecialchars($agent['phone']); ?></div>
                                <div style="color: var(--medium-grey);"><i class="bi bi-envelope"></i> <?php echo htmlspecialchars($agent['email']); ?></div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span class="badge badge-info">
                                    <?php echo $agent['total_members'] ?? 0; ?> Members
                                </span>
                            </div>
                            <?php if (($agent['total_members'] ?? 0) > 0): ?>
                            <div style="margin-top: 0.5rem;">
                                <div class="progress-bar" style="height: 8px;">
                                    <div class="progress-bar-fill" 
                                         style="width: <?php echo min(100, ($agent['total_members'] / 50) * 100); ?>%; background: var(--gradient-success);"></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--secondary-violet);">
                                KES <?php echo number_format($agent['pending_commission'] ?? 0, 2); ?>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--medium-grey);">
                                Pending
                            </div>
                        </td>
                        <td>
                            <?php
                            $statusClass = match($agent['status']) {
                                'active' => 'badge-success',
                                'suspended' => 'badge-warning',
                                'inactive' => 'badge-secondary',
                                default => 'badge-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo ucfirst($agent['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/admin/agents/view/<?php echo $agent['id']; ?>" 
                                   class="btn btn-sm btn-info" 
                                   title="View Details">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                
                                <a href="/admin/agents/edit/<?php echo $agent['id']; ?>" 
                                   class="btn btn-sm btn-warning" 
                                   title="Edit Agent">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                
                                <?php if ($agent['status'] === 'active'): ?>
                                <form method="POST" action="/admin/agent/suspend" style="display: inline;" onsubmit="return confirm('Suspend this agent?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="agent_id" value="<?php echo $agent['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Suspend">
                                        <i class="bi bi-pause-circle-fill"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <form method="POST" action="/admin/agent/activate" style="display: inline;" onsubmit="return confirm('Activate this agent?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                                    <input type="hidden" name="agent_id" value="<?php echo $agent['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-success" title="Activate">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="empty-state">
            <i class="bi bi-person-badge" style="font-size: 4rem; color: var(--light-grey); margin-bottom: 1rem;"></i>
            <h3 style="color: var(--medium-grey); margin-bottom: 0.5rem;">No Agents Found</h3>
            <p style="color: var(--medium-grey);">
                <?php echo !empty($filters['search']) ? 'Try adjusting your search criteria.' : 'Agents will appear here once they are registered.'; ?>
            </p>
            <a href="/admin/agents/create" class="btn btn-primary" style="margin-top: 1rem;">
                <i class="bi bi-person-plus-fill"></i> Register First Agent
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Top Performers (if agents exist) -->
<?php if (!empty($agents) && count($agents) > 0): ?>
<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h4 style="margin: 0;"><i class="bi bi-trophy-fill"></i> Top Performers This Month</h4>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <?php 
            // Sort agents by total members descending
            $topAgents = $agents;
            usort($topAgents, function($a, $b) {
                return ($b['total_members'] ?? 0) - ($a['total_members'] ?? 0);
            });
            $topAgents = array_slice($topAgents, 0, 3);
            
            foreach ($topAgents as $index => $topAgent): 
            if (($topAgent['total_members'] ?? 0) > 0):
            ?>
            <div style="background: var(--soft-grey); padding: 1.5rem; border-radius: var(--radius-md); position: relative;">
                <?php if ($index === 0): ?>
                <div style="position: absolute; top: -10px; right: -10px; width: 40px; height: 40px; background: var(--gradient-warning); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; box-shadow: var(--shadow-md);">
                    <i class="bi bi-trophy-fill"></i>
                </div>
                <?php endif; ?>
                <div style="text-align: center;">
                    <div style="width: 60px; height: 60px; margin: 0 auto 1rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: 700;">
                        <?php echo strtoupper(substr($topAgent['first_name'], 0, 1) . substr($topAgent['last_name'], 0, 1)); ?>
                    </div>
                    <h5 style="margin: 0 0 0.25rem 0; color: var(--secondary-violet);">
                        <?php echo htmlspecialchars($topAgent['first_name'] . ' ' . $topAgent['last_name']); ?>
                    </h5>
                    <p style="margin: 0 0 1rem; color: var(--medium-grey); font-size: 0.875rem;">
                        <?php echo htmlspecialchars($topAgent['agent_number']); ?>
                    </p>
                    <div class="badge badge-success" style="font-size: 1.125rem; padding: 0.5rem 1rem;">
                        <?php echo $topAgent['total_members']; ?> Members
                    </div>
                </div>
            </div>
            <?php 
            endif;
            endforeach; 
            ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include VIEWS_PATH . '/layouts/dashboard-footer.php'; ?>
