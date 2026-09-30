<?php

session_start();

include "connection.php";

if (!isset($_SESSION['otp_email'])) {
    header("Location: register.php");
    exit;
}

if (isset($_POST['verify'])) {

    $email = $_SESSION['otp_email'];
    $otp = $_POST['otp'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE email = ? AND otp = ?"
    );

    mysqli_stmt_bind_param($stmt, "ss", $email, $otp);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $update = mysqli_prepare(
            $conn,
            "UPDATE users SET verified = 1, otp = NULL WHERE email = ?"
        );

        mysqli_stmt_bind_param($update, "s", $email);
        mysqli_stmt_execute($update);

        unset($_SESSION['otp_email']);

        header("Location: login.php");
        exit;

    } else {

        $error = "Invalid OTP.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Enter OTP</h2>

<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>

<form method="POST">

    <input
        type="text"
        name="otp"
        placeholder="Enter OTP"
        maxlength="6"
    >

    <br><br>

    <button name="verify">Verify OTP</button>

</form>

</body>
</html>