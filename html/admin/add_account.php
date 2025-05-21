<?php
include('../connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy và làm sạch dữ liệu
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $userType = $_POST['user_type']; // "student" hoặc "teacher"
    $userId = intval($_POST['user_id']);

    if (empty($email) || empty($password) || empty($userType) || empty($userId)) {
        echo json_encode(['success' => false, 'error' => 'Thiếu thông tin cần thiết.']);
        exit;
    }

    $check_sql = "SELECT id FROM accounts WHERE username = '$email'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (!$check_result) {
        echo json_encode(['success' => false, 'error' => 'Lỗi truy vấn kiểm tra: ' . mysqli_error($conn)]);
        exit;
    }
    
    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(['success' => false, 'error' => 'Email đã tồn tại.']);
        exit;
    }
    // truêtruee

    // Mã hóa mật khẩu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Xác định role và ID
    $role = $userType === 'student' ? 'student' : 'teacher';
    $student_id = $userType === 'student' ? $userId : "NULL";
    $teacher_id = $userType === 'teacher' ? $userId : "NULL";

    $insert_sql = "
        INSERT INTO accounts (username, password, role, student_id, teacher_id)
        VALUES ('$email', '$hashedPassword', '$role', $student_id, $teacher_id)
    ";

    if (mysqli_query($conn, $insert_sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Lỗi khi thêm tài khoản: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Yêu cầu không hợp lệ.']);
}
?>
