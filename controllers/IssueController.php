<?php
require_once __DIR__ . '/../models/Issue.php';
require_once __DIR__ . '/../models/Vote.php';
require_once __DIR__ . '/../config/helpers.php';

class IssueController
{
    public static function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        if ($method === 'POST') {
            if (!verifyCsrf($_POST['csrf'] ?? '')) {
                flash('error', 'Invalid session token.');
                redirect('index.php?page=dashboard');
            }
            switch ($action) {
                case 'create_issue':
                    self::create();
                    break;
                case 'cast_vote':
                    self::vote();
                    break;
                case 'update_status':
                    self::updateStatus();
                    break;
            }
        }
    }

    private static function create(): void
    {
        requireLogin();
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $issueType = trim($_POST['issue_type'] ?? '');
        $lat = floatval($_POST['latitude'] ?? 0);
        $lng = floatval($_POST['longitude'] ?? 0);
        $imagePath = null;

        if (!$title || !$description || !$issueType || !$lat || !$lng) {
            flash('error', 'All fields including map location are required.');
            redirect('index.php?page=report');
        }

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../uploads/';
            $safeName = time() . '_' . basename($_FILES['image']['name']);
            $target = $uploadDir . $safeName;
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed, true) && move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                $imagePath = 'uploads/' . $safeName;
            } else {
                flash('error', 'Invalid image upload.');
                redirect('index.php?page=report');
            }
        }

        $duplicates = Issue::findDuplicates($lat, $lng, $issueType);
        if ($duplicates) {
            $names = array_column($duplicates, 'title');
            flash('error', 'Similar issue(s) found nearby: ' . implode(', ', $names));
        }

        $created = Issue::create([
            'title'         => $title,
            'description'   => $description,
            'image'         => $imagePath,
            'latitude'      => $lat,
            'longitude'     => $lng,
            'issue_type'    => $issueType,
            'priority_score'=> 0,
            'status'        => 'Pending',
            'reported_by'   => $_SESSION['user']['user_id'],
        ]);

        if ($created) {
            flash('success', 'Issue reported. Votes will shape its priority.');
        } else {
            flash('error', 'Unable to save issue.');
        }
        redirect('index.php?page=dashboard');
    }

    private static function vote(): void
    {
        requireLogin();
        $issueId = intval($_POST['issue_id'] ?? 0);
        $level = $_POST['vote_level'] ?? 'low';
        $userId = $_SESSION['user']['user_id'];
        $allowed = ['low', 'medium', 'high'];
        if (!$issueId || !in_array($level, $allowed, true)) {
            flash('error', 'Invalid vote.');
            redirect('index.php?page=dashboard');
        }

        if (Vote::cast($issueId, $userId, $level)) {
            Issue::recalcPriority($issueId);
            flash('success', 'Vote saved.');
        } else {
            flash('error', 'Could not save vote.');
        }
        redirect('index.php?page=dashboard');
    }

    private static function updateStatus(): void
    {
        if (!isAdmin()) {
            flash('error', 'Only admins can change status.');
            redirect('index.php?page=dashboard');
        }
        $issueId = intval($_POST['issue_id'] ?? 0);
        $status = $_POST['status'] ?? 'Pending';
        if ($issueId) {
            Issue::updateStatus($issueId, $status);
            Issue::recalcPriority($issueId);
            flash('success', 'Status updated.');
        }
        redirect('index.php?page=admin');
    }
}

