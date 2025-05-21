<?php 
include('../connect.php');

$sql_accounts = "
    SELECT 
        accounts.id,
        accounts.username AS email,
        accounts.role,
        accounts.student_id,
        accounts.teacher_id,
        students.code AS student_code,
        students.name AS student_name,
        teachers.code AS teacher_code,
        teachers.name AS teacher_name
    FROM accounts
    LEFT JOIN students ON accounts.student_id = students.id
    LEFT JOIN teachers ON accounts.teacher_id = teachers.id
";

$result_accounts = $conn->query($sql_accounts);
$student_accounts = [];
$teacher_accounts = [];

if ($result_accounts && $result_accounts->num_rows > 0) {
    while ($row = $result_accounts->fetch_assoc()) {
        if (!is_null($row['student_id'])) {
            $student_accounts[] = [
                'id' => $row['id'],
                'code' => $row['student_code'],
                'name' => $row['student_name'],
                'email' => $row['email']
            ];
        } elseif (!is_null($row['teacher_id'])) {
            $teacher_accounts[] = [
                'id' => $row['id'],
                'code' => $row['teacher_code'],
                'name' => $row['teacher_name'],
                'email' => $row['email']
            ];
        }
    }
}
?>
