<?php
include('../connect.php');

$sql_majors = "SELECT id, name FROM major";
$result_majors = $conn->query($sql_majors);
$majors = [];

if ($result_majors && $result_majors->num_rows > 0) {
    while ($row = $result_majors->fetch_assoc()) {
        $majors[] = $row;
    }
}
?>