<?php
class Vehicle {
    private $conn;
    private $table;

    public $id;
    public $plate_number;
    public $model;
    public $owner;
    public $year;
    public $insurance_status;

    public function __construct($db, $table = "vehicles") {
        $this->conn = $db;
        $this->table = $table;
    }

    // Create a new vehicle record
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  (plate_number, model, owner, year, insurance_status) 
                  VALUES (:plate_number, :model, :owner, :year, :insurance_status)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':plate_number', $this->plate_number);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':owner', $this->owner);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':insurance_status', $this->insurance_status);

        if ($stmt->execute()) {
            return true;
        }

        // Log error
        error_log("Create Error: " . $stmt->errorInfo()[2]);
        return false;
    }

    public function read($limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return $stmt; 
        } else {
            return null; 
        }
    }
    

    // Read a single vehicle record
    public function readSingle() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        error_log("Read Single Error: " . $stmt->errorInfo()[2]);
        return false;
    }

    // Update a vehicle record
    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET plate_number = :plate_number, model = :model, owner = :owner, 
                      year = :year, insurance_status = :insurance_status 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':plate_number', $this->plate_number);
        $stmt->bindParam(':model', $this->model);
        $stmt->bindParam(':owner', $this->owner);
        $stmt->bindParam(':year', $this->year);
        $stmt->bindParam(':insurance_status', $this->insurance_status);

        if ($stmt->execute()) {
            return true;
        }

        error_log("Update Error: " . $stmt->errorInfo()[2]);
        return false;
    }

    // Delete a vehicle record
    public function delete() {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }

        error_log("Delete Error: " . $stmt->errorInfo()[2]);
        return false;
    }

      // Search method
      public function search($query) {
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE plate_number LIKE :query 
                   OR model LIKE :query 
                   OR owner LIKE :query";

        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%" . $query . "%";
        $stmt->bindParam(':query', $searchTerm);
        $stmt->execute();

        return $stmt;
    }
}
?>
