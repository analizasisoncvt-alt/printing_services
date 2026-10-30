<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'dbConfig.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = :id");
$stmt->execute([':id' => $id]);
$project = $stmt->fetch();

if (isset($_POST['updateProjectBtn'])) {
    $project_name = $_POST['project_name'];
    $client_name = $_POST['client_name'];
    $status = $_POST['status'];
    $added_by = $_SESSION['username'];

    $sql = "UPDATE projects SET project_name = :project_name, client_name = :client_name, status = :status, added_by = :added_by WHERE project_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':project_name' => $project_name,
        ':client_name' => $client_name,
        ':status' => $status,
        ':added_by' => $added_by,
        ':id' => $id
    ]);
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head><title>Edit Project</title></head>
<body>
    <h2>Edit Project</h2>
    <form action="edit_project.php?id=<?php echo $id; ?>" method="POST">
        <p>Project Name: <input type="text" name="project_name" value="<?php echo $project['project_name']; ?>" required></p>
        <p>Client Name: <input type="text" name="client_name" value="<?php echo $project['client_name']; ?>" required></p>
        <p>Status: <input type="text" name="status" value="<?php echo $project['status']; ?>" required></p>
        <input type="submit" name="updateProjectBtn" value="Update Project">
    </form>
    <p><a href="index.php">Cancel</a></p>
</body>
</html>