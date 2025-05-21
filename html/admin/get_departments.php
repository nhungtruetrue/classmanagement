<?php
include('../connect.php');

$sql_departments = "SELECT id, name FROM departments";
$result_departments = $conn->query($sql_departments);
$departments = [];

if ($result_departments && $result_departments->num_rows > 0) {
    while ($row = $result_departments->fetch_assoc()) {
        $departments[] = $row;
    }
}
?>