<?php
require_once('../models/db.php');

class Watchlist extends db {

    public function __construct() {
        parent::__construct();
    }

    // Add a movie to the watchlist
    public function addToWatchlist($userId, $offersId) {
        $query = "INSERT INTO watchlists (user_id, offers_id) VALUES (?, ?)";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bind_param("ii", $userId, $offersId);
        return $stmt->execute();
    }

    // Remove a movie from the watchlist
    public function removeFromWatchlist($userId, $offersId) {
        $query = "DELETE FROM watchlists WHERE user_id = ? AND offers_id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bind_param("ii", $userId, $offersId);
        return $stmt->execute();
    }

    // Check if a movie is in the watchlist
    public function isInWatchlist($userId, $offersId) {
        $query = "SELECT * FROM watchlists WHERE user_id = ? AND offers_id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bind_param("ii", $userId, $offersId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function getWatchlist($userId) {
        $query = "SELECT o.id, o.title, o.posterLink, o.rating, o.originalTitle, o.description, o.fsk, o.trailer
                  FROM watchlists w
                  INNER JOIN offers o ON w.offers_id = o.id
                  WHERE w.user_id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }   
}

