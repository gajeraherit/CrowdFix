<?php
require_once __DIR__ . '/../config/db.php';

class Vote
{
    public static function cast(int $issueId, int $userId, string $level): bool
    {
        $pdo = getDB();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('SELECT vote_id FROM votes WHERE issue_id = ? AND user_id = ?');
            $stmt->execute([$issueId, $userId]);
            if ($stmt->fetch()) {
                $update = $pdo->prepare('UPDATE votes SET vote_level = ? WHERE issue_id = ? AND user_id = ?');
                $update->execute([$level, $issueId, $userId]);
            } else {
                $insert = $pdo->prepare('INSERT INTO votes (issue_id, user_id, vote_level) VALUES (?, ?, ?)');
                $insert->execute([$issueId, $userId, $level]);
            }
            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function getIssueVoteWeights(int $issueId): array
    {
        $pdo = getDB();
        $stmt = $pdo->prepare(
            "SELECT 
                SUM(CASE WHEN vote_level = 'high' THEN 3 WHEN vote_level = 'medium' THEN 2 ELSE 1 END) AS score,
                COUNT(*) as total_votes
             FROM votes WHERE issue_id = ?"
        );
        $stmt->execute([$issueId]);
        return $stmt->fetch() ?: ['score' => 0, 'total_votes' => 0];
    }
}

