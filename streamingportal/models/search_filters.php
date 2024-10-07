<?php
require_once('movies.class.php');

$movies = new Movies();

$selectedGenre = 'All';

if (isset($_POST['genre']) && $_POST['genre'] != 'All') {
    $selectedGenre = $_POST['genre'];
}

$moviesList = $movies->getMoviesByGenre($selectedGenre);

if (!empty($moviesList)) {
    foreach ($moviesList as $movie) {
        ?>
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
                        <p><strong>Release Year:</strong> <?= $movie['releaseYear'] ?></p>
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
                            if (!empty($directors)) {
                                foreach ($directors as $director) {
                                    echo $director['firstname'] . ' ' . $director['lastname'] . ' ';
                                }
                            } else {
                                echo "No directors available";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal">Close</button>
                        <a href="<?= $movie['trailer'] ?>" class="btn" target="_blank">Watch Trailer</a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<p>No movies found for the selected genre.</p>';
}
?>
