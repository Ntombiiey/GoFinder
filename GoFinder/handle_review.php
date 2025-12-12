<?php
require_once 'db_config.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch($action) {
    case 'submit':
        submitReview($pdo);
        break;
    case 'get':
        getReviews($pdo);
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        exit();
}

function submitReview($pdo) {
    $activity_id = filter_input(INPUT_POST, 'activity_id', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    
    if (!$activity_id || !$rating || !$comment) {
        echo json_encode(['error' => 'Invalid input']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("INSERT INTO reviews (activity_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->execute([$activity_id, $rating, $comment]);
        
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } catch(PDOException $e) {
        echo json_encode(['error' => 'Failed to submit review']);
    }
}

function getReviews($pdo) {
    $activity_id = filter_input(INPUT_GET, 'activity_id', FILTER_SANITIZE_STRING);
    
    if (!$activity_id) {
        echo json_encode(['error' => 'Invalid activity ID']);
        return;
    }
    
    try {
        // Get reviews
        $stmt = $pdo->prepare("SELECT * FROM reviews WHERE activity_id = ? ORDER BY created_at DESC");
        $stmt->execute([$activity_id]);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate average rating
        $stmt = $pdo->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE activity_id = ?");
        $stmt->execute([$activity_id]);
        $avgRating = $stmt->fetch(PDO::FETCH_ASSOC)['avg_rating'];
        
        echo json_encode([
            'success' => true,
            'reviews' => $reviews,
            'averageRating' => round($avgRating ?? 0, 1)
        ]);
    } catch(PDOException $e) {
        echo json_encode(['error' => 'Failed to fetch reviews']);
    }
}
?>