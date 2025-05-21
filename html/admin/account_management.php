<?php 
include('get_account.php'); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý tài khoản</title>
  <link rel="stylesheet" href="../css/student.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/pop_upadd.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    .tab {
      display: flex;
      margin-bottom: 20px;
    }
    .tab button {
      padding: 10px 20px;
      border: none;
      background: #ccc;
      cursor: pointer;
      margin-right: 5px;
    }
    .tab button.active {
      background: #007bff;
      color: white;
    }
    .tab-content {
      display: none;
    }
    .tab-content.active {
      display: block;
    }
    .modal {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }
    .modal-content {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 400px;
    }
  </style>
</head>
<body>
<?php include('header.html'); ?>

<div class="container">
  <div class="page-header">
    <h2>Quản lý tài khoản</h2>
  </div>
  <div class="tab">
    <button class="tablink active" onclick="showTab('student')">Sinh viên</button>
    <button class="tablink" onclick="showTab('teacher')">Giảng viên</button>
  </div>
  <div class="actions" >
      <button class="btn" onclick="openModal()">Thêm mới</button>
    </div>
  <div id="student" class="tab-content active">
    <h3>Danh sách tài khoản sinh viên</h3>
    <table>
      <thead>
        <tr>
          <th>Mã SV</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($student_accounts as $account): ?>
        <tr>
          <td><?= $account['code'] ?></td>
          <td><?= $account['name'] ?></td>
          <td><?= $account['email'] ?></td>
          <td>
            <button onclick='openUpdateModal(<?= json_encode($account) ?>)'>✏️</button>
            <button onclick="deleteaccount(<?= $account['id'] ?>)">🗑️</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div id="teacher" class="tab-content">
    <h3>Danh sách tài khoản giảng viên</h3>
    <table>
      <thead>
        <tr>
          <th>Mã GV</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($teacher_accounts as $account): ?>
        <tr>
          <td><?= $account['code'] ?></td>
          <td><?= $account['name'] ?></td>
          <td><?= $account['email'] ?></td>
          <td>
            <button onclick='openUpdateModal(<?= json_encode($account) ?>)'>✏️</button>
            <button onclick="deleteaccount(<?= $account['id'] ?>)">🗑️</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<<div class="modal" id="accountModal">
  <div class="modal-content">
    <h3>Thêm tài khoản</h3>
    <select id="userType" onchange="fillEmailOptions()">
      <option value="">Chọn loại người dùng</option>
      <option value="student">Sinh viên</option>
      <option value="teacher">Giảng viên</option>
    </select>

    <select id="userSelect" onchange="autoFillEmail()">
      <option value="">Chọn người dùng</option>
    </select>

    <input type="email" id="accountEmail" placeholder="Email" readonly>
    <input type="password" id="accountPassword" placeholder="Mật khẩu" required>

    <div class="action">
      <button onclick="saveAccount()">Lưu</button>
      <button onclick="closeModal()">Hủy</button>
    </div>
  </div>
</div>


<script>
function showTab(tab) {
  document.querySelectorAll('.tablink').forEach(btn => btn.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(div => div.classList.remove('active'));
  document.querySelector(`.tablink[onclick="showTab('${tab}')"]`).classList.add('active');
  document.getElementById(tab).classList.add('active');
}

function openModal() {
  document.getElementById('accountModal').style.display = 'flex';
  document.getElementById('userType').value = '';
  document.getElementById('userSelect').innerHTML = '<option value="">Chọn người dùng</option>';
  document.getElementById('accountEmail').value = '';
  document.getElementById('accountPassword').value = '';
}

function closeModal() {
  document.getElementById('accountModal').style.display = 'none';
}
function fillEmailOptions() {
  const type = document.getElementById('userType').value;
  const select = document.getElementById('userSelect');
  select.innerHTML = '<option value="">Chọn người dùng</option>';

  if (!type) return;

  fetch(`get_${type}.php`)
    .then(res => res.json())
    .then(data => {
      data.forEach(user => {
        const option = document.createElement('option');
        option.value = JSON.stringify(user);
        option.textContent = `${user.code} - ${user.name} (${user.email})`;
        select.appendChild(option);
      });
    })
    .catch(error => {
      console.error('Lỗi khi tải danh sách người dùng:', error);
    });
}

function autoFillEmail() {
  const selected = document.getElementById('userSelect').value;
  if (!selected) {
    document.getElementById('accountEmail').value = '';
    return;
  }

  const user = JSON.parse(selected);
  document.getElementById('accountEmail').value = user.email;
}

function saveAccount() {
  const email = document.getElementById('accountEmail').value;
  const password = document.getElementById('accountPassword').value;
  const type = document.getElementById('userType').value;
  const user = JSON.parse(document.getElementById('userSelect').value);

  fetch('add_account.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({
      user_type: type,
      user_id: user.id,
      email,
      password
    })
  }).then(res => res.json())
    .then(data => {
      if (data.success) {
        alert('Tạo tài khoản thành công!');
        location.reload();
      } else {
        alert('Lỗi: ' + data.error);
      }
    });
}
</script>
</body>
</html>
