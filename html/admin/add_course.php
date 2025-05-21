<?php
include('../connect.php');

$course_name = $_POST['course_name'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$status = $_POST['status'] ?? '';

if ($course_name === '' || $status === '') {
    echo json_encode(['success' => false, 'error' => 'Tên khóa học và trạng thái là bắt buộc.']);
    exit;
}

$sql = "INSERT INTO courses (course_name, start_date, end_date, status) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $course_name, $start_date, $end_date, $status);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
