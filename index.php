<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'dbConfig.php';

// Add Project (Parent)
if (isset($_POST['addProjectBtn'])) {
    $project_name = $_POST['project_name'];
    $client_name = $_POST['client_name'];
    $status = $_POST['status'];
    $added_by = $_SESSION['username'];

    $sql = "INSERT INTO projects (project_name, client_name, status, added_by) 
            VALUES (:project_name, :client_name, :status, :added_by)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':project_name' => $project_name,
        ':client_name' => $client_name,
        ':status' => $status,
        ':added_by' => $added_by
    ]);
    header("Location: index.php");
    exit();
}

// Delete Project
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM projects WHERE project_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    header("Location: index.php");
    exit();
}

// Fetch Projects
$stmt = $pdo->query("SELECT * FROM projects");
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Printing Services</title></head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <a href="logout.php">Logout</a>

    <h2>Add New Project</h2>
    <form action="index.php" method="POST">
        <p>Project Name: <input type="text" name="project_name" required></p>
        <p>Client Name: <input type="text" name="client_name" required></p>
        <p>Status: <input type="text" name="status" placeholder="Pending/Done" required></p>
        <input type="submit" name="addProjectBtn" value="Add Project">
    </form>

    <h2>Projects List</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Project Name</th>
            <th>Client Name</th>
            <th>Status</th>
            <th>Added By</th>
            <th>Last Updated</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($projects as $row): ?>
        <tr>
            <td><?php echo $row['project_id']; ?></td>
            <td><?php echo $row['project_name']; ?></td>
            <td><?php echo $row['client_name']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['added_by']; ?></td>
            <td><?php echo $row['last_updated']; ?></td>
            <td>
                <a href="edit_project.php?id=<?php echo $row['project_id']; ?>">Edit</a> | 
                <a href="index.php?delete_id=<?php echo $row['project_id']; ?>" onclick="return confirm('Delete this project?')">Delete</a> | 
                <a href="layout_items.php?project_id=<?php echo $row['project_id']; ?>">View Items</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>