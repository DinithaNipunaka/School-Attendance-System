<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Attendance_list-" . date("Y-m-d") . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<table border='1'>
    <thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Admission No</th>
            <th>Class</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>";

$cnt = 1;

$query = mysqli_query($conn, "
    SELECT 
        tblattendance.Id, 
        tblattendance.status, 
        tblattendance.dateTimeTaken, 
        tblclass.className, 
        tblstudents.firstName, 
        tblstudents.lastName, 
        tblstudents.admissionNumber 
    FROM tblattendance
    INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
    INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
    WHERE tblattendance.dateTimeTaken = CURDATE()
");

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
        $status = $row['status'] == '1' ? "Present" : "Absent";

        echo "<tr>
                <td>" . $cnt . "</td>
                <td>" . $row['firstName'] . "</td>
                <td>" . $row['lastName'] . "</td>
                <td>" . $row['admissionNumber'] . "</td>
                <td>" . $row['className'] . "</td>
                <td>" . $status . "</td>
                <td>" . $row['dateTimeTaken'] . "</td>
              </tr>";
        $cnt++;
    }
} else {
    echo "<tr><td colspan='7'>No records found for the selected date.</td></tr>";
}

echo "</tbody></table>";
?>
