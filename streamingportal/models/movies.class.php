<?php
require_once 'db.php';

class Movies extends db {
    
    public function getAllMovies() {
        $sql = "SELECT o.id, o.title, o.posterLink, o.fsk, o.rating, o.description, o.trailer, o.originalTitle, m.releaseYear
                FROM offers o
                LEFT JOIN movie m ON o.id = m.offers_id
                ORDER BY o.id DESC";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Fetch rows
        } else {
            return [];
        }
    }
    
    public function searchMovies($query) {
        $query = $this->conn->real_escape_string($query);
        $sql = "SELECT o.id, o.title, o.posterLink, o.fsk, o.rating, o.description, o.trailer, o.originalTitle, m.releaseYear
                FROM offers o
                LEFT JOIN movie m ON o.id = m.offers_id
                WHERE o.title LIKE '%$query%' OR o.description LIKE '%$query%'";
        $result = $this->conn->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }
    

    public function getMovieLanguages($movieId) {
        $sql = "SELECT l.language FROM hasSubs hs
                JOIN languages l ON hs.languages_id = l.id
                WHERE hs.offers_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getMovieActors($movieId) {
        $sql = "SELECT f.firstname, f.lastname FROM actors a
                JOIN filmIndustryProfessional f ON a.filmIndustryProfessional_id = f.id
                WHERE a.movies_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getMovieDirectors($movieId) {
        $sql = "SELECT f.firstname, f.lastname FROM directors d
                JOIN filmIndustryProfessional f ON d.filmIndustryProfessional_id = f.id
                WHERE d.movies_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getMoviesByGenre($genre) {
        // If genre 'All' get all movies
        if ($genre === 'All') {
            $sql = "SELECT DISTINCT o.id, o.title, o.posterLink, o.fsk, o.rating, o.description, o.trailer, o.originalTitle, m.releaseYear 
                    FROM offers o 
                    LEFT JOIN movie m ON o.id = m.offers_id";
        } else {
            // Filter by specific genre
            $sql = "SELECT DISTINCT o.id, o.title, o.posterLink, o.fsk, o.rating, o.description, o.trailer, o.originalTitle, m.releaseYear 
                    FROM offers o 
                    LEFT JOIN movie m ON o.id = m.offers_id
                    LEFT JOIN offersHasGenres ohg ON o.id = ohg.offers_id
                    LEFT JOIN genres g ON ohg.genres_id = g.id
                    WHERE g.name = ?";
        }
    
        $stmt = $this->conn->prepare($sql);
    
        if ($genre !== 'All') {
            $stmt->bind_param("s", $genre);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
    public function addMovie($title, $trailer, $fsk, $posterLink, $originalTitle, $description, $rating) {
        $sql = "INSERT INTO offers (title, trailer, fsk, posterLink, originalTitle, description, rating) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssisssd", $title, $trailer, $fsk, $posterLink, $originalTitle, $description, $rating);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function searchMovieByTitle($title) {
        $title = $this->conn->real_escape_string($title); // Escape input for safety
        $sql = "SELECT * FROM offers WHERE LOWER(title) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $title);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the first matching row
        } else {
            return false;
        }
    }    
    
    public function updateMovieByTitle($originalTitle, $newTitle, $trailer, $fsk, $posterLink, $description, $rating) {
        $sql = "UPDATE offers SET title = ?, trailer = ?, fsk = ?, posterLink = ?, description = ?, rating = ?
                WHERE LOWER(title) = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssissds", $newTitle, $trailer, $fsk, $posterLink, $description, $rating, $originalTitle);
        
        return $stmt->execute();
    }
    
    public function deleteMovieByTitle($title) {
        $title = $this->conn->real_escape_string($title); // Escape input for safety
        $sql = "DELETE FROM offers WHERE LOWER(title) = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $title);
        
        return $stmt->execute();
    }
    
}
?>
