function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('active');
  }
  
  
  function openModal() {
    document.getElementById('studentModal').style.display = 'flex';
  }
  
  function closeModal() {
    document.getElementById('studentModal').style.display = 'none';
    clearForm();
  }
  
  function clearForm() {
    document.getElementById('studentId').value = '';
    document.getElementById('studentName').value = '';
    document.getElementById('studentClass').value = '';
    document.getElementById('studentEmail').value = '';
  }
  
  function saveStudent() {
    const id = document.getElementById('studentId').value;
    const name = document.getElementById('studentName').value;
    const className = document.getElementById('studentClass').value;
    const email = document.getElementById('studentEmail').value;
  
    if (id && name && className && email) {
      students.push({ id, name, class: className, email });
      renderTable();
      closeModal();
    } else {
      alert('Vui lòng nhập đầy đủ thông tin.');
    }
  }
  
  function editStudent(index) {
    const student = students[index];
    const name = prompt('Sửa tên:', student.name);
    const className = prompt('Sửa lớp:', student.class);
    const email = prompt('Sửa email:', student.email);
    if (name && className && email) {
      students[index] = { ...student, name, class: className, email };
      renderTable();
    }
  }
  
  function deleteStudent(index) {
    if (confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) {
      students.splice(index, 1);
      renderTable();
    }
  }
  
  function downloadFile() {
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Mã SV,Họ tên,Lớp,Email\n";
    students.forEach(student => {
      csvContent += `${student.id},${student.name},${student.class},${student.email}\n`;
    });
  
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'danh_sach_sinh_vien.csv');
    document.body.appendChild(link);
    link.click();
  }
  
  renderTable();
  