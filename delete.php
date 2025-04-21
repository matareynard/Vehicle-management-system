<?php
require 'database.php';
require 'vehicle.php';

$database = new Database();
$db = $database->connect();
$vehicle = new Vehicle($db);

// Get the vehicle ID from the URL
if (isset($_GET['id'])) {
    $vehicle->id = $_GET['id'];

    // Attempt to delete the vehicle
    if ($vehicle->delete()) {
        echo "Vehicle deleted successfully!";
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting vehicle.";
    }
} else {
    echo "No vehicle ID provided.";
}
?>
