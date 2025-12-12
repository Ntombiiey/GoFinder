<?php
// get_user_rating.php - API to fetch user's previous rating for an activity

session_start(); // Start session to get logged-in user info
include('db.php');

// Check if user is logged in and activityId is provided
if (isset($_SESSION['user_id']) && isset($_GET['activityId'])) {
    $userId = $_SESSION['user_id'];
    $activityId = $_GET['activityId'];

    // Prepare the SQL query to fetch the user's rating
    $stmt = $conn->prepare("
        SELECT rating
        FROM reviews
        WHERE user_id = ? AND activity_id = ?
    ");
    $stmt->bind_param("ii", $userId, $activityId);
    $stmt->execute();
    $stmt->bind_result($rating);

    // Check if a rating exists for this user and activity
    if ($stmt->fetch()) {
        echo json_encode(['userRating' => $rating]);
    } else {
        echo json_encode(['userRating' => null]);  // No rating found
    }
    $stmt->close();
} else {
    http_response_code(400);  // Bad Request
    echo json_encode(["message" => "User not logged in or missing activityId."]);
}
?>
