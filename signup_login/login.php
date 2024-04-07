<?php
include('login_header.php');
require_once '../connect-db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   

    function validateInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validateInput($_POST['username']);
    $password = validateInput($_POST['password']);
    $query = "SELECT u.user_id, u.username, p.pass_hash 
              FROM AF_User u
              JOIN AF_Password p ON u.pass_id = p.pass_id
              WHERE u.username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['pass_hash'])) {
            //later for keeping track of sessions. keeps the user logged in. 
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            header("Location: ../search_page.php");
            exit();
        } else {
            echo "<p>Invalid password.</p>";
        }
    } else {
        echo "<p>User not found.</p>";
    }
}
?>
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Yuina Barzdukas">
    <title>Log In</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
    </header>
    <main>
        <div class="login-container">
            <h2>Log In</h2>

            <form action="login.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block login-btn">Log In</button>
                </div>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="signup.php">Register here</a></p>
        </div>
        </div>
    </main>
</body>
</html>
