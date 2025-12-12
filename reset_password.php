<<<<<<< HEAD
<?php
// Include database connection
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Validate token and update password
    $stmt = $conn->prepare("UPDATE sign_up SET password = ?, reset_token = NULL WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("ss", $hashedPassword, $token);
   
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Invalid or expired token!');</script>";
        }
    } else {
        echo "<script>alert('Something went wrong! Please try again.');</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
<form method="POST" action="reset_password.php">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <div class="input-box">
        <input type="password" name="new_password" class="input-field" placeholder="Enter new password" required>
    </div>
    <div class="input-submit">
        <button type="submit" class="submit-btn">Set New Password</button>
    </div>
</form>
=======
<?php
// Include database connection
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Validate token and update password
    $stmt = $conn->prepare("UPDATE sign_up SET password = ?, reset_token = NULL WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("ss", $hashedPassword, $token);
    if ($stmt->execute()) {
        echo "<script>alert('Password reset successful!');</script>";
    } else {
        echo "<script>alert('Invalid or expired token!');</script>";
    }
}
?>
<form method="POST" action="reset_password.php">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <div class="input-box">
        <input type="password" name="new_password" class="input-field" placeholder="Enter new password" required>
    </div>
    <div class="input-submit">
        <button type="submit" class="submit-btn">Set New Password</button>
    </div>
</form>
>>>>>>> 3ab2e7234dfa8b65343360c5026c2d843b90eb98
