<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

   
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match'); window.location.href = 'create_account.html';</script>";
        exit;
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $connection = new mysqli('localhost', 'root', '', 'petstore');

    if ($connection->connect_error) {
        echo "<script>alert('Connection failed: " . $connection->connect_error . "');</script>";
        exit;
    }

    $stmt = $connection->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User account created successfully'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Error creating user account: " . $stmt->error . "'); window.location.href = 'create_account.html';</script>";
    }
    $stmt->close();
    $connection->close();
}
?>
