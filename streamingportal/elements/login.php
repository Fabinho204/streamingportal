<?php
session_start();

require_once('../models/db.php');
require_once('../views/header.php');

$db = new db();
$conn = $db->getConnection();

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST["name"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $errors[] = "Username and Password are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, firstname, lastname, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $username); // Bind the email (used as username)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            if (password_verify($password, $row['password'])) {
                $_SESSION['userlogin'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_firstname'] = $row['firstname'];
                $_SESSION['user_lastname'] = $row['lastname'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                header("Location: ../views/homepage.php"); // Redirect to the homepage
                exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "User not found.";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-In</title>
    
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
    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn">Login</button>
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
        <p>No Account yet? <a href="signup.php">Registrieren</a></p>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
