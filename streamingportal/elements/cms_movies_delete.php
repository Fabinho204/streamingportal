<?php
session_start();
require_once('../models/movies.class.php'); // Include the Movies class

$movies = new Movies();
$movie = null; // Initialize movie variable

// Check if the user has submitted the form to search for a movie by title
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchTitle'])) {
    $movieTitle = strtolower($_POST['searchTitle']);

    // Fetch the movie details using the provided title
    $movie = $movies->searchMovieByTitle($movieTitle);

    if (!$movie) {
        echo "<script>alert('Movie not found. Please try again.');</script>";
    }
}

// Handle the delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmDelete'])) {
    $movieTitle = strtolower($_POST['movieTitle']); // The movie title from the hidden field

    // Delete the movie from the database
    $deleteSuccess = $movies->deleteMovieByTitle($movieTitle);

    if ($deleteSuccess) {
        echo "<script>alert('Movie successfully deleted.');</script>";
        // Redirect to the movie list after deletion
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


</head>
<body>
    <div class="container mt-4">
        <h2>Delete a Movie</h2>

        <!-- Movie Search Form -->
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
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
