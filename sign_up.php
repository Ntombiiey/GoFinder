<?php
// Include database connection
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT email FROM sign_up WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO sign_up (firstName, lastName, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful! You can now log in. .');
            setTimeout(function(){
            window.location.href = 'login.php';
        }, 2000); // Redirect after 2 seconds </script>";
           
        //header("Location: login.php");
            exit();
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }
        
        $stmt->close();
    }
}
?>


<!-- HTML Signup Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUP</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="Signup-box">
        <div class="Signup-header">
            <header>Sign Up</header>
        </div>
        <form method="POST" action="sign_up.php">
            <div class="input-box">
                <input type="text" name="first_name" class="input-field" placeholder="First Name" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="text" name="last_name" class="input-field" placeholder="Last Name" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" class="input-field" placeholder="Email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" id="password"class="input-field" placeholder="Password" autocomplete="off" required>
                <span class="password-toggle" onclick="togglePassword()">
                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                </span>
            
            </div>

            <div class="input-submit">
                <button type="submit" class="submit-btn">Sign Up</button>
            </div>
        </form>
        <div class="sign-up-link">
            <p>Already have an account? <a href="login.php">Login Here</a></p>
        </div>

        <!-- success or error message -->
        <?php
        if (isset($success)) {
            echo "<p style='color:green;'>$success</p>";
        } elseif (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
        ?>
        

    <!-- Password  -->
        <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var icon = document.getElementById("togglePasswordIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash"); // Change to 'eye slash' icon
          
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye"); // Change back to 'eye' icon
            
            }
        }
    </script>

    </div>
</body>
</html>
