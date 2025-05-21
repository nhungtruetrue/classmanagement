<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['id'];

    if (empty($subject_id)) {
        echo json_encode(['success' => false, 'error' => 'ID môn học không hợp lệ.']);
        exit;
    }

    $sql = "DELETE FROM subjects WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subject_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể xóa môn học.']);
    }
}
?>
