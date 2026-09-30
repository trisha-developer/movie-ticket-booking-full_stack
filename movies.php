<?php

session_start();

include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM movies");

?>

<!DOCTYPE html>
<html>

<head>
    <title>Movies - CineBook</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h2>Welcome <?php echo $_SESSION['user_name']; ?> 👋</h2>

<a href="bookings.php">My Bookings</a>
|
<a href="logout.php">Sign Out</a>

<hr>

<h2>Movies</h2>

<?php while ($movie = mysqli_fetch_assoc($result)) { ?>

    <h3><?php echo $movie['title']; ?></h3>

    <p>Ticket Price: ₹<?php echo $movie['price']; ?></p>

    <a href="book_movie.php?id=<?php echo $movie['id']; ?>">
        Book Ticket
    </a>

    <hr>

<?php } ?>

</body>
</html>