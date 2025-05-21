<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $subject_name = $_POST['subject_name'];
    $description = $_POST['description'];

    if (empty($subject_name)) {
        echo json_encode(['success' => false, 'error' => 'Tên môn học không được để trống.']);
        exit;
    }

    $sql = "UPDATE subjects SET subject_name = ?, description = ? WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $subject_name, $description, $subject_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể cập nhật môn học.']);
    }
}
?>
