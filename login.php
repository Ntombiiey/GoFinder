<?php
// Include database connection
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare statement to retrieve user info
    $stmt = $conn->prepare("SELECT id, password FROM sign_up WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Redirect or handle login success
            echo "<script>alert('Login successful! Redirecting...');</script>";
            // Redirect to a new page
           header ("Location: Catogories.html");
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!');</script>";
    }

    $stmt->close();
}
?>


<!-- HTML Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login_Form</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Login</header>
        </div>
        <form method="POST" action="login.php">
            <div class="input-box">
                <input type="email" name="email" class="input-field" placeholder="Email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" class="input-field" placeholder="Password" autocomplete="off" required>
            </div>
            <div class="forgot">
                <section>
                    <input type="checkbox" id="check">
                    <label for="check">Remember me</label>
                </section>
                <section>
                    <a href="forgot_password.php"> Forgot password</a>
                </section>
            </div>
            <div class="input-submit">
                <button type="submit" class="submit-btn">Sign In</button>
            </div>
        </form>
        <div class="sign-up-link">
            <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
        </div>

        <!-- Display error message -->
        <?php
        if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }

      
          ?>
    </div>
</body>
</html>
