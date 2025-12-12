<?php
// get_reviews.php - API to fetch reviews and ratings for an activity

// Include the database connection
include('db.php');

// Ensure the request method is GET and activityId is provided
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['activityId'])) {
    $activityId = $_GET['activityId'];

    // Prepare the SQL query to fetch reviews and calculate the average rating
    $stmt = $conn->prepare("
        SELECT r.review, r.rating, u.firstName AS name
        FROM reviews r
        JOIN sign_up u ON r.user_id = u.id
        WHERE r.activity_id = ?
    ");
    $stmt->bind_param("i", $activityId);  // Bind the activityId to the query
    $stmt->execute();
    $result = $stmt->get_result();

    $reviews = [];
    $totalRating = 0;
    $numReviews = $result->num_rows;

    // Loop through all fetched reviews
    while ($row = $result->fetch_assoc()) {
        $reviews[] = [
            'name' => $row['name'],
            'comment' => $row['review'],
            'rating' => $row['rating']
        ];
        $totalRating += $row['rating'];
    }

    // Calculate the average rating
    $averageRating = $numReviews > 0 ? $totalRating / $numReviews : 0;

    // Return the reviews and average rating as JSON
    echo json_encode([
        'reviews' => $reviews,
        'averageRating' => round($averageRating, 2)
    ]);
} else {
    // Invalid request
    http_response_code(400);  // Bad Request
    echo json_encode(["message" => "Invalid request or missing activityId."]);
}
?>
