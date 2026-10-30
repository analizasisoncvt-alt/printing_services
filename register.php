<?php
require_once 'dbConfig.php';

if (isset($_POST['registerBtn'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (first_name, last_name, address, age, username, password) 
            VALUES (:first_name, :last_name, :address, :age, :username, :password)";
    
    $stmt = $pdo->prepare($sql);
    $executeQuery = $stmt->execute([
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':address' => $address,
        ':age' => $age,
        ':username' => $username,
        ':password' => $password
    ]);

    if ($executeQuery) {
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
    <h2>User Registration</h2>
    <form action="register.php" method="POST">
        <p>First Name: <input type="text" name="first_name" required></p>
        <p>Last Name: <input type="text" name="last_name" required></p>
        <p>Address: <input type="text" name="address" required></p>
        <p>Age: <input type="number" name="age" required></p>
        <p>Username: <input type="text" name="username" required></p>
        <p>Password: <input type="password" name="password" required></p>
        <input type="submit" name="registerBtn" value="Register">
    </form>
    <p>May account na? <a href="login.php">Login dito</a></p>
</body>
</html>