<?php
include('login_header.php');
require_once '../connect-db.php';


if (isset($_SESSION['username'])) : session_destroy();
endif;             




function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function usernameExists($username) {
    global $db;
    $query = "SELECT * FROM AF_User WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$username]);
    return ($stmt->rowCount() > 0);
}

function addUser($username, $password_hash) {
    global $db;
    $insertQuery = "INSERT INTO AF_Password (pass_hash) VALUES (?)";
    $stmt = $db->prepare($insertQuery);
    $stmt->execute([$password_hash]);
    $pass_id = $db->lastInsertId(); 
    $insertUserQuery = "INSERT INTO AF_User (username, pass_id) VALUES (?, ?)";
    $stmtUser = $db->prepare($insertUserQuery);
    $stmtUser->execute([$username, $pass_id]);
    return $db->lastInsertId();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = validateInput($_POST['username']);
    $password = validateInput($_POST['password']);

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if (usernameExists($username)) {
        echo "<p>Username already exists. Please choose a different username.</p>";
    } else {
        $db->beginTransaction();

        try {
            $user_id = addUser($username, $password_hash);

            $db->commit();

            echo "<p>Registered successfully. You can now <a href='login.php'>login</a>.</p>";
        } catch (PDOException $e) {
            $db->rollBack();
            echo "<p>An error occurred during registration: " . $e->getMessage() . "</p>";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Yuina Barzdukas">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
    </header>
    <main>
        <div class="login-container">
            <h2>Sign Up</h2>

            <form action="signup.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                

                <div class="form-group">
                    <label class="form-label" for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block login-btn">Sign Up</button>
                </div>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login.php">Log in here</a></p>
        </div>
    </main>
</body>
</html>
