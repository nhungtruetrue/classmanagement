<?php
include('../connect.php');
$sql = "SELECT * FROM subjects";
$result = $conn->query($sql);
$subjects = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý môn học</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
</head>
<body>

<?php include('header.html'); ?>

<div class="container">
  <div class="page-header">
    <h2>Danh sách môn học</h2>
  </div>
  <div class="page-actions">
    <button class="btn" onclick="openModal()">Thêm môn học</button>
  </div>

  <table>
    <thead>
      <tr>
        <th>Mã môn</th>
        <th>Tên môn học</th>
        <th>Mô tả</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($subjects as $subject): ?>
      <tr>
        <td><?= $subject['subject_id'] ?></td>
        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
        <td><?= htmlspecialchars($subject['description']) ?></td>
        <td>
          <button onclick='openUpdateModal(<?= json_encode($subject) ?>)'>✏️</button>
          <button onclick="deleteSubject(<?= $subject['subject_id'] ?>)">🗑️</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal Thêm -->
<div class="modal" id="subjectModal">
  <div class="modal-content">
    <h3>Thêm môn học</h3>
    <input type="text" id="subjectName" placeholder="Tên môn học" required>
    <textarea id="description" placeholder="Mô tả"></textarea>
    <div class="action">
      <button onclick="saveSubject()">Lưu</button>
      <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>

<!-- Modal Cập nhật -->
<div class="modal" id="updateSubjectModal">
  <div class="modal-content">
    <h3>Cập nhật môn học</h3>
    <input type="hidden" id="updateSubjectId">
    <input type="text" id="updateSubjectName" placeholder="Tên môn học">
    <textarea id="updateDescription" placeholder="Mô tả"></textarea>
    <div class="action">
      <button onclick="updateSubject()">Cập nhật</button>
      <button onclick="closeUpdateModal()">Hủy</button>
    </div>
  </div>
</div>

<script>
function openModal() {
  document.getElementById('subjectModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('subjectModal').style.display = 'none';
}
function openUpdateModal(subject) {
  document.getElementById('updateSubjectModal').style.display = 'flex';
  document.getElementById('updateSubjectId').value = subject.subject_id;
  document.getElementById('updateSubjectName').value = subject.subject_name;
  document.getElementById('updateDescription').value = subject.description;
}
function closeUpdateModal() {
  document.getElementById('updateSubjectModal').style.display = 'none';
}

function saveSubject() {
  const data = new URLSearchParams();
  data.append('subject_name', document.getElementById('subjectName').value);
  data.append('description', document.getElementById('description').value);

  fetch('add_subject.php', {
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

function updateSubject() {
  const formData = new FormData();
  formData.append('subject_id', document.getElementById('updateSubjectId').value);
  formData.append('subject_name', document.getElementById('updateSubjectName').value);
  formData.append('description', document.getElementById('updateDescription').value);

  fetch('edit_subject.php', {
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

function deleteSubject(id) {
  if (confirm('Bạn có chắc muốn xóa?')) {
    fetch('delete_subject.php', {
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
