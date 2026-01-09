<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Admin Dashboard</h3>
        <p class="text-muted small mb-0">Monitor, prioritize, and act on citizen reports.</p>
    </div>
    <div>
        <span class="badge bg-danger">High priority: <?= count($data['highPriority']); ?></span>
        <span class="badge bg-secondary ms-2">Total: <?= count($data['issues']); ?></span>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6>Status Breakdown</h6>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6>Issues by Type</h6>
                <canvas id="typeChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6>Heatmap (priority weighted)</h6>
                <div id="heatmap" style="height:200px;" class="border rounded"></div>
                <p class="small text-muted mt-2">Color intensity rises with priority score.</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">High Priority Queue</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data['highPriority'] as $issue): ?>
                    <tr>
                        <td><?= sanitize($issue['title']); ?></td>
                        <td><?= sanitize($issue['issue_type']); ?></td>
                        <td><span class="badge bg-danger"><?= intval($issue['priority_score']); ?></span></td>
                        <td><?= sanitize($issue['status']); ?></td>
                        <td>
                            <form class="d-flex" method="post" action="index.php?page=admin">
                                <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="issue_id" value="<?= intval($issue['issue_id']); ?>">
                                <select name="status" class="form-select form-select-sm me-2">
                                    <?php foreach (['Pending','In Progress','Resolved','Closed'] as $st): ?>
                                        <option value="<?= $st; ?>" <?= $issue['status'] === $st ? 'selected' : ''; ?>><?= $st; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn btn-sm btn-outline-secondary">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($data['highPriority'])): ?>
                    <tr><td colspan="5" class="text-muted">No high-priority items yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    window.dashboardData = {
        byType: <?= json_encode($data['byType']); ?>,
        statusCounts: <?= json_encode($data['statusCounts']); ?>,
        heatmap: <?= json_encode($data['heatmap']); ?>
    };
</script>

