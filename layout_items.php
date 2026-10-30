<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'dbConfig.php';

if (!isset($_GET['project_id']) || empty($_GET['project_id'])) {
    header("Location: index.php");
    exit();
}

$project_id = $_GET['project_id'];

// Fetch Project Info
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :id");
$stmt->execute([':id' => $project_id]);
$project = $stmt->fetch();

if (!$project) {
    header("Location: index.php");
    exit();
}

// Add Item
if (isset($_POST['addItemBtn'])) {
    $item_type = $_POST['item_type'];
    $price = $_POST['price'];
    $added_by = $_SESSION['username'];

    $sql = "INSERT INTO layout_items (project_id, item_type, price, added_by) VALUES (:project_id, :item_type, :price, :added_by)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':project_id' => $project_id,
        ':item_type' => $item_type,
        ':price' => $price,
        ':added_by' => $added_by
    ]);
    header("Location: layout_items.php?project_id=" . $project_id);
    exit();
}

// Edit Item Logic
$edit_item = null;
if (isset($_GET['edit_item_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM layout_items WHERE item_id = :id");
    $stmt->execute([':id' => $_GET['edit_item_id']]);
    $edit_item = $stmt->fetch();
}

// Update Item
if (isset($_POST['updateItemBtn'])) {
    $item_id = $_POST['item_id'];
    $item_type = $_POST['item_type'];
    $price = $_POST['price'];
    $added_by = $_SESSION['username'];

    $sql = "UPDATE layout_items SET item_type = :item_type, price = :price, added_by = :added_by WHERE item_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':item_type' => $item_type,
        ':price' => $price,
        ':added_by' => $added_by,
        ':id' => $item_id
    ]);
    header("Location: layout_items.php?project_id=" . $project_id);
    exit();
}

// Delete Item
if (isset($_GET['delete_item_id'])) {
    $item_id = $_GET['delete_item_id'];
    $sql = "DELETE FROM layout_items WHERE item_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $item_id]);
    header("Location: layout_items.php?project_id=" . $project_id);
    exit();
}

// Fetch Items
$stmt = $pdo->prepare("SELECT * FROM layout_items WHERE project_id = :id");
$stmt->execute([':id' => $project_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Layout Items</title></head>
<body>
    <h1>Layout Items for Project: <?php echo htmlspecialchars($project['project_name']); ?></h1>
    <a href="index.php">Back to Projects</a>

    <?php if ($edit_item): ?>
    <h2>Edit Layout Item</h2>
    <form action="layout_items.php?project_id=<?php echo $project_id; ?>" method="POST">
        <input type="hidden" name="item_id" value="<?php echo $edit_item['item_id']; ?>">
        <p>Item Type: <input type="text" name="item_type" value="<?php echo htmlspecialchars($edit_item['item_type']); ?>" required></p>
        <p>Price: <input type="number" step="0.01" name="price" value="<?php echo $edit_item['price']; ?>" required></p>
        <input type="submit" name="updateItemBtn" value="Update Item">
        <a href="layout_items.php?project_id=<?php echo $project_id; ?>">Cancel</a>
    </form>
    <?php else: ?>
    <h2>Add Layout Item</h2>
    <form action="layout_items.php?project_id=<?php echo $project_id; ?>" method="POST">
        <p>Item Type: <input type="text" name="item_type" placeholder="e.g., Tarpaulin, Logo" required></p>
        <p>Price: <input type="number" step="0.01" name="price" required></p>
        <input type="submit" name="addItemBtn" value="Add Item">
    </form>
    <?php endif; ?>

    <h2>Items List</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Item ID</th>
            <th>Item Type</th>
            <th>Price</th>
            <th>Added By</th>
            <th>Last Updated</th>
            <th>Action</th>
        </tr>
        <?php foreach ($items as $row): ?>
        <tr>
            <td><?php echo $row['item_id']; ?></td>
            <td><?php echo htmlspecialchars($row['item_type']); ?></td>
            <td><?php echo $row['price']; ?></td>
            <td><?php echo htmlspecialchars($row['added_by']); ?></td>
            <td><?php echo $row['last_updated']; ?></td>
            <td>
                <a href="layout_items.php?project_id=<?php echo $project_id; ?>&edit_item_id=<?php echo $row['item_id']; ?>">Edit</a> | 
                <a href="layout_items.php?project_id=<?php echo $project_id; ?>&delete_item_id=<?php echo $row['item_id']; ?>" onclick="return confirm('Delete item?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>