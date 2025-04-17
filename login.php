<?php
header("Content-Type: application/json");

// 1. Database credentials
$host = "localhost";
$user = "root"; // default for XAMPP
$pass = "";     // default for XAMPP
$db = "loginapp"; // Your database name

// 2. Create connection
$conn = new mysqli($host, $user, $pass, $db);

// 3. Check connection
if ($conn->connect_error) {
    echo json_encode(["status" => "fail", "error" => "Database connection failed"]);
    exit();
}

// 4. Get POST data
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// 5. Validate inputs
if (empty($username) || empty($password)) {
    echo json_encode(["status" => "fail", "error" => "Missing username or password"]);
    exit();
}

// 6. Escape to prevent SQL injection (basic)
$username = $conn->real_escape_string($username);
$password = $conn->real_escape_string($password);

// 7. Query database
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

// 8. Check result
if ($result && $result->num_rows === 1) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "fail"]);
}

// 9. Close connection
$conn->close();
?>
