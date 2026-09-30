<?php

session_start();

include "connection.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $repeat = $_POST['repeat'];

    if (empty($name) || empty($email) || empty($pass) || empty($repeat)) {

        $error = "All fields are required.";

    } elseif ($pass != $repeat) {

        $error = "Passwords do not match.";

    } else {

        // Check email
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {

            $error = "Email already registered.";

        } else {

            $otp = rand(1000, 9999);

            $password = password_hash($pass, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name,email,password,otp) VALUES (?,?,?,?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $name,
                $email,
                $password,
                $otp
            );

            if (mysqli_stmt_execute($stmt)) {

                $_SESSION['otp_email'] = $email;

                // Send OTP
                $mail = new PHPMailer(true);

                try {

                    $mail->isSMTP();
                    $mail->Host = 'smtp.example.com';
                    $mail->SMTPAuth = true;

                    $mail->Username = 'demo@example.com';
                    $mail->Password = 'YOUR_PASSWORD';

                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom(
                        'demo@example.com',
                        'CineBook'
                    );

                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = "CineBook OTP";
                    $mail->Body = "<h2>Your OTP is: $otp</h2>";

                    $mail->send();

                    header("Location: otp.php");
                    exit;

                } catch (Exception $e) {

                    $error = "OTP could not be sent.";

                }

            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - CineBook</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Registration</h2>

<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>

<form method="POST">

    <input type="text" name="name" placeholder="Name">
    <br><br>

    <input type="email" name="email" placeholder="Email">
    <br><br>

    <input type="password" name="password" placeholder="Password">
    <br><br>

    <input type="password" name="repeat" placeholder="Repeat Password">
    <br><br>

    <button name="register">Register</button>

</form>

<br>

<a href="login.php">Already have an account? Login</a>

</body>
</html>
