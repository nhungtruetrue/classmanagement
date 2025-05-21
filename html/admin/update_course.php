<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $course_id = (int) $_POST['course_id'];  
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $status = isset($_POST['status']) && $_POST['status'] == '1' ? 1 : 0;

    $query = "UPDATE courses 
              SET course_name = '$course_name', 
                  start_date = '$start_date', 
                  end_date = '$end_date', 
                  status = $status 
              WHERE course_id = $course_id";

    if (mysqli_query($conn, $query)) {
        echo "Cập nhật thành công.";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
