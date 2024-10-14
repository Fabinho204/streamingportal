<?php
session_start();
require_once('../models/db.php');
require_once('../views/header.php');

$errors = [];
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = "user"; // Default role for users

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        $errors[] = "All fields are required.";
    } else {
        $db = new db();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "A user with this email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $insertStmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $insertStmt->bind_param("sssss", $firstname, $lastname, $email, $hashed_password, $role);

            if ($insertStmt->execute()) {
                $success_message = "Registration successful. You can now log in.";
                header("Location: login.php"); // Redirect to login
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }

            $insertStmt->close();
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome Link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/script.js"></script>

    <!-- Font Family -->
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&family=Noto+Sans+Arabic:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="login-container"> <!-- Using login-container class to match login form style -->
        <h2 class="text-center">Sign Up</h2>
        <form id="signupForm" action="signup.php" method="POST">
            <div class="form-group">
                <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
            </div>
            <div class="form-group">
                <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="signup" class="btn">Sign Up</button>
            </div>
        </form>

        <?php
        if (!empty($errors)) {
            echo '<div class="alert alert-danger">';
            foreach ($errors as $error) {
                echo '<div>' . htmlspecialchars($error) . '</div>';
            }
            echo '</div>';
        }
        ?>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
