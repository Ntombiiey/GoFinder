<<<<<<< HEAD
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
=======
<?php
// Include database connection
include('db.php');


session_start(); // Start session to track user login


if (isset($_COOKIE['remember_me'])) {
    //  auto-login the user
    $rememberToken = $_COOKIE['remember_me'];

    setcookie('remember_me', $rememberToken, time() + (86400 * 30), "/"); // 30 days
echo "Cookie has been set with value: $rememberToken"; // Debug line

           
    // Prepare statement to retrieve the user based on the token
    $stmt = $conn->prepare("SELECT id, email FROM sign_up WHERE remember_token = ?");
    $stmt->bind_param("s", $rememberToken);
    $stmt->execute();
    $stmt->bind_result($id, $email);
    if ($stmt->fetch()) {
        $_SESSION['user_id'] = $id; // Store user in session
       // echo "<script>alert('Welcome back! Redirecting...');</script>";
       
       header('Location: welcome.php');//home page
        exit();
    }
        $stmt->close();
    }
    
    

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
            $_SESSION['user_id'] = $id;
        }
            // Check if "Remember Me" is checked
            if (isset($_POST['remember_me'])) {
                $rememberToken = bin2hex(random_bytes(16)); // Generate a secure token
                setcookie('remember_me', $rememberToken, time() + (86400 * 30), "/"); // 30 days
                $updateStmt = $conn->prepare("UPDATE sign_up SET remember_token = ? WHERE id = ?");
                $updateStmt->bind_param("si", $rememberToken, $id);
                $updateStmt->execute();
                $updateStmt->close();
            }


            echo "<script>alert('Login successful! Redirecting...');</script>";
            
             header('Location: welcome.php'); //homepage 
        } else {
            echo "<script>alert('Invalid password!');</script>";
        }
    } else {
        echo "<script>alert('No user found with this email!');</script>";
    }
 //$stmt->close();

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
>>>>>>> 3ab2e7234dfa8b65343360c5026c2d843b90eb98
