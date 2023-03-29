<?php
class Database
{
    public function getConnect()
    {
        $servername = "localhost";
        $username = "Barley";
        $password = "Csak1enter";
        $dbName = "drop_calories";
        
        try {
          $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
          // set the PDO error mode to exception
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $conn;
        } catch(PDOException $e) {
          echo "Connection failed: " . $e->getMessage();
        }
    }
}
