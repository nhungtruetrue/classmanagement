<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $code= isset($_POST['code'])?trim($_POST['code']):'';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $dob = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $year = isset($_POST['enrollment_year']) ? $_POST['enrollment_year'] : '';
    $major_id = isset($_POST['major_id']) ? $_POST['major_id'] : '';
    $national_id = isset($_POST['national_id']) ? trim($_POST['national_id']) : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';


    $sql = "INSERT INTO students 
        (code, name, email, phone, date_of_birth, gender, address, enrollment_year, major_id, national_id, status) 
        VALUES (
            '$code','$name', '$email', '$phone', '$dob', '$gender', 
            '$address', '$year', '$major_id', '$national_id', '$status'
        )";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
}
?>
