<?php
require_once('../models/db.php');

class Watchlist extends db {

    public function __construct() {
        parent::__construct(); // Call the parent constructor to set up the database connection
    }

    // Fetch the watchlist for a specific user
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
    

    // Add a movie to the watchlist
    public function addToWatchlist($userId, $offersId) {
        // Check if the movie is already in the watchlist
        $checkQuery = "SELECT * FROM watchlists WHERE user_id = ? AND offers_id = ?";
        $checkStmt = $this->getConnection()->prepare($checkQuery);
        $checkStmt->bind_param("ii", $userId, $offersId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows == 0) {
            // Insert into the watchlist if not already there
            $query = "INSERT INTO watchlists (user_id, offers_id) VALUES (?, ?)";
            $stmt = $this->getConnection()->prepare($query);
            $stmt->bind_param("ii", $userId, $offersId);
            $stmt->execute();
            return true;
        }

        return false; // Movie already in watchlist
    }
    public function removeFromWatchlist($userId, $offersId) {
        $query = "DELETE FROM watchlists WHERE user_id = ? AND offers_id = ?";
        $stmt = $this->getConnection()->prepare($query);
        $stmt->bind_param("ii", $userId, $offersId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }
    
}
