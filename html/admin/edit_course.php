<?php
include '../connect.php';

if (!isset($_GET['course_id'])) {
    echo "Thiếu ID khóa học.";
    exit;
}

$course_id = (int) $_GET['course_id'];
$course_id_safe = mysqli_real_escape_string($conn, $course_id);
$query = "SELECT * FROM courses WHERE course_id = $course_id_safe";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Không tìm thấy khóa học.";
    exit;
}

$course = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chỉnh sửa khóa học</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/subject.css">
  <link rel="stylesheet" href="../css/tab.css">
  <link rel="stylesheet" href="../css/updateCourse.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <script>
    function updateCourseStatus() {
      const label = document.getElementById('courseStatusLabel');
      const checkbox = document.getElementById('courseStatusSwitch');
      label.textContent = checkbox.checked ? 'Mở đăng ký' : 'Đóng đăng ký';
    }

    function updateCourse() {
      const courseName = document.getElementById('courseName').value;
      const startDate = document.getElementById('startDate').value;
      const endDate = document.getElementById('endDate').value;
      const status = document.getElementById('courseStatusSwitch').checked ? 1 : 0;

      const xhr = new XMLHttpRequest();
      xhr.open("POST", "update_course.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onload = function() {
        if (xhr.status === 200) {
          alert("Cập nhật thành công!");
          window.location.href = "course_management.php";
        } else {
          alert("Có lỗi xảy ra!");
        }
      };
      xhr.send(`course_id=<?= $course['course_id'] ?>&course_name=${encodeURIComponent(courseName)}&start_date=${startDate}&end_date=${endDate}&status=${status}`);
    }

    function closeModal() {
      window.location.href = "course_management.php";
    }
  </script>
</head>

<?php include('header.html'); ?>

<body>

<div class="container">
  <div class="page-header">
      <h2>Quản lý khóa học/HKI</h2>
  </div>
  <div class="tab-bar">
  <div class="tab-bar">
  <a href="class_management.php?course_id=<?= $course_id ?>" class="tab-item <?= basename($_SERVER['PHP_SELF']) == 'class_management.php' ? 'active' : '' ?>">Quản lý lớp học</a>
  <a href="edit_course.php?course_id=<?= $course_id ?>" class="tab-item <?= basename($_SERVER['PHP_SELF']) == 'edit_course.php' ? 'active' : '' ?>">Thông tin chung</a>
</div>
</div>

  
  <div class="separator"></div> 
  <div class="updateCourse">
    <div class="modal-content">
      <h3>Chỉnh sửa thông tin khóa học</h3>
      <label for="courseName">Tên khóa học</label>
      <input type="text" id="courseName" value="<?= htmlspecialchars($course['course_name']) ?>">

      <label for="startDate">Ngày bắt đầu</label>
      <input type="date" id="startDate" value="<?= $course['start_date'] ?>">

      <label for="endDate">Ngày kết thúc</label>
      <input type="date" id="endDate" value="<?= $course['end_date'] ?>">

      <label for="courseStatusSwitch">Trạng thái khóa học</label>
      <div class="switch-container">
        <label class="switch">
          <input type="checkbox" id="courseStatusSwitch" onchange="updateCourseStatus()" <?= $course['status'] ? 'checked' : '' ?>>
          <span class="slider round"></span>
        </label>
        <span id="courseStatusLabel"><?= $course['status'] ? 'Mở đăng ký' : 'Đóng đăng ký' ?></span>
      </div>

      <div class="action">
        <button onclick="updateCourse()">Cập nhật</button>
        <button onclick="closeModal()">Hủy</button>
      </div>
    </div>
  </div>

</body>
</html>
