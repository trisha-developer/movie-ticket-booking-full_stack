<?php

session_start();

include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {

    $booking_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM bookings
         WHERE id = ? AND user_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ii",
        $booking_id,
        $user_id
    );

    mysqli_stmt_execute($stmt);
}

header("Location: bookings.php");
exit;

?>