<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $code = mysqli_real_escape_string($conn,$_POST['code']??'');
    $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $dob = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? '');
    $address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $year = mysqli_real_escape_string($conn, $_POST['year'] ?? '');
    $major_id = mysqli_real_escape_string($conn, $_POST['major_id'] ?? '');
    $national_id = mysqli_real_escape_string($conn, $_POST['national_id'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');

    if ($id > 0) {
        $sql = "UPDATE students 
                SET code='$code', name='$name', email='$email', phone='$phone', date_of_birth='$dob',
                    gender='$gender', address='$address', enrollment_year='$year',
                    major_id='$major_id', national_id='$national_id', status='$status'
                WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $conn->error]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'ID không hợp lệ']);
    }

    $conn->close();
}
?>
