<?php
session_start();
require_once('../models/db.php'); // Include the db class
require_once('../models/watchlist.class.php'); // Include the Watchlist class
require_once('../models/movies.class.php'); // Include the Movies class

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Create an instance of Watchlist and Movies class
$watchlist = new Watchlist();
$movies = new Movies();

// Get the watchlist for the logged-in user
$watchlistItems = $watchlist->getWatchlist($userId);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Watchlist</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome Link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">

    <!-- Custom Scripts -->
    <script src="../script/script.js"></script>
</head>

<body>

    <?php require_once('header.php'); ?>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h4>Your Watchlist</h4>
            </div>
        </div>

        <div class="row main-content">
            <div class="col-md-12">
                <div class="row" id="movies-container">
                    <?php if (!empty($watchlistItems)): ?>
                        <!-- Loop through watchlist items and display them -->
                        <?php foreach ($watchlistItems as $movie): ?>
                            <div class="col-md-4">
                                <div class="card movie-card mb-4">
                                    <img src="<?= $movie['posterLink'] ?>" class="card-img-top" alt="<?= $movie['title'] ?>">
                                    <div class="card-body movie-card-body">
                                        <h5 class="card-title"><?= $movie['title'] ?></h5>
                                        <p class="card-text">Rating: <?= $movie['rating'] ?></p>
                                    </div>
                                    <div class="card-footer movie-card-footer">
                                        <button class="btn" data-toggle="modal" data-target="#movieModal<?= $movie['id'] ?>">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for displaying additional movie details -->
                            <div class="modal fade" id="movieModal<?= $movie['id'] ?>" tabindex="-1" role="dialog"
                                aria-labelledby="movieModalLabel<?= $movie['id'] ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="movieModalLabel<?= $movie['id'] ?>">
                                                <?= $movie['title'] ?>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Movie Details -->
                                            <p><strong>Original Title:</strong> <?= $movie['originalTitle'] ?></p>
                                            <p><strong>Description:</strong> <?= $movie['description'] ?></p>
                                            <p><strong>FSK:</strong> <?= $movie['fsk'] ?></p>
                                            <p><strong>Available Languages:</strong>
                                                <?php
                                                $languages = $movies->getMovieLanguages($movie['id']);
                                                if (!empty($languages)) {
                                                    $languageNames = [];
                                                    foreach ($languages as $language) {
                                                        $languageNames[] = $language['language'];
                                                    }
                                                    echo implode(', ', $languageNames);
                                                } else {
                                                    echo "No languages available";
                                                }
                                                ?>
                                            </p>
                                            <p><strong>Actors:</strong>
                                                <?php
                                                $actors = $movies->getMovieActors($movie['id']);
                                                if (!empty($actors)) {
                                                    $actorNames = [];
                                                    foreach ($actors as $actor) {
                                                        $actorNames[] = $actor['firstname'] . ' ' . $actor['lastname'];
                                                    }
                                                    echo implode(', ', $actorNames);
                                                } else {
                                                    echo "No actors available";
                                                }
                                                ?>
                                            </p>

                                            <p><strong>Director:</strong>
                                                <?php
                                                $directors = $movies->getMovieDirectors($movie['id']);
                                                foreach ($directors as $director) {
                                                    echo $director['firstname'] . ' ' . $director['lastname'] . ' ';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Your watchlist is empty.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('footer.php'); ?>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.2/dist/sweetalert2.all.min.js"></script>

</body>

</html>