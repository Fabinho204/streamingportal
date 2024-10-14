<?php
session_start();
require_once('../models/watchlist.class.php'); // Load Watchlist class

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You need to be logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$offersId = $_POST['offers_id'];

// Instantiate the Watchlist class (which extends db)
$watchlist = new Watchlist();

// Add the movie to the watchlist
if ($watchlist->addToWatchlist($userId, $offersId)) {
    echo json_encode(['success' => true, 'message' => 'Movie added to watchlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Movie is already in your watchlist.']);
}
