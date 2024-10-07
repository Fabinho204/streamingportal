<?php
session_start();
require_once('../models/movies.class.php'); // Include the Movies class

$movies = new Movies();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $title = $_POST['title'];
    $trailer = $_POST['trailer'];
    $fsk = $_POST['fsk'];
    $posterLink = $_POST['posterLink'];
    $originalTitle = $_POST['originalTitle'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];

    $result = $movies->addMovie($title, $trailer, $fsk, $posterLink, $originalTitle, $description, $rating);

    if ($result) {
        echo "<script>alert('Movie added successfully!');</script>";
        header('Location: ../views/cms_movies_list.php'); // Redirect back to movie list after successful submission
        exit();
    } else {
        echo "<script>alert('Error adding movie. Please try again.');</script>";
    }
}
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Add Movie</title>
</head>

<body>
    <div class="container mt-4">
        <h2>Add New Movie</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="title">Movie Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="trailer">Trailer Link</label>
                <input type="url" class="form-control" id="trailer" name="trailer">
            </div>
            <div class="form-group">
                <label for="fsk">FSK (Age Rating)</label>
                <input type="number" class="form-control" id="fsk" name="fsk" min="0" max="18" required>
            </div>
            <div class="form-group">
                <label for="posterLink">Poster Link</label>
                <input type="url" class="form-control" id="posterLink" name="posterLink">
            </div>
            <div class="form-group">
                <label for="originalTitle">Original Title</label>
                <input type="text" class="form-control" id="originalTitle" name="originalTitle" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="rating">Rating</label>
                <input type="number" class="form-control" id="rating" name="rating" step="0.01" min="0" max="10" required>
            </div>
            <button type="submit" class="btn">Add Movie</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>
