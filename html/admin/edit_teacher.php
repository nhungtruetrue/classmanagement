<?php
require_once '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['teacher_id'] ?? '';
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $dob = mysqli_real_escape_string($conn, $_POST['date_of_birth'] ?? '');
    $gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $department_id = $_POST['department_id'] ?? 'NULL';
    $position = mysqli_real_escape_string($conn, $_POST['position'] ?? '');
    $degree = mysqli_real_escape_string($conn, $_POST['degree'] ?? '');
    $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');
    $bio = mysqli_real_escape_string($conn, $_POST['bio'] ?? '');

    $id = (int)$id;
    $department_id = $department_id !== '' ? (int)$department_id : 'NULL';

    $sql = "UPDATE teachers SET 
                name='$name', 
                email='$email', 
                phone='$phone', 
                date_of_birth='$dob',
                gender='$gender',
                address='$address',
                department_id=$department_id,
                position='$position',
                degree='$degree',
                hire_date='$hire_date',
                status='$status',
                bio='$bio'
            WHERE teacher_id=$id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
