<?php
include('../connect.php');

$course_id = intval($_POST['course_id'] ?? 0);
$subject_id = intval($_POST['subject_id'] ?? 0);

if ($course_id && $subject_id) {
    $query = "INSERT INTO course_subjects (course_id, subject_id) VALUES ($course_id, $subject_id)";
    if (mysqli_query($conn, $query)) {
        header("Location: class_management.php?course_id=$course_id");
        exit;
    } else {
        echo "Lỗi khi thêm môn học.";
    }
} else {
    echo "Thiếu dữ liệu.";
}
?>
