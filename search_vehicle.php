<?php 
require 'database.php';
require 'vehicle.php';

// Database and Vehicle initialization
$database = new Database();
$db = $database->connect();
$vehicle = new Vehicle($db);

// Get the search query from the GET request
$searchQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

// If a search query exists, fetch the results
$results = null;
if (!empty($searchQuery)) {
    $results = $vehicle->search($searchQuery);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Vehicle</h1>

        <!-- Search Form -->
        <form method="get" action="search_vehicle.php">
            <input type="text" name="query" placeholder="Search by plate number, model, or owner..." 
                value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Display Results -->
        <?php if (!empty($searchQuery)): ?>
            <h2>Search Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <?php if ($results && $results->rowCount() > 0): ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Plate Number</th>
                        <th>Model</th>
                        <th>Owner</th>
                        <th>Year</th>
                        <th>Insurance Status</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $results->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['model']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['insurance_status']); ?></td>
                            <td>
                                <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No vehicles found matching your search.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
