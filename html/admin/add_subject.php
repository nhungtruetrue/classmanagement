<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_name = $_POST['subject_name'];
    $description = $_POST['description'];

    if (empty($subject_name)) {
        echo json_encode(['success' => false, 'error' => 'Tên môn học không được để trống.']);
        exit;
    }

    $sql = "INSERT INTO subjects (subject_name, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $subject_name, $description);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Không thể thêm môn học.']);
    }
}
?>
