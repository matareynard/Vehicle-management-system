<?php
require 'database.php';
require 'vehicle.php';

$database = new Database();
$db = $database->connect();
$vehicle = new Vehicle($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input and set properties
    $vehicle->plate_number = $_POST['plate_number'];
    $vehicle->model = $_POST['model'];
    $vehicle->owner = $_POST['owner'];
    $vehicle->year = $_POST['year'];
    $vehicle->insurance_status = $_POST['insurance_status'];

    // Attempt to create the new vehicle
    if ($vehicle->create()) {
        echo "Vehicle added successfully!";
        header("Location: index.php");
        exit;
    } else {
        echo "Error adding vehicle.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Add New Vehicle</h1>
    <form method="POST" action="">
        <label for="plate_number">Plate Number:</label>
        <input type="text" id="plate_number" name="plate_number" required>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="owner">Owner:</label>
        <input type="text" id="owner" name="owner" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" min="1900" max="2099" required>

        <label for="insurance_status">Insurance Status:</label>
        <select id="insurance_status" name="insurance_status" required>
            <option value="Active">Active</option>
            <option value="Expired">Expired</option>
        </select>

        <button type="submit">Add Vehicle</button>
    </form>
</body>
</html>
