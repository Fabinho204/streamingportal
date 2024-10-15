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

if ($watchlist->removeFromWatchlist($userId, $offersId)) {
    echo json_encode(['success' => true, 'message' => 'Movie removed from your watchlist.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to remove the movie from your watchlist.']);
}
