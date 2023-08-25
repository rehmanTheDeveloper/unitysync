<?php

class DatabaseHandler {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die('Connection failed: ' . $this->conn->connect_error);
        }
    }

    // Method to check if a username exists in the database
    public function isUsernameExists($table, $username) {
        $username = $this->conn->real_escape_string($username);
        $query = "SELECT * FROM `".$table."` WHERE username = '$username'";
        $result = $this->conn->query($query);
        return ($result->num_rows > 0);
    }

    // public function isUsernameExists($table, $username) {
    //     $username = $this->conn->real_escape_string($username);
    //     $query = "SELECT * FROM `".$table."` WHERE username = '$username'";
    //     $result = $this->conn->query($query);
    //     if ($result && $result->num_rows > 0) {
    //         return $result->fetch_assoc();
    //     }
    // }

    // Method to check if a password exists in the database
    public function isPasswordExists($table, $password) {
        $password = $this->conn->real_escape_string($password);
        $query = "SELECT * FROM `".$table."` WHERE password = '$password'";
        $result = $this->conn->query($query);
        return ($result->num_rows > 0);
    }

    // Add more methods as needed, e.g., methods for adding users, etc.

    public function closeConnection() {
        $this->conn->close();
    }
}