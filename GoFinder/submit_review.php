<?php
// submit_review.php - API to submit a new review and rating for an activity

session_start(); // Start session to get logged-in user info
include('db.php');

// Ensure the request method is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    // Get the submitted data
    $activityId = $data['activityId'];
    $rating = $data['rating'];
    $review = $data['review'];

    // Prepare the SQL query to insert a new review
    $stmt = $conn->prepare("
        INSERT INTO reviews (user_id, activity_id, rating, review)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE rating = VALUES(rating), review = VALUES(review)
    ");
    $stmt->bind_param("iiis", $userId, $activityId, $rating, $review);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Review submitted successfully!"]);
    } else {
        http_response_code(500);  // Internal Server Error
        echo json_encode(["message" => "Failed to submit review."]);
    }
    $stmt->close();
} else {
    http_response_code(400);  // Bad Request
    echo json_encode(["message" => "Invalid request or user not logged in."]);
}
?>
