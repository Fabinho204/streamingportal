<?php
session_start();
require_once('../models/watchlist.class.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'You need to be logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];
$offersId = $_POST['offers_id'];

$watchlist = new Watchlist();

// Check if the movie is already in the watchlist
if ($watchlist->isInWatchlist($userId, $offersId)) {
    echo json_encode(['success' => false, 'message' => 'Movie is already in your watchlist.']);
    exit;
}

if ($watchlist->addToWatchlist($userId, $offersId)) {
    echo json_encode(['success' => true, 'message' => 'Movie added to watchlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add the movie to your watchlist.']);
}
