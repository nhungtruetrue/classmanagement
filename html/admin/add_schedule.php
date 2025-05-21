<?php
include('../connect.php');

$course_subject_id = isset($_POST['course_subject_id']) ? intval($_POST['course_subject_id']) : 0;
$teacher_id = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : 0;
$shift_id = isset($_POST['shift_id']) ? intval($_POST['shift_id']) : 0;
$study_date = isset($_POST['study_date']) ? intval($_POST['study_date']) : 0;

if ($course_subject_id === 0 || $teacher_id === 0 || $shift_id === 0 || $study_date === 0) {
    http_response_code(400);
    echo 'Thiếu thông tin đầu vào.';
    exit;
}

$query = "
    INSERT INTO class_schedules (course_subject_id, teacher_id, shift_id, study_date)
    VALUES ($course_subject_id, $teacher_id, $shift_id, $study_date)
";

if (mysqli_query($conn, $query)) {
    echo "Thêm lịch học thành công.";
} else {
    http_response_code(500);
    echo "Lỗi: " . mysqli_error($conn);
}

mysqli_close($conn);
