<?php
session_start();
require_once('../models/db.php');
require_once('../models/watchlist.class.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You need to be logged in to remove an item.']);
    exit;
}

$userId = $_SESSION['user_id'];
$offersId = $_POST['offers_id'];

// Instantiate the Watchlist class
$watchlist = new Watchlist();

// Remove the movie from the watchlist
if ($watchlist->removeFromWatchlist($userId, $offersId)) {
    echo json_encode(['success' => true, 'message' => 'Movie removed from your watchlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove the movie from your watchlist.']);
}
