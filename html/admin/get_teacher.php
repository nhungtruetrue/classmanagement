<?php
include('../connect.php');
$sql = "SELECT teachers.teacher_id,
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
        JOIN department ON teachers.department_id = department.id";
$result = $conn->query($sql);
$teachers = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}
?>
