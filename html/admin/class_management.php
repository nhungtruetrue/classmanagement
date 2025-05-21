<?php 
include('get_majors.php');
include('get_student.php');
include('get_subject.php');
include('../connect.php');

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;

if ($course_id === 0) {
    die('Thiếu thông tin khóa học.');
}

$subjects = [];
$available_subjects = [];

$course_id_safe = mysqli_real_escape_string($conn, $course_id);

// Lấy danh sách môn học thuộc khóa học kèm số lớp
$query = "
    SELECT 
      cs.course_subject_id,
        s.subject_id, 
        s.subject_name,
        COUNT(csched.schedule_id) AS class_count
    FROM course_subjects cs
    JOIN subjects s ON cs.subject_id = s.subject_id
    LEFT JOIN class_schedules csched ON cs.course_subject_id = csched.course_subject_id
    WHERE cs.course_id = $course_id_safe
    GROUP BY s.subject_id, s.subject_name
";

$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $subjects[] = $row;
    }
}

// Lấy các môn học chưa được thêm vào khóa học này
$query_available = "
    SELECT subject_id, subject_name 
    FROM subjects 
    WHERE subject_id NOT IN (
        SELECT subject_id FROM course_subjects WHERE course_id = $course_id_safe
    )
";
$result_available = mysqli_query($conn, $query_available);
if ($result_available) {
    while ($row = mysqli_fetch_assoc($result_available)) {
        $available_subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý lớp học</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/subject.css">
  <link rel="stylesheet" href="../css/tab.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
  <link rel="stylesheet" href="../css/updateCourse.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include('header.html'); ?>

<div class="container">
  <div class="tab-bar">
    <a href="class_management.php?course_id=<?= $course_id ?>" class="tab-item <?= basename($_SERVER['PHP_SELF']) == 'class_management.php' ? 'active' : '' ?>">Quản lý lớp học</a>
    <a href="edit_course.php?course_id=<?= $course_id ?>" class="tab-item <?= basename($_SERVER['PHP_SELF']) == 'edit_course.php' ? 'active' : '' ?>">Thông tin chung</a>
  </div>

  <div class="page-actions">
    <div class="search">
      <input name="search" placeholder="Tìm kiếm theo tên môn học">
    </div>
    <div class="actions">
      <button class="btn" onclick="openModal()">Thêm mới</button>
    </div>
  </div>

  <div class="page-header">
    <h2>Danh sách môn học trong khóa học</h2>
  </div>

  <?php if (empty($subjects)): ?>
    <p>Không có môn học nào được phân cho khóa học này.</p>
  <?php else: ?>
    <table>
  <thead>
    <tr>
      <th>Tên môn học</th>
      <th>Số lớp</th>
      <th>Hành động</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($subjects as $subject): ?>
      <tr>
        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
        <td><?= intval($subject['class_count']) ?></td>
        <td>
          <a href="schedule_management.php?course_subject_id=<?= $subject['course_subject_id'] ?>" class="btn-edit">✏️</a>
          <button onclick="deleteSubjectFromCourse(<?= $subject['subject_id'] ?>)">🗑️</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <?php endif; ?>
</div>

<!-- Popup Thêm môn học -->
<div class="modal" id="subjectModal">
  <div class="modal-content">
    <h3>Thêm môn học</h3>
    <form action="add_subject_to_course.php" method="POST">
      <input type="hidden" name="course_id" value="<?= $course_id ?>">
      <select name="subject_id" required>
        <option value="">-- Chọn môn học --</option>
        <?php foreach ($available_subjects as $subject): ?>
          <option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
        <?php endforeach; ?>
      </select>
      <div class="action">
        <button type="submit">Lưu</button>
        <button type="button" onclick="closeModal()">Hủy</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('subjectModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('subjectModal').style.display = 'none';
}
function deleteSubjectFromCourse(subjectId) {
  if (confirm("Bạn có chắc muốn xóa môn học này khỏi khóa học không?")) {
    window.location.href = `delete_subject_from_course.php?course_id=<?= $course_id ?>&subject_id=` + subjectId;
  }
}


</script>

</body>
</html>
