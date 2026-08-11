<?php
session_start();

// Hardcoded credentials (replace with DB later)
$admin_user = "admin";
$admin_pass = "12345";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1b0000, #4d0000);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: #1f1f1f;
            padding: 45px 40px;
            border-radius: 15px;
            width: 380px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.6);
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
            color: #fff;
        }

        .login-box h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
        }

        .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field input {
            width: 100%;
            padding: 14px 15px 14px 45px;
            border: 1px solid #333;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            background: #2c2c2c;
            color: #fff;
            transition: all 0.3s ease;
        }

        .input-field input::placeholder {
            color: #aaa;
        }

        .input-field input:focus {
            background: #1f1f1f;
            border-color: #e74c3c;
            box-shadow: 0 0 8px rgba(231,76,60,0.5);
        }

        .input-field i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #e74c3c;
            font-size: 16px;
            pointer-events: none;
        }

        button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(231,76,60,0.6);
        }

        .error {
            margin-bottom: 18px;
            color: #e74c3c;
            font-size: 14px;
            background: #2c0000;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #e74c3c;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @media (max-width: 420px) {
            .login-box {
                width: 90%;
                padding: 30px 20px;
            }
        }
    </style>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <div class="input-field">
                <i class="fa fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-field">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
