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
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Vehicle List</h1>
        <a href="create.php" class="add-button">Add New Vehicle</a>
        <?php if ($result->rowCount() === 0): ?>
            <p>No vehicles found. <a href="create.php">Add your first vehicle</a>.</p>
        <?php else: ?>
            <table>
                <caption>List of registered vehicles</caption>
                <tr>
                    <th>#</th>
                    <th>Plate Number</th>
                    <th>Model</th>
                    <th>Owner</th>
                    <th>Year</th>
                    <th>Insurance Status</th>
                    <th>Actions</th>
                </tr>
                <?php 
                $rowNumber = $offset + 1; // Start row number based on the current page
                while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?php echo $rowNumber++; ?></td> <!-- Sequential row number -->
                        <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['model']); ?></td>
                        <td><?php echo htmlspecialchars($row['owner']); ?></td>
                        <td><?php echo htmlspecialchars($row['year']); ?></td>
                        <td><?php echo htmlspecialchars($row['insurance_status']); ?></td>
                        <td class="actions">
                            <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
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
