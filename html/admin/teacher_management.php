<?php include('get_departments.php');
include('get_teachers.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý giáo viên</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include('header.html'); ?>

<div class="container">
  <div class="page-header">
    <h2>Danh sách giáo viên</h2>
  </div>
  <div class="page-actions">
    <div class="search">
      <input name="search" placeholder="Tìm kiếm theo tên, email giáo viên">
    </div>
    <div class="actions">
      <button class="btn" onclick="openModal()">Thêm mới</button>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Mã GV</th>
        <th>Họ tên</th>
        <th>Phòng ban</th>
        <th>Email</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($teachers as $teacher): ?>
      <tr>
        <td><?= htmlspecialchars($teacher['teacher_id']) ?></td>
        <td><?= htmlspecialchars($teacher['name']) ?></td>
        <td><?= htmlspecialchars($teacher['department_name']) ?></td>
        <td><?= htmlspecialchars($teacher['email']) ?></td>
        <td>
          <button onclick='openUpdateModal(<?= json_encode($teacher) ?>)'>✏️</button>
          <button onclick="deleteTeacher(<?= $teacher['teacher_id'] ?>)">🗑️</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Popup Thêm giáo viên -->
<div class="modal" id="teacherModal">
  <div class="modal-content">
    <h3>Thêm giáo viên</h3>
    <input type="text" id="teacherName" placeholder="Họ tên" required>
    <input type="email" id="teacherEmail" placeholder="Email">
    <input type="tel" id="teacherPhone" placeholder="Số điện thoại">
    <input type="date" id="teacherDOB" placeholder="Ngày sinh">
    <select id="teacherGender">
      <option value="">Giới tính</option>
      <option value="Male">Nam</option>
      <option value="Female">Nữ</option>
      <option value="Other">Khác</option>
    </select>
    <input type="text" id="teacherAddress" placeholder="Địa chỉ">
    <select id="teacherDepartmentId">
      <option value="">Phòng ban</option>
      <?php foreach ($departments as $dep): ?>
        <option value="<?= $dep['id'] ?>"><?= $dep['name'] ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" id="teacherPosition" placeholder="Chức vụ">
    <input type="text" id="teacherDegree" placeholder="Học vị">
    <input type="date" id="teacherHireDate" placeholder="Ngày tuyển dụng">
    <select id="teacherStatus">
      <option value="">Trạng thái</option>
      <option value="Active">Đang làm</option>
      <option value="On Leave">Nghỉ phép</option>
      <option value="Retired">Nghỉ hưu</option>
    </select>
    <textarea id="teacherBio" placeholder="Tiểu sử"></textarea>
    <div class="action">
      <button onclick="saveTeacher()">Lưu</button>
      <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>

<!-- Modal cập nhật giáo viên -->
<div class="modal" id="updateTeacherModal">
  <div class="modal-content">
    <h3>Cập nhật giáo viên</h3>
    <input type="hidden" id="updateTeacherId">
    <input type="text" id="updateTeacherName" placeholder="Họ tên">
    <input type="email" id="updateTeacherEmail" placeholder="Email">
    <input type="tel" id="updateTeacherPhone" placeholder="Số điện thoại">
    <input type="date" id="updateTeacherDOB" placeholder="Ngày sinh">
    <select id="updateTeacherGender">
      <option value="">Giới tính</option>
      <option value="Male">Nam</option>
      <option value="Female">Nữ</option>
      <option value="Other">Khác</option>
    </select>
    <input type="text" id="updateTeacherAddress" placeholder="Địa chỉ">


    <select id="updateTeacherDepartmentId">
      <option value="">Phòng ban</option>
      <?php foreach ($departments as $dep): ?>
        <option value="<?= $dep['id'] ?>"><?= $dep['name'] ?></option>
      <?php endforeach; ?>
    </select>


    <input type="text" id="updateTeacherPosition" placeholder="Chức vụ">
    <input type="text" id="updateTeacherDegree" placeholder="Học vị">
    <input type="date" id="updateTeacherHireDate" placeholder="Ngày tuyển dụng">
    <select id="updateTeacherStatus">
      <option value="">Trạng thái</option>
      <option value="Active">Đang làm</option>
      <option value="On Leave">Nghỉ phép</option>
      <option value="Retired">Nghỉ hưu</option>
    </select>
    <textarea id="updateTeacherBio" placeholder="Tiểu sử"></textarea>
    <div class="action">
      <button onclick="updateTeacher()">Cập nhật</button>
      <button onclick="closeUpdateModal()">Hủy</button>
    </div>
  </div>
</div>

<script>
// Hiển thị popup
function openModal() {
  document.getElementById('teacherModal').style.display = 'flex';
}
function closeModal() {
  document.getElementById('teacherModal').style.display = 'none';
}
function openUpdateModal(teacher) {
  document.getElementById('updateTeacherModal').style.display = 'flex';
  document.getElementById('updateTeacherId').value = teacher.teacher_id;
  document.getElementById('updateTeacherName').value = teacher.name;
  document.getElementById('updateTeacherEmail').value = teacher.email;
  document.getElementById('updateTeacherPhone').value = teacher.phone;
  document.getElementById('updateTeacherDOB').value = teacher.date_of_birth;
  document.getElementById('updateTeacherGender').value = teacher.gender;
  document.getElementById('updateTeacherAddress').value = teacher.address;
  document.getElementById('updateTeacherDepartmentId').value = teacher.department_id;
  document.getElementById('updateTeacherPosition').value = teacher.position;
  document.getElementById('updateTeacherDegree').value = teacher.degree;
  document.getElementById('updateTeacherHireDate').value = teacher.hire_date;
  document.getElementById('updateTeacherStatus').value = teacher.status;
  document.getElementById('updateTeacherBio').value = teacher.bio;
}
function closeUpdateModal() {
  document.getElementById('updateTeacherModal').style.display = 'none';
}

// Gửi dữ liệu lên server
function saveTeacher() {
  const formData = new URLSearchParams();
  formData.append('name', document.getElementById('teacherName').value);
  formData.append('email', document.getElementById('teacherEmail').value);
  formData.append('phone', document.getElementById('teacherPhone').value);
  formData.append('dob', document.getElementById('teacherDOB').value);
  formData.append('gender', document.getElementById('teacherGender').value);
  formData.append('address', document.getElementById('teacherAddress').value);
  formData.append('department_id', document.getElementById('teacherDepartmentId').value);
  formData.append('position', document.getElementById('teacherPosition').value);
  formData.append('degree', document.getElementById('teacherDegree').value);
  formData.append('hire_date', document.getElementById('teacherHireDate').value);
  formData.append('status', document.getElementById('teacherStatus').value);
  formData.append('bio', document.getElementById('teacherBio').value);

  fetch('add_teacher.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: formData.toString()
  }).then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Thêm giáo viên thành công!');
        location.reload();
      } else {
        alert('Lỗi: ' + data.error);
      }
    });
}

function updateTeacher() {
  const formData = new FormData();
  formData.append('id', document.getElementById('updateTeacherId').value);
  formData.append('name', document.getElementById('updateTeacherName').value);
  formData.append('email', document.getElementById('updateTeacherEmail').value);
  formData.append('phone', document.getElementById('updateTeacherPhone').value);
  formData.append('dob', document.getElementById('updateTeacherDOB').value);
  formData.append('gender', document.getElementById('updateTeacherGender').value);
  formData.append('address', document.getElementById('updateTeacherAddress').value);
  formData.append('department_id', document.getElementById('updateTeacherDepartmentId').value);
  formData.append('position', document.getElementById('updateTeacherPosition').value);
  formData.append('degree', document.getElementById('updateTeacherDegree').value);
  formData.append('hire_date', document.getElementById('updateTeacherHireDate').value);
  formData.append('status', document.getElementById('updateTeacherStatus').value);
  formData.append('bio', document.getElementById('updateTeacherBio').value);

  fetch('edit_teacher.php', {
    method: 'POST',
    body: formData
  }).then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Cập nhật thành công!');
        location.reload();
      } else {
        alert('Lỗi: ' + data.error);
      }
    });
}

function deleteTeacher(id) {
  if (confirm('Bạn có chắc chắn muốn xóa giáo viên này?')) {
    fetch('delete_teacher.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'id=' + encodeURIComponent(id)
    }).then(res => res.json())
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
