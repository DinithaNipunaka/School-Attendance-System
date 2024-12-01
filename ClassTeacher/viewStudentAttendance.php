<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>
<body id="page-top">
  <div id="wrapper">
    <?php include "Includes/sidebar.php"; ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include "Includes/topbar.php"; ?>

        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">View Student Attendance</h1>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Attendance Form</h6>
            </div>
            <div class="card-body">
              <form method="post" class="needs-validation" novalidate>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="admissionNumber">Select Student <span class="text-danger">*</span></label>
                    <select id="admissionNumber" name="admissionNumber" class="form-control" required>
                      <option value="">--Select Student--</option>
                      <?php
                      $qry = "SELECT Id, firstName, lastName, admissionNumber FROM tblstudents WHERE classId = '$_SESSION[classId]' ORDER BY firstName";
                      $result = $conn->query($qry);
                      while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['admissionNumber']}'>{$row['firstName']} {$row['lastName']}</option>";
                      }
                      ?>
                    </select>
                    <div class="invalid-feedback">Please select a student.</div>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="type">Type <span class="text-danger">*</span></label>
                    <select id="type" name="type" class="form-control" required>
                      <option value="">--Select--</option>
                      <option value="1">All</option>
                      <option value="2">Single Date</option>
                      <option value="3">Date Range</option>
                    </select>
                    <div class="invalid-feedback">Please select a type.</div>
                  </div>
                </div>

                <button type="submit" name="view" class="btn btn-primary btn-block">View Attendance</button>
              </form>
            </div>
          </div>

          <?php if (isset($_POST['view'])): ?>
            <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Results</h6>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Admission No</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $admissionNumber = $_POST['admissionNumber'];
                    $type = $_POST['type'];

                    if ($type == "1") {
                      $query = "SELECT * FROM tblattendance WHERE admissionNo = '$admissionNumber' AND classId = '$_SESSION[classId]'";
                    }

                    $result = $conn->query($query);
                    $sn = 1;
                    while ($row = $result->fetch_assoc()) {
                      $status = $row['status'] == '1' ? "Present" : "Absent";
                      echo "<tr>
                              <td>{$sn}</td>
                              <td>{$row['admissionNo']}</td>
                              <td>{$status}</td>
                              <td>{$row['dateTimeTaken']}</td>
                            </tr>";
                      $sn++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    (function () {
      'use strict';
      window.addEventListener('load', function () {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function (form) {
          form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>
</body>
</html>
