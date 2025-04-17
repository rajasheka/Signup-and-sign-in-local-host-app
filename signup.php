<?php
$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "loginapp";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "Missing username or password"]);
    exit();
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if user already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "fail", "message" => "User already exists"]);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User registered successfully"]);

    // Log to a text file
    $log = date("Y-m-d H:i:s") . " - Signup: $username\n";
    file_put_contents("signup_log.txt", $log, FILE_APPEND);

} else {
    echo json_encode(["status" => "error", "message" => "Failed to register user"]);
}

$stmt->close();
$conn->close();
?>
