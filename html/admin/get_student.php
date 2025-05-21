<?php
include('../connect.php');
$sql = "SELECT students.id, students.code, students.phone, students.national_id, students.gender, students.status, students.enrollment_year, students.address, students.name, students.major_id, students.email, students.date_of_birth, major.name AS major
        FROM students
        JOIN major ON students.major_id = major.id";
$result = $conn->query($sql);
$students = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
?>
