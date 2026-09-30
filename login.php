<?php

session_start();

include "connection.php";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE email = ?"
    );

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if ($user['verified'] == 0) {

            $error = "Please verify your email first.";

        } elseif (password_verify($pass, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: movies.php");
            exit;

        } else {

            $error = "Wrong password.";

        }

    } else {

        $error = "Email not found.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Login</h2>

<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>

<form method="POST">

    <input type="email" name="email" placeholder="Email">
    <br><br>

    <input type="password" name="password" placeholder="Password">
    <br><br>

    <button name="login">Login</button>

</form>

<br>

<a href="register.php">Create Account</a>

</body>
</html>