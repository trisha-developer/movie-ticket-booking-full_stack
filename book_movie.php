<?php

session_start();

include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: movies.php");
    exit;
}

$movie_id = $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM movies WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $movie_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

if (!$movie) {
    die("Movie not found");
}

if (isset($_POST['book'])) {

    $seats = $_POST['seats'];
    $user_id = $_SESSION['user_id'];

    if ($seats < 1) {

        $error = "Enter valid seats.";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO bookings (user_id,movie_id,seats)
             VALUES (?,?,?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iii",
            $user_id,
            $movie_id,
            $seats
        );

        mysqli_stmt_execute($stmt);

        header("Location: bookings.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Ticket</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Book Ticket</h2>

<h3><?php echo $movie['title']; ?></h3>

<p>Price: ₹<?php echo $movie['price']; ?></p>

<?php
if (isset($error)) {
    echo "<p>$error</p>";
}
?>

<form method="POST">

    <label>Number of Seats</label>

    <input
        type="number"
        name="seats"
        min="1"
        value="1"
    >

    <br><br>

    <button name="book">Book Ticket</button>

</form>

<br>

<a href="movies.php">Back to Movies</a>

</body>
</html>