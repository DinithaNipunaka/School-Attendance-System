<?php 
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);

session_start(); // Ensure sessions are started
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Validate `userId` session
if (!isset($_SESSION['userId'])) {
    die("User not authenticated. Please log in.");
}

// Fetch the teacher's assigned class
$query = "SELECT tblclass.Id AS classId, tblclass.className 
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          WHERE tblclassteacher.Id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['userId']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("No class assigned to this teacher.");
}
$teacherClass = $result->fetch_assoc();
$stmt->close();

// Store class ID in session to be used later for student queries
$_SESSION['classId'] = $teacherClass['classId'];
$className = $teacherClass['className'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>All Students in Class</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->

    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- TopBar -->

        <!-- Container Fluid -->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
              All Students in Class (<?php echo htmlspecialchars($className); ?>)
            </h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Students in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Student List</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Admission No</th>
                        <th>Class</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // Fetch students assigned to this teacher's class
                      $query = "SELECT tblstudents.Id, tblstudents.firstName, tblstudents.lastName, 
                                       tblstudents.admissionNumber, tblclass.className
                                FROM tblstudents
                                INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                                WHERE tblstudents.classId = ?";
                      $stmt = $conn->prepare($query);
                      $stmt->bind_param("i", $_SESSION['classId']); // Use teacher's classId
                      $stmt->execute();
                      $result = $stmt->get_result();

                      $sn = 0;
                      if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                              $sn++;
                              echo "<tr>
                                      <td>{$sn}</td>
                                      <td>" . htmlspecialchars($row['firstName']) . "</td>
                                      <td>" . htmlspecialchars($row['lastName']) . "</td>
                                      <td>" . htmlspecialchars($row['admissionNumber']) . "</td>
                                      <td>" . htmlspecialchars($row['className']) . "</td>
                                    </tr>";
                          }
                      } else {
                          echo "<tr><td colspan='5' class='text-center text-danger'>No students found for this class.</td></tr>";
                      }
                      $stmt->close();
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- Row End -->

        </div>
        <!-- Container Fluid End -->
      </div>
      <!-- Content End -->

      <!-- Footer -->
      <?php include "Includes/footer.php"; ?>
      <!-- Footer End -->
    </div>
  </div>

  <!-- Scroll to Top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#dataTableHover').DataTable(); // Initialize DataTable
    });
  </script>
</body>

</html>
