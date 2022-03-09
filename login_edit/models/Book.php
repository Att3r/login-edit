<?php

require_once './libraries/Database.php';

class Book
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getBooks()
    {
        $this->db->query('SELECT 
                            tb.id, tb.book_name, tb.book_author, 
                            COALESCE(AVG(rb.stars), 0) AS rating
                        FROM top_books AS tb
                        LEFT JOIN rates_books AS rb ON tb.id = rb.book_id
                        GROUP BY tb.id');
        $row = $this->db->resultSet();
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function getRatedUserBooksIds($usermail)
    { // username on userEmail tegelikkuses
        $this->db->query('SELECT book_id FROM rates_books WHERE username=:username GROUP BY book_id');
        $this->db->bind(':username', $usermail);
        $row = $this->db->resultSet();
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function findRatedBookByIdAndEmail($book_id, $email)
    {
        $this->db->query('SELECT * FROM rates_books WHERE username = :username AND book_id = :book_id');
        $this->db->bind(':username', $email);
        $this->db->bind(':book_id', $book_id);
        $row = $this->db->single();
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function addUserNewRating($book_id, $rating, $email)
    {
        $this->db->query('INSERT INTO rates_books (book_id, username, stars, added) VALUES (:book_id, :username, :stars, NOW())');
        $this->db->bind(':book_id', $book_id);
        $this->db->bind(':username', $email);
        $this->db->bind(':stars', $rating);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUserRating($id, $stars) {
        $this->db->query('UPDATE rates_books SET stars = :stars WHERE id = :id');
        $this->db->bind(':stars', $stars);
        $this->db->bind(':id', $id);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
