<?php
require 'database.php';
require 'vehicle.php';

$database = new Database();
$db = $database->connect();
$vehicle = new Vehicle($db);

// Get the vehicle ID from the URL
if (isset($_GET['id'])) {
    $vehicle->id = $_GET['id'];
    $vehicleData = $vehicle->readSingle();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the vehicle's properties
    $vehicle->id = $_POST['id'];
    $vehicle->plate_number = $_POST['plate_number'];
    $vehicle->model = $_POST['model'];
    $vehicle->owner = $_POST['owner'];
    $vehicle->year = $_POST['year'];
    $vehicle->insurance_status = $_POST['insurance_status'];

    // Attempt to update the vehicle
    if ($vehicle->update()) {
        echo "Vehicle updated successfully!";
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating vehicle.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vehicle</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 500px; margin: auto; padding: 20px; background: #f4f4f4; }
        input, select { width: 100%; margin: 10px 0; padding: 10px; }
        button { background: #007bff; color: #fff; padding: 10px; border: none; }
    </style>
</head>
<body>
    <h1>Edit Vehicle</h1>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $vehicleData['id']; ?>" />
        <label for="plate_number">Plate Number:</label>
        <input type="text" name="plate_number" id="plate_number" value="<?php echo $vehicleData['plate_number']; ?>" required>

        <label for="model">Model:</label>
        <input type="text" name="model" id="model" value="<?php echo $vehicleData['model']; ?>" required>

        <label for="owner">Owner:</label>
        <input type="text" name="owner" id="owner" value="<?php echo $vehicleData['owner']; ?>" required>

        <label for="year">Year:</label>
        <input type="number" name="year" id="year" value="<?php echo $vehicleData['year']; ?>" required>

        <label for="insurance_status">Insurance Status:</label>
        <select name="insurance_status" id="insurance_status" required>
            <option value="Active" <?php echo $vehicleData['insurance_status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
            <option value="Expired" <?php echo $vehicleData['insurance_status'] == 'Expired' ? 'selected' : ''; ?>>Expired</option>
        </select>

        <button type="submit">Update Vehicle</button>
    </form>
</body>
</html>
