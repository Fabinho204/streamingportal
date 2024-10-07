<?php
session_start();
require_once('../models/movies.class.php'); // Include Movies class

//instance of Movies class
$movies = new Movies();

// Retrieve all movies initially
$moviesList = $movies->getAllMovies();
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Font Awesome Link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>Movie Homepage</title>
</head>

<body>

    <?php require_once('header.php'); ?>

    <div class="container mt-4">
        <div class="row">
            <!-- Filters Section -->
            <div class="col-md-3">
                <h4>Filters</h4>
                <form id="filterForm" method="POST">
                    <div class="form-group">
                        <label for="genreSelect">Genre</label>
                        <select class="form-control" id="genreSelect" name="genre">
                            <option value="All">All</option>
                            <option value="Action">Action</option>
                            <option value="Science Fiction">Science Fiction</option>
                            <option value="Drama">Drama</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Fantasy">Fantasy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn" id="applyFilters">Apply Filters</button>
                </form>
            </div>

            <!-- Movie List Section -->
            <div class="col-md-9">
                <h4>Movies</h4>
                <div class="row" id="movies-container">
                    <?php if (!empty($moviesList)): ?>
                        <!-- Loop through movies and display -->
                        <?php foreach ($moviesList as $movie): ?>
                            <div class="col-md-4">
                                <div class="card movie-card mb-4">
                                    <img src="<?= $movie['posterLink'] ?>" class="card-img-top" alt="<?= $movie['title'] ?>">
                                    <div class="card-body movie-card-body">
                                        <h5 class="card-title"><?= $movie['title'] ?></h5>
                                        <p class="card-text">Rating: <?= $movie['rating'] ?></p>
                                    </div>
                                    <div class="card-footer movie-card-footer">
                                        <button class="btn" data-toggle="modal"
                                            data-target="#movieModal<?= $movie['id'] ?>">
                                            Mehr Anzeigen
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
                                            <a href="<?= $movie['trailer'] ?>" class="btn" target="_blank">Watch
                                                Trailer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No movies found.</p>
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

    <script>
        $(document).ready(function() {
            // When filter form is submitted
            $('#filterForm').on('submit', function(event) {
                event.preventDefault();

                var selectedGenre = $('#genreSelect').val();

                // Send AJAX request to search_filter
                $.ajax({
                    url: "../models/search_filters.php",
                    type: 'POST',
                    data: {
                        genre: selectedGenre
                    },
                    success: function(response) {
                        // Update list dynamically with filtered
                        $('#movies-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>
