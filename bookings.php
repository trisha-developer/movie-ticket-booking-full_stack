<?php

session_start();

include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare(
    $conn,
    "SELECT bookings.id, movies.title, movies.price,
            bookings.seats, bookings.booking_date
     FROM bookings
     JOIN movies ON bookings.movie_id = movies.id
     WHERE bookings.user_id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html>

<head>
    <title>My Bookings</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>My Bookings</h2>

<a href="movies.php">Movies</a>
|
<a href="logout.php">Sign Out</a>

<hr>

<?php if (mysqli_num_rows($result) == 0) { ?>

    <p>No tickets booked.</p>

<?php } ?>

<?php while ($booking = mysqli_fetch_assoc($result)) { ?>

    <h3><?php echo $booking['title']; ?></h3>

    <p>
        Seats:
        <?php echo $booking['seats']; ?>
    </p>

    <p>
        Price per ticket:
        ₹<?php echo $booking['price']; ?>
    </p>

    <p>
        Booking Date:
        <?php echo $booking['booking_date']; ?>
    </p>

    <a href="cancel.php?id=<?php echo $booking['id']; ?>">
        Cancel Ticket
    </a>

    <hr>

<?php } ?>

</body>
</html>