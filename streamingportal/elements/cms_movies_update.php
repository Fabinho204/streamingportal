<?php
session_start();
require_once('../models/movies.class.php'); // Include the Movies class

$movies = new Movies();

// Check if the user has submitted the form to search for a movie by title
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchTitle'])) {
    $movieTitle = strtolower($_POST['searchTitle']);

    // Fetch the movie details using the provided title
    $movie = $movies->searchMovieByTitle($movieTitle);

    if (!$movie) {
        echo "<script>alert('Movie not found. Please try again.');</script>";
    }
} 

// Handle the update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    // Get the updated movie details from the form submission
    $originalTitle = strtolower($_POST['originalTitle']); // Get the original title from the hidden field
    $newTitle = strtolower($_POST['title']); // This is the new title (can be changed)
    $trailer = $_POST['trailer'];
    $fsk = (int)$_POST['fsk'];
    $posterLink = $_POST['posterLink'];
    $description = $_POST['description'];
    $rating = (float)$_POST['rating'];

    // Update the movie in the database
    $updateSuccess = $movies->updateMovieByTitle($originalTitle, $newTitle, $trailer, $fsk, $posterLink, $description, $rating);

    if ($updateSuccess) {
        // Redirect to cms_movies_list.php if the update was successful
        header('Location: ../views/cms_movies_list.php');
        exit(); // Make sure to exit after the redirection to prevent further code execution
    } else {
        echo "<script>alert('Failed to update the movie. Please try again.');</script>";
    }
}

// If no search has been done, or no valid movie was found, show the search form
if (!isset($movie)) {
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Movie to Update</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Type in the Title of the Movie you want to update</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="searchTitle">Movie Title</label>
                <input type="text" class="form-control" id="searchTitle" name="searchTitle" required>
            </div>
            <button type="submit" class="btn">Search Movie</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    exit();
} 
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Movie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Update Movie: <?= $movie['title'] ?></h2>

        <form method="POST" action="">
            <!-- Hidden field to keep track of the original title -->
            <input type="hidden" name="originalTitle" value="<?= $movie['title'] ?>">

            <div class="form-group">
                <label for="title">Movie Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= $movie['title'] ?>" required>
            </div>
            <div class="form-group">
                <label for="trailer">Trailer Link</label>
                <input type="url" class="form-control" id="trailer" name="trailer" value="<?= $movie['trailer'] ?>">
            </div>
            <div class="form-group">
                <label for="fsk">FSK (Age Rating)</label>
                <input type="number" class="form-control" id="fsk" name="fsk" value="<?= $movie['fsk'] ?>" required>
            </div>
            <div class="form-group">
                <label for="posterLink">Poster Link</label>
                <input type="url" class="form-control" id="posterLink" name="posterLink" value="<?= $movie['posterLink'] ?>">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?= $movie['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" step="0.01" min="0" max="10" value="<?= $movie['rating'] ?>" required>
            </div>
            <button type="submit" class="btn">Update Movie</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
