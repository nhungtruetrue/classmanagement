<?php
require_once '../connect.php'; // đảm bảo kết nối đến DB

// Kiểm tra nếu có dữ liệu POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ request
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $address = $_POST['address'] ?? '';
    $department_id = $_POST['department_id'] ?? 'NULL';
    $position = $_POST['position'] ?? '';
    $degree = $_POST['degree'] ?? '';
    $hire_date = $_POST['hire_date'] ?? '';
    $status = $_POST['status'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // Escape dữ liệu để tránh SQL Injection (nếu không dùng prepared statement)
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $date_of_birth = mysqli_real_escape_string($conn, $date_of_birth);
    $gender = mysqli_real_escape_string($conn, $gender);
    $address = mysqli_real_escape_string($conn, $address);
    $position = mysqli_real_escape_string($conn, $position);
    $degree = mysqli_real_escape_string($conn, $degree);
    $hire_date = mysqli_real_escape_string($conn, $hire_date);
    $status = mysqli_real_escape_string($conn, $status);
    $bio = mysqli_real_escape_string($conn, $bio);

    // Chuẩn bị truy vấn
    $sql = "INSERT INTO teachers (name, email, phone, date_of_birth, gender, address, department_id, position, degree, hire_date, status, bio)
            VALUES ('$name', '$email', '$phone', '$date_of_birth', '$gender', '$address', $department_id, '$position', '$degree', '$hire_date', '$status', '$bio')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
