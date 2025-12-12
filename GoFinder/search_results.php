<?php
// Step 2.1: Connect to the Database
$conn = new mysqli('localhost', 'username', 'password', 'go_finder');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2.2: Capture the Search Query and Filter
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Prepare the SQL statement
$sql = "SELECT * FROM places WHERE has_wifi LIKE ?";
$params = ["%$query%"]; // Use an array to hold the parameters

// Modify SQL based on the selected filter
if ($filter === 'location') {
    $sql .= " AND category = ?";
    $params[] = 'location';
} elseif ($filter === 'prices') {
    $sql .= " AND category = ?";
    $params[] = 'prices';
} elseif ($filter === 'free-wifi') {
    $sql .= " AND wifi_available = 1";
} elseif ($filter === 'reviews') {
    $sql .= " ORDER BY star_rating DESC";
}

// Prepare the statement
$stmt = $conn->prepare($sql);
if ($filter === 'free-wifi') {
    $stmt->bind_param("s", $params[0]); // Only bind the first parameter
} else {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params); // Bind all parameters
}

// Step 2.4: Execute the SQL Query
$stmt->execute();
$result = $stmt->get_result();

// Step 2.5: Display Results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='result-item'>";
        echo "<h2>" . htmlspecialchars($row['business_name']) . "</h2>";
        echo "<p>" . htmlspecialchars($row['description']) . "</p>";
        echo "<p>Rating: " . htmlspecialchars($row['star_rating']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No results found for your search.</p>";
}

// Close the connection
$stmt->close();
$conn->close();
?>