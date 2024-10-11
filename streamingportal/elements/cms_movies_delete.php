<?php
session_start();
require_once('../models/movies.class.php');

$movies = new Movies();
$movie = null; // Initialize movie variable
$showError = false; // To control whether to show the error message or not

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchTitle'])) {
    $movieTitle = strtolower($_POST['searchTitle']);

    // Search for the movie
    $movie = $movies->searchMovieByTitle($movieTitle);

    if (!$movie) {
        // If the movie is not found, show the error
        $showError = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmDelete'])) {
    $movieTitle = strtolower($_POST['movieTitle']);

    // Attempt to delete the movie
    $deleteSuccess = $movies->deleteMovieByTitle($movieTitle);

    if ($deleteSuccess) {
        echo "<script>alert('Movie successfully deleted.');</script>";
        header('Location: ../views/cms_movies_list.php');
        exit();
    } else {
        echo "<script>alert('Failed to delete the movie. Please try again.');</script>";
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Movie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/script.js"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php require_once('../views/header.php'); ?>
    <div class="container mt-4">
        <h2>Delete a Movie</h2>

        <!-- Search Form -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="searchTitle">Movie Title</label>
                <input type="text" class="form-control" id="searchTitle" name="searchTitle" required>
            </div>
            <button type="submit" class="btn">Search Movie</button>
        </form>

        <?php if ($movie): ?>
            <!-- If movie is found, show its details and the delete confirmation -->
            <hr>
            <h4>Movie Details</h4>
            <p><strong>Title:</strong> <?= $movie['title'] ?></p>
            <p><strong>Original Title:</strong> <?= $movie['originalTitle'] ?></p>
            <p><strong>Description:</strong> <?= $movie['description'] ?></p>
            <p><strong>FSK:</strong> <?= $movie['fsk'] ?></p>
            <p><strong>Rating:</strong> <?= $movie['rating'] ?></p>

            <!-- Delete confirmation button using SweetAlert -->
            <button class="btn" onclick="confirmDelete('<?= $movie['title'] ?>')">Delete Movie</button>

            <!-- Hidden form that will be submitted by SweetAlert -->
            <form id="deleteForm" method="POST" action="">
                <input type="hidden" name="movieTitle" value="<?= $movie['title'] ?>">
                <input type="hidden" name="confirmDelete" value="1">
            </form>
        <?php elseif ($showError): ?>
            <!-- Show error message if the movie was not found -->
            <div class="alert alert-danger mt-3">
                Movie not found. Please try searching again.
            </div>
            <a class="btn" href="cms_movies_delete.php">Try Again</a>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
