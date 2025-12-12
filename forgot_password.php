<?php
// Include database connection
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT id FROM sign_up WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(16)); // Generate a unique token
        $stmt->bind_result($user_id);
        $stmt->fetch();

        // Store the token in the database
        $insertToken = $conn->prepare("UPDATE sign_up SET reset_token = ?, reset_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
        $insertToken->bind_param("si", $token, $user_id);
        $insertToken->execute();

        // Send the reset email (for simplicity, echo the link)
        $resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
        echo "<script>alert('A password reset link has been sent to your email!');</script>";
        // You would typically send the email here
        echo "Password reset link: $resetLink"; // Debug purpose
    } else {
        echo "<script>alert('No account associated with this email!');</script>";
    }

    $stmt->close();
}
?>
<form method="POST" action="forgot_password.php">
    <div class="input-box">
        <input type="email" name="email" class="input-field" placeholder="Enter your email" required>
    </div>
    <div class="input-submit">
        <button type="submit" class="submit-btn">Reset Password</button>
    </div>
</form>
