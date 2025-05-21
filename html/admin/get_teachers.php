<?php
include('../connect.php'); // Kết nối trả về $conn là mysqli

$teachers = [];

$sql = "
    SELECT 
        teachers.teacher_id,
        teachers.name,
         teachers.code,
        teachers.email,
        teachers.phone,
        teachers.date_of_birth,
        teachers.gender,
        teachers.address,
        teachers.department_id,
        departments.name AS department_name,
        teachers.position,
        teachers.degree,
        teachers.hire_date,
        teachers.status,
        teachers.bio
    FROM teachers
    LEFT JOIN departments ON teachers.department_id = departments.id
    ORDER BY teachers.teacher_id DESC
";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
} else {
}
?>
