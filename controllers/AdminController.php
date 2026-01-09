<?php
require_once __DIR__ . '/../models/Issue.php';
require_once __DIR__ . '/../config/helpers.php';

class AdminController
{
    public static function dashboardData(): array
    {
        requireLogin();
        if (!isAdmin()) {
            flash('error', 'Admin access required.');
            redirect('index.php?page=dashboard');
        }
        $issues = Issue::getAll();
        $byType = Issue::statsByType();
        $statusCounts = Issue::statusCounts();
        $heatmap = Issue::heatmapData();
        $highPriority = array_filter($issues, fn($i) => $i['priority_score'] >= 10);

        return [
            'issues'        => $issues,
            'byType'        => $byType,
            'statusCounts'  => $statusCounts,
            'heatmap'       => $heatmap,
            'highPriority'  => $highPriority,
        ];
    }
}

