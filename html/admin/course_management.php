<?php
// course_management.php
include('../connect.php');

// Lấy danh sách khóa học
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
$courses = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý khóa học</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
</head>
<body>

<?php include('header.html'); ?>

<div class="container">
  <div class="page-header">
    <h2>Danh sách khóa học</h2>
  </div>
  <div class="page-actions">
    <button class="btn" onclick="openModal()">Thêm khóa học</button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Mã KH</th>
        <th>Tên khóa học</th>
        <th>Ngày bắt đầu</th>
        <th>Ngày kết thúc</th>
        <th>Trạng thái</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($courses as $course): ?>
      <tr>
        <td><?= $course['course_id'] ?></td>
        <td><?= htmlspecialchars($course['course_name']) ?></td>
        <td><?= $course['start_date'] ?></td>
        <td><?= $course['end_date'] ?></td>
        <td><?= $course['status'] ?></td>
        <td>
        <a href="edit_course.php?course_id=<?= $course['course_id'] ?>" class="btn-edit">✏️</a>
          <button onclick="deleteCourse(<?= $course['course_id'] ?>)">🗑️</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal Thêm -->
<div class="modal" id="courseModal">
  <div class="modal-content">
    <h3>Thêm khóa học</h3>
    <input type="text" id="courseName" placeholder="Tên khóa học" required>
    <input type="date" id="startDate">
    <input type="date" id="endDate">
    <select id="status">
      <option value="">Trạng thái</option>
      <option value="Open">Mở</option>
      <option value="Closed">Đóng</option>
      <option value="Completed">Hoàn thành</option>
    </select>
    <div class="action">
      <button onclick="saveCourse()">Lưu</button>
      <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>

<!-- Modal Cập nhật -->
<div class="modal" id="updateCourseModal">
  <div class="modal-content">
    <h3>Cập nhật khóa học</h3>
    <input type="hidden" id="updateCourseId">
    <input type="text" id="updateCourseName" placeholder="Tên khóa học">
    <input type="date" id="updateStartDate">
    <input type="date" id="updateEndDate">
    <select id="updateStatus">
      <option value="Open">Mở</option>
      <option value="Closed">Đóng</option>
      <option value="Completed">Hoàn thành</option>
    </select>
    <div class="action">
      <button onclick="updateCourse()">Cập nhật</button>
      <button onclick="closeUpdateModal()">Hủy</button>
    </div>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('courseModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('courseModal').style.display = 'none';
}
function openUpdateModal(course) {
  document.getElementById('updateCourseModal').style.display = 'flex';
  document.getElementById('updateCourseId').value = course.course_id;
  document.getElementById('updateCourseName').value = course.course_name;
  document.getElementById('updateStartDate').value = course.start_date;
  document.getElementById('updateEndDate').value = course.end_date;
  document.getElementById('updateStatus').value = course.status;
}
function closeUpdateModal() {
  document.getElementById('updateCourseModal').style.display = 'none';
}

function saveCourse() {
  const data = new URLSearchParams();
  data.append('course_name', document.getElementById('courseName').value);
  data.append('start_date', document.getElementById('startDate').value);
  data.append('end_date', document.getElementById('endDate').value);
  data.append('status', document.getElementById('status').value);

  fetch('add_course.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: data.toString()
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Thêm thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + data.error);
    }
  });
}

function updateCourse() {
  const formData = new FormData();
  formData.append('course_id', document.getElementById('updateCourseId').value);
  formData.append('course_name', document.getElementById('updateCourseName').value);
  formData.append('start_date', document.getElementById('updateStartDate').value);
  formData.append('end_date', document.getElementById('updateEndDate').value);
  formData.append('status', document.getElementById('updateStatus').value);

  fetch('edit_course.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Cập nhật thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + data.error);
    }
  });
}

function deleteCourse(id) {
  if (confirm('Bạn có chắc muốn xóa?')) {
    fetch('delete_course.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + encodeURIComponent(id)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Đã xóa');
        location.reload();
      } else {
        alert('Lỗi: ' + data.error);
      }
    });
  }
}
</script>

</body>
</html>
