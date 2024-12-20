<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] !== "POST"){
        header ("Location: login.php");
        exit();
    }

    require('Connection.php');
    

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
        
    if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    }
    if (!isset($_SESSION['last_attempt_time'])) {
    $_SESSION['last_attempt_time'] = time();
    }
    $lockout_time = 180;
    if ($_SESSION['login_attempts'] >= 3 && time() - $_SESSION['last_attempt_time'] < $lockout_time) {
    $_SESSION['login_error'] = "Too many login attempts. Please try again after 3 minutes.";
    header("Location: login.php");
    exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE Email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($admin && password_verify($password, $admin['PasswordHash'])) {
                    // Redirect to admin dashboard
                    $_SESSION['loggedin'] = true;
                    $_SESSION['email'] = $admin['Email'];
                    $_SESSION['user_id'] = $admin['AdminID'];
                    header("Location: AdminPanel.php");
                    exit();
                }
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_id'] = $user['userID']; 
            $_SESSION['login_attempts'] = 0;
            header("Location: homepage.php");
            exit();
            }
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: login.php");
            exit();    
    } catch (PDOException $e) {
        $_SESSION['login_error'] = "Error. Please try again later.";
        header("Location: login.php");
        exit();
    }

?>