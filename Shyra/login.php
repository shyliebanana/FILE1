<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $connection = new mysqli('localhost', 'root', '', 'petstore');

    
    if ($connection->connect_error) {
        echo "<script>alert('Connection failed: " . $connection->connect_error . "');</script>";
    } else {
       
        $stmt = $connection->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

      
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_username, $db_password, $role);
            $stmt->fetch();

           
            if (password_verify($password, $db_password)) {
                
                $_SESSION['username'] = $db_username;
                $_SESSION['role'] = $role;

               
                if ($role == 'admin') {
                    header("Location: Admin.html");
                } else {
                    header("Location: user.html"); 
                }
                exit;
            } else {
                echo "<script>alert('Invalid password');</script>";
            }
        } else {
            echo "<script>alert('No user found with that username');</script>";
        }

        $stmt->close();
        $connection->close();
    }
}
