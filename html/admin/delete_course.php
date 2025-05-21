<?php
include('../connect.php');

$id = $_POST['id'] ?? '';

if ($id === '') {
    echo json_encode(['success' => false, 'error' => 'Thiếu ID để xóa.']);
    exit;
}

$sql = "DELETE FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
