<?php
include('../connect.php');

$all_subjects = [];

$query = "SELECT subject_id, subject_name, description FROM subjects";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $all_subjects[] = $row;
    }
}
?>
