<?php
// DB connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'login_db';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a test user
$username = 'admin';
$password_plain = 'password';

// Hash the password
$hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

// Insert the user
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "✅ User created successfully!<br>";
    echo "Username: <strong>$username</strong><br>";
    echo "Password: <strong>$password_plain</strong>";
} else {
    echo "⚠️ Error: " . $stmt->error;
}

$conn->close();
?>
