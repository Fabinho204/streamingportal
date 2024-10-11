<?php
require_once('../models/movies.class.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['movie_id'])) {
    $movie_id = intval($_POST['movie_id']); // Make sure the movie ID is safe

    $movies = new Movies();
    $deleted = $movies->deleteMovie($movie_id);

    if ($deleted) {
        header("Location: ../views/cms_movies_list.php?msg=deleted");
        exit;
    } else {
        echo "Error deleting the movie.";
    }
} else {
    header("Location: ../views/cms_movies_list.php");
    exit;
}
?>
