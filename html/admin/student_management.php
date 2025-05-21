<?php include('get_majors.php'); 
include('get_student.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý sinh viên</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include('header.html'); ?>

<div class="container">
  <div class="page-header">
    <h2>Danh sách sinh viên</h2>
  </div>
  <div class="page-actions">
    <div class="search">
      <input name="search" placeholder="Tìm kiếm theo tên, email sinh viên">
    </div>
    <div class="actions">
      <button class="btn" onclick="openModal()">Thêm mới</button>
      <button class="btn" onclick="downloadFile()">Import sinh viên</button>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Mã SV</th>
        <th>Họ tên</th>
        <th>Ngành học</th>
        <th>Email</th>
        <th>Hành động</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($students as $student): ?>
      <tr>
        <td><?= htmlspecialchars($student['code']) ?></td>
        <td><?= htmlspecialchars($student['name']) ?></td>
        <td><?= htmlspecialchars($student['major']) ?></td>
        <td><?= htmlspecialchars($student['email']) ?></td>
        <td>
          <button onclick='openUpdateModal(<?= json_encode($student) ?>)'>✏️</button>
          <button onclick="deleteStudent(<?= $student['id'] ?>)">🗑️</button>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Popup Form Thêm -->
<div class="modal" id="studentModal">
  <div class="modal-content">
    <h3>Thêm mới sinh viên</h3>

    <input type="text" id="code" placeholder="Mã SV" required>
    <input type="text" id="studentName" placeholder="Họ tên" required>
    <input type="email" id="studentEmail" placeholder="Email" required>
    <input type="tel" id="studentPhone" placeholder="Số điện thoại" required>
    <input type="date" id="studentDOB" placeholder="Ngày sinh" required>

    <select id="studentGender" required>
      <option value="">Giới tính</option>
      <option value="Male">Nam</option>
      <option value="Female">Nữ</option>
      <option value="Other">Khác</option>
    </select>

    <input type="text" id="studentAddress" placeholder="Địa chỉ" required>
    <input type="number" id="studentEnrollmentYear" placeholder="Năm nhập học" min="2000" max="2100" required>

    <select id="studentMajorId" required>
      <option value="">Ngành học</option>
      <?php foreach ($majors as $major): ?>
        <option value="<?= $major['id'] ?>"><?= $major['name'] ?></option>
      <?php endforeach; ?>
    </select>

    <input type="text" id="studentNationalId" placeholder="Số CCCD/CMND" required>

    <select id="studentStatus" required>
      <option value="">Trạng thái</option>
      <option value="Studying">Đang học</option>
      <option value="On Leave">Bảo lưu</option>
      <option value="Graduated">Tốt nghiệp</option>
      <option value="Dropped">Thôi học</option>
    </select>

    <div class="action">
      <button type="button" onclick="saveStudent()">Lưu</button>
      <button type="button" onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>

<!-- Popup Sửa Thông Tin Sinh Viên -->
<div class="modal" id="updateStudentModal">
  <div class="modal-content">
    <h3>Cập nhật sinh viên</h3>

    <input type="hidden" id="updateStudentId">
    <input type="text" id="updateStudentCode" placeholder="Mã SV" required>
    <input type="text" id="updateStudentName" placeholder="Họ tên" required>
    <input type="email" id="updateStudentEmail" placeholder="Email" required>
    <input type="tel" id="updateStudentPhone" placeholder="Số điện thoại" required>
    <input type="date" id="updateStudentDOB" placeholder="Ngày sinh" required>

    <select id="updateStudentGender" required>
      <option value="">Giới tính</option>
      <option value="Male">Nam</option>
      <option value="Female">Nữ</option>
      <option value="Other">Khác</option>
    </select>

    <input type="text" id="updateStudentAddress" placeholder="Địa chỉ" required>
    <input type="number" id="updateStudentEnrollmentYear" placeholder="Năm nhập học" min="2000" max="2100" required>

    <select id="updateStudentMajorId" required>
      <option value="">Ngành học</option>
      <?php foreach ($majors as $major): ?>
        <option value="<?= $major['id'] ?>"><?= $major['name'] ?></option>
      <?php endforeach; ?>
    </select>

    <input type="text" id="updateStudentNationalId" placeholder="Số CCCD/CMND" required>

    <select id="updateStudentStatus" required>
      <option value="">Trạng thái</option>
      <option value="Studying">Đang học</option>
      <option value="On Leave">Bảo lưu</option>
      <option value="Graduated">Tốt nghiệp</option>
      <option value="Dropped">Thôi học</option>
    </select>

    <div class="action">
      <button type="button" onclick="updateStudent()">Cập nhật</button>
      <button type="button" onclick="closeUpdateModal()">Hủy</button>
    </div>
  </div>
</div>

<script>
// Mở modal
function openModal() {
  document.getElementById('studentModal').style.display = 'flex';
}

// Đóng modal
function closeModal() {
  document.getElementById('studentModal').style.display = 'none';
  resetForm();
}

function closeUpdateModal() {
  document.getElementById('updateStudentModal').style.display = 'none';
}

// Reset form
function resetForm() {
  document.getElementById('code').value = '';
  document.getElementById('studentName').value = '';
  document.getElementById('studentEmail').value = '';
  document.getElementById('studentPhone').value = '';
  document.getElementById('studentDOB').value = '';
  document.getElementById('studentGender').value = '';
  document.getElementById('studentAddress').value = '';
  document.getElementById('studentEnrollmentYear').value = '';
  document.getElementById('studentMajorId').value = '';
  document.getElementById('studentNationalId').value = '';
  document.getElementById('studentStatus').value = '';
}

// Lưu sinh viên
function saveStudent() {
  const code = document.getElementById('code').value.trim();
  const name = document.getElementById('studentName').value.trim();
  const email = document.getElementById('studentEmail').value.trim();
  const phone = document.getElementById('studentPhone').value.trim();
  const dob = document.getElementById('studentDOB').value;
  const gender = document.getElementById('studentGender').value;
  const address = document.getElementById('studentAddress').value.trim();
  const year = document.getElementById('studentEnrollmentYear').value;
  const major = document.getElementById('studentMajorId').value;
  const status = document.getElementById('studentStatus').value;
  const nationalId = document.getElementById('studentNationalId').value.trim();

  const formData = new URLSearchParams();
  formData.append('code', code);
  formData.append('name', name);
  formData.append('email', email);
  formData.append('phone', phone);
  formData.append('date_of_birth', dob);
  formData.append('gender', gender);
  formData.append('address', address);
  formData.append('enrollment_year', year);
  formData.append('major_id', major);
  formData.append('status', status);
  formData.append('national_id', nationalId);

  fetch('add_student.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: formData.toString()
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert('Thêm sinh viên thành công!');
      location.reload();
    } else {
      alert('Lỗi: ' + data.error);
    }
  })
  .catch(err => {
    console.error('Fetch error:', err);
    alert('Không thể kết nối đến máy chủ. Vui lòng thử lại sau.');
  });
}

function openUpdateModal(student) {
  document.getElementById('updateStudentModal').style.display = 'flex';
  document.getElementById('updateStudentId').value = student.id;
  document.getElementById('updateStudentCode').value = student.code;
  document.getElementById('updateStudentName').value = student.name;
  document.getElementById('updateStudentEmail').value = student.email;
  document.getElementById('updateStudentPhone').value = student.phone;
  document.getElementById('updateStudentDOB').value = student.date_of_birth;
  document.getElementById('updateStudentGender').value = student.gender;
  document.getElementById('updateStudentAddress').value = student.address;
  document.getElementById('updateStudentEnrollmentYear').value = student.enrollment_year;
  document.getElementById('updateStudentMajorId').value = student.major_id;
  document.getElementById('updateStudentNationalId').value = student.national_id;
  document.getElementById('updateStudentStatus').value = student.status;
}

// Xóa sinh viên
function deleteStudent(id) {
  if (confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) {
    fetch('delete_student.php', {
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

// Cập nhật sinh viên
function updateStudent() {
  const formData = new FormData();
  formData.append('id', document.getElementById('updateStudentId').value);
  formData.append('code', document.getElementById('updateStudentCode').value);
  formData.append('name', document.getElementById('updateStudentName').value);
  formData.append('email', document.getElementById('updateStudentEmail').value);
  formData.append('phone', document.getElementById('updateStudentPhone').value);
  formData.append('dob', document.getElementById('updateStudentDOB').value);
  formData.append('gender', document.getElementById('updateStudentGender').value);
  formData.append('address', document.getElementById('updateStudentAddress').value);
  formData.append('year', document.getElementById('updateStudentEnrollmentYear').value);
  formData.append('major_id', document.getElementById('updateStudentMajorId').value);
  formData.append('national_id', document.getElementById('updateStudentNationalId').value);
  formData.append('status', document.getElementById('updateStudentStatus').value);

  fetch('edit_student.php', {
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

</script>

</body>
</html>
