<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-0">Reported Issues</h3>
        <p class="text-muted small mb-0">Vote to influence priority.</p>
    </div>
    <a class="btn btn-primary" href="index.php?page=report">+ Report Issue</a>
</div>

<div class="row">
    <?php foreach ($issues as $issue): ?>
        <div class="col-md-6 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5><?= sanitize($issue['title']); ?></h5>
                        <span class="badge bg-secondary"><?= sanitize($issue['issue_type']); ?></span>
                    </div>
                    <p class="small text-muted mb-1">Status: <?= sanitize($issue['status']); ?></p>
                    <p><?= nl2br(sanitize($issue['description'])); ?></p>
                    <?php if (!empty($issue['image'])): ?>
                        <img src="<?= sanitize($issue['image']); ?>" class="img-fluid rounded mb-2" alt="Issue image">
                    <?php endif; ?>
                    <p class="small mb-1">Priority Score: <strong><?= intval($issue['priority_score']); ?></strong></p>
                    <p class="small text-muted">Reported by: <?= sanitize($issue['reporter'] ?? 'Unknown'); ?> on <?= sanitize($issue['created_at']); ?></p>
                    <form class="d-flex align-items-center" method="post" action="index.php?page=dashboard">
                        <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
                        <input type="hidden" name="action" value="cast_vote">
                        <input type="hidden" name="issue_id" value="<?= intval($issue['issue_id']); ?>">
                        <label class="me-2 small mb-0">Your vote</label>
                        <select name="vote_level" class="form-select form-select-sm me-2" style="width:140px">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                        <button class="btn btn-sm btn-outline-primary">Vote</button>
                    </form>
                    <?php if (isAdmin()): ?>
                        <form class="d-flex align-items-center mt-2" method="post" action="index.php?page=dashboard">
                            <input type="hidden" name="csrf" value="<?= csrfToken(); ?>">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="issue_id" value="<?= intval($issue['issue_id']); ?>">
                            <label class="me-2 small mb-0">Status</label>
                            <select name="status" class="form-select form-select-sm me-2" style="width:150px">
                                <?php foreach (['Pending','In Progress','Resolved','Closed'] as $st): ?>
                                    <option value="<?= $st; ?>" <?= $issue['status'] === $st ? 'selected' : ''; ?>><?= $st; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-outline-secondary">Update</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($issues)): ?>
        <p class="text-muted">No issues yet. Be the first to report.</p>
    <?php endif; ?>
</div>

