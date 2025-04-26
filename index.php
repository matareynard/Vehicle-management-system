<?php
require 'database.php';
require 'vehicle.php';

$database = new Database();
$db = $database->connect();
$vehicle = new Vehicle($db);

$limit = 10; // Rows per page
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$offset = ($page - 1) * $limit;

$result = $vehicle->read($limit, $offset);
$totalRecords = $db->query("SELECT COUNT(*) FROM vehicles")->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Vehicle Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .add-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }

        .search-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }

        .add-button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
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

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            background-color: #007bff;
            color: #ffffff;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #0056b3;
        }

        .pagination a.active {
            background-color: #0056b3;
            font-weight: bold;
        }

        .actions a {
            margin-right: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .delete {
            color: #dc3545;
        }

        .delete:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Vehicle List</h1>
        <a href="search_vehicle.php" class="search-button">search</a>
        <a href="add_vehicle.php" class="add-button">Add New Vehicle</a>
        <?php if ($result->rowCount() === 0): ?>
            <p>No vehicles found. <a href="add_vehicle.php">Add your first vehicle</a>.</p>
        <?php else: ?>
            <table>
                <!-- <caption>List of registered vehicles</caption> -->
                <tr>
                    <th>#</th> <!-- Sequential row number -->
                    <th>Plate Number</th>
                    <th>Model</th>
                    <th>Owner</th>
                    <th>Year</th>
                    <th>Insurance Status</th>
                    <th>Actions</th>
                </tr>
                <?php
                $rowNumber = $offset + 1; // Calculate the sequential number
                while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $rowNumber++; ?></td> <!-- Display sequential row number -->
                        <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['model']); ?></td>
                        <td><?php echo htmlspecialchars($row['owner']); ?></td>
                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                        <td><?php echo htmlspecialchars($row['insurance_status']); ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this vehicle?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>