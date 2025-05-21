<?php
include('../connect.php');

$course_subject_id = isset($_GET['course_subject_id']) ? intval($_GET['course_subject_id']) : 0;

if ($course_subject_id === 0) {
    die('Thiếu course_subject_id.');
}

// Lấy danh sách lịch học
$query = "
    SELECT cs.schedule_id, cs.teacher_id, cs.shift_id, cs.study_date,
           t.name AS teacher_name, sh.shift_name
    FROM class_schedules cs
    LEFT JOIN teachers t ON cs.teacher_id = t.teacher_id
    LEFT JOIN shifts sh ON cs.shift_id = sh.shift_id
    WHERE cs.course_subject_id = $course_subject_id
";
$result = mysqli_query($conn, $query);

$schedules = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $schedules[] = $row;
    }
}

// Lấy danh sách giáo viên
$teachers = [];
$tresult = mysqli_query($conn, "SELECT teacher_id, name FROM teachers");
while ($row = mysqli_fetch_assoc($tresult)) {
    $teachers[] = $row;
}

// Lấy danh sách ca học
$shifts = [];
$sresult = mysqli_query($conn, "SELECT shift_id, shift_name FROM shifts");
while ($row = mysqli_fetch_assoc($sresult)) {
    $shifts[] = $row;
}

function getDayName($dayNumber) {
    $days = [1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7', 7 => 'Chủ nhật'];
    return $days[$dayNumber] ?? 'Không rõ';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý lịch học</title>
    <link rel="stylesheet" href="../css/student.css">
    <link rel="stylesheet" href="../css/subject.css">
    <link rel="stylesheet" href="../css/tab.css">
    <link rel="stylesheet" href="../css/pop_upadd.css">
    <link rel="stylesheet" href="../css/updateCourse.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; border-radius: 8px; width: 400px; }
    </style>
</head>
<body>
<?php include("header.html"); ?>

<h2>Lịch học cho môn học #<?= $course_subject_id ?></h2>
<div class="actions">
    <button class="btn" onclick="openModal()">Thêm mới</button>
</div>

<table>
    <thead>
        <tr>
            <th>Giáo viên</th>
            <th>Ca học</th>
            <th>Thứ</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($schedules as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['teacher_name']) ?></td>
            <td><?= htmlspecialchars($row['shift_name']) ?></td>
            <td><?= getDayName(intval($row['study_date'])) ?></td>
            <td>
                <button onclick="editSchedule(<?= $row['schedule_id'] ?>)">✏️</button>
                <button onclick="deleteSchedule(<?= $row['schedule_id'] ?>)">🗑️</button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Popup Thêm lịch học -->
<div class="modal" id="scheduleModal">
  <div class="modal-content">
    <h3>Thêm lịch học</h3>
    <input type="hidden" id="course_subject_id" value="<?= $course_subject_id ?>">
    
    <label>Giáo viên:</label>
    <select id="teacher_id" required>
      <option value="">--Chọn Giảng viên--</option>
      <?php foreach ($teachers as $teacher): ?>
        <option value="<?= $teacher['teacher_id'] ?>"><?= $teacher['name'] ?></option>
      <?php endforeach; ?>
    </select>
    
    <label>Ca học:</label>
    <select id="shift_id" required>
      <option value="">--Chọn Ca học--</option>
      <?php foreach ($shifts as $shift): ?>
        <option value="<?= $shift['shift_id'] ?>"><?= $shift['shift_name'] ?></option>
      <?php endforeach; ?>
    </select>

    <label>Thứ :</label>
    <select id="study_date" required>
      <?php for ($i = 1; $i <= 7; $i++): ?>
        <option value="<?= $i ?>"><?= getDayName($i) ?></option>
      <?php endfor; ?>
    </select>
    <div class="action">
      <button onclick="saveSchedule()">Lưu</button>
      <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>

<script>
function openModal() {
    document.getElementById('scheduleModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('scheduleModal').style.display = 'none';
}

function editSchedule(id) {
    window.location.href = 'edit_schedule.php?schedule_id=' + id;
}

function deleteSchedule(id) {
    if (confirm('Bạn có chắc muốn xóa lịch học này?')) {
        window.location.href = 'delete_schedule.php?schedule_id=' + id;
    }
}

function saveSchedule() {
    const course_subject_id = document.getElementById('course_subject_id').value;
    const teacher_id = document.getElementById('teacher_id').value;
    const shift_id = document.getElementById('shift_id').value;
    const study_date = document.getElementById('study_date').value;
    if (!teacher_id || !shift_id || !study_date) {
        alert('Vui lòng nhập đầy đủ thông tin!');
        return;
    }

    const formData = new FormData();
    formData.append('course_subject_id', course_subject_id);
    formData.append('teacher_id', teacher_id);
    formData.append('shift_id', shift_id);
    formData.append('study_date', study_date);
    fetch('add_schedule.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        alert('Đã thêm lịch học thành công!');
        window.location.reload();
    })
    .catch(err => {
        console.error(err);
        alert('Lỗi khi thêm lịch học!');
    });
}
</script>

</body>
</html>
