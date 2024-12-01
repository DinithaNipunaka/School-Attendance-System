<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$filename = "Attendance_list";
$dateTaken = date("Y-m-d");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report</title>
    <style>
        /* General Reset and Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: #555;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            max-width: 1200px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }

        th {
            background-color: #343a40;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 14px;
        }

        td {
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        tr:hover {
            background-color: #e9ecef;
        }

        .no-records {
            text-align: center;
            font-style: italic;
            color: #666;
        }

        /* Status Styling */
        td.status-icon {
            display: flex; /* Align icon and text in a single row */
            align-items: center;
            gap: 8px; /* Add spacing between the icon and text */
            text-align: left;
        }

        .status-present {
            color: #198754;
        }

        .status-absent {
            color: #dc3545;
        }

        /* Button Styling */
        .button-container {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .btn {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print {
            background-color: #198754;
        }

        .btn-download {
            background-color: #007bff;
        }

        .btn:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            table {
                font-size: 12px;
            }

            .btn {
                font-size: 14px;
                padding: 10px 15px;
            }
        }
    </style>
    <!-- Icons from Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<div class="container">
    <h2>Attendance Report</h2>

    <div class="button-container">
        <button class="btn btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Print
        </button>
        <a href="download_report.php" class="btn btn-download">
            <i class="fas fa-download"></i> Download
        </a>
    </div>

    <table>
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
        <tbody>
            <?php
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
                WHERE tblattendance.dateTimeTaken = '$dateTaken' 
                  AND tblattendance.classId = '$_SESSION[classId]'
            ");

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $status = $row['status'] == '1' ? "Present" : "Absent";
                    $statusClass = $status == "Present" ? "status-present" : "status-absent";
                    $icon = $status == "Present" ? "fas fa-check-circle" : "fas fa-times-circle";

                    echo "<tr>
                            <td>" . $cnt . "</td>
                            <td>" . htmlspecialchars($row['firstName']) . "</td>
                            <td>" . htmlspecialchars($row['lastName']) . "</td>
                            <td>" . htmlspecialchars($row['admissionNumber']) . "</td>
                            <td>" . htmlspecialchars($row['className']) . "</td>
                            <td class='status-icon $statusClass'>
                                <i class='$icon'></i> $status
                            </td>
                            <td>" . htmlspecialchars($row['dateTimeTaken']) . "</td>
                          </tr>";
                    $cnt++;
                }
            } else {
                echo "<tr><td colspan='7' class='no-records'>No records found for the selected date.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
