<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "username", "password", "go_finder");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Handle review submission
if (isset($_POST['action']) && $_POST['action'] === 'submit') {
    if (!isset($_POST['activity_id']) || !isset($_POST['rating']) || !isset($_POST['review'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
        exit;
    }

    $activity_id = $_POST['activity_id'];
    $rating = intval($_POST['rating']);
    $review_text = $_POST['review'];
    
    $sql = "INSERT INTO review (activity_id, rating, review_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("sis", $activity_id, $rating, $review_text);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    
    $stmt->close();
}

// Handle loading reviews
if (isset($_POST['action']) && $_POST['action'] === 'load') {
    if (!isset($_POST['activity_id'])) {
        echo json_encode(["status" => "error", "message" => "Activity ID is required"]);
        exit;
    }

    $activity_id = $_POST['activity_id'];
    
    $sql = "SELECT * FROM review WHERE activity_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    
    $stmt->bind_param("s", $activity_id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $reviews = [];
        
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        echo json_encode(["status" => "success", "data" => $reviews]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    
    $stmt->close();
}

$conn->close();
?>