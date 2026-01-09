<?php
require_once __DIR__ . '/../config/db.php';

class Issue
{
    public static function create(array $data): bool
    {
        $pdo = getDB();
        $stmt = $pdo->prepare(
            'INSERT INTO issues (title, description, image, latitude, longitude, issue_type, priority_score, status, created_at, reported_by)
             VALUES (:title, :description, :image, :lat, :lng, :type, :priority, :status, NOW(), :reported_by)'
        );
        return $stmt->execute([
            ':title'       => $data['title'],
            ':description' => $data['description'],
            ':image'       => $data['image'] ?? null,
            ':lat'         => $data['latitude'],
            ':lng'         => $data['longitude'],
            ':type'        => $data['issue_type'],
            ':priority'    => $data['priority_score'] ?? 0,
            ':status'      => $data['status'] ?? 'Pending',
            ':reported_by' => $data['reported_by'],
        ]);
    }

    public static function getAll(?string $status = null): array
    {
        $pdo = getDB();
        if ($status) {
            $stmt = $pdo->prepare('SELECT i.*, u.name AS reporter FROM issues i LEFT JOIN users u ON i.reported_by = u.user_id WHERE status = ? ORDER BY priority_score DESC, created_at DESC');
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query('SELECT i.*, u.name AS reporter FROM issues i LEFT JOIN users u ON i.reported_by = u.user_id ORDER BY priority_score DESC, created_at DESC');
        }
        return $stmt->fetchAll();
    }

    public static function getById(int $id): ?array
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT * FROM issues WHERE issue_id = ?');
        $stmt->execute([$id]);
        $issue = $stmt->fetch();
        return $issue ?: null;
    }

    public static function updateStatus(int $id, string $status): bool
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('UPDATE issues SET status = ? WHERE issue_id = ?');
        return $stmt->execute([$status, $id]);
    }

    public static function setPriority(int $id, int $score): bool
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('UPDATE issues SET priority_score = ? WHERE issue_id = ?');
        return $stmt->execute([$score, $id]);
    }

    public static function recalcPriority(int $issueId): ?int
    {
        $pdo = getDB();
        $stmt = $pdo->prepare('SELECT issue_type, created_at FROM issues WHERE issue_id = ?');
        $stmt->execute([$issueId]);
        $issue = $stmt->fetch();
        if (!$issue) {
            return null;
        }

        $voteData = Vote::getIssueVoteWeights($issueId);
        $voteScore = $voteData['score'] ?? 0;
        $typeWeight = self::typeWeight($issue['issue_type']);
        $daysSince = (new DateTime($issue['created_at']))->diff(new DateTime())->days;
        $priority = ($voteScore * 2) + $daysSince + $typeWeight;
        self::setPriority($issueId, $priority);
        return $priority;
    }

    public static function findDuplicates(float $lat, float $lng, string $type, float $radiusKm = 0.3): array
    {
        $pdo = getDB();
        // Use positional parameters to avoid driver quirks with repeated named params
        $stmt = $pdo->prepare(
            'SELECT *,
            (6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance
             FROM issues
             WHERE issue_type = ?
             HAVING distance <= ?
             ORDER BY distance ASC
             LIMIT 5'
        );
        $stmt->execute([$lat, $lng, $lat, $type, $radiusKm]);
        return $stmt->fetchAll();
    }

    public static function statsByType(): array
    {
        $pdo = getDB();
        $stmt = $pdo->query('SELECT issue_type, COUNT(*) as total FROM issues GROUP BY issue_type');
        return $stmt->fetchAll();
    }

    public static function statusCounts(): array
    {
        $pdo = getDB();
        $stmt = $pdo->query('SELECT status, COUNT(*) as total FROM issues GROUP BY status');
        return $stmt->fetchAll();
    }

    public static function heatmapData(): array
    {
        $pdo = getDB();
        $stmt = $pdo->query('SELECT latitude, longitude, priority_score FROM issues');
        return $stmt->fetchAll();
    }

    private static function typeWeight(string $type): int
    {
        $weights = [
            'accident' => 5,
            'water leakage' => 4,
            'pothole' => 3,
            'broken streetlight' => 2,
        ];
        return $weights[strtolower($type)] ?? 1;
    }
}

