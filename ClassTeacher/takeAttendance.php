<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Fetch the class and class arm for the current teacher
$query = "SELECT tblclass.className
          FROM tblclassteacher
          INNER JOIN tblclass ON tblclass.Id = tblclassteacher.classId
          WHERE tblclassteacher.Id = '$_SESSION[userId]'";
$rs = $conn->query($query);
$num = $rs->num_rows;
$rrw = $rs->fetch_assoc();

// Get today's date
$dateTaken = date("Y-m-d");

// Check if attendance for today has already been taken
$qurty = mysqli_query($conn, "SELECT * FROM tblattendance WHERE classId = '$_SESSION[classId]' AND dateTimeTaken = '$dateTaken'");
$count = mysqli_num_rows($qurty);

if ($count == 0) { // If attendance hasn't been taken yet, insert new attendance records
    $qus = mysqli_query($conn, "SELECT * FROM tblstudents WHERE classId = '$_SESSION[classId]'");
    while ($ros = $qus->fetch_assoc()) {
        $qquery = mysqli_query($conn, "INSERT INTO tblattendance (admissionNo, classId, status, dateTimeTaken) 
                                       VALUES ('$ros[admissionNumber]', '$_SESSION[classId]', '0', '$dateTaken')");
    }
}

if (isset($_POST['save'])) {
    $admissionNo = $_POST['admissionNo'];
    $check = $_POST['check'];
    $N = count($admissionNo);
    $status = "";

    // Check if attendance has already been taken
    $qurty = mysqli_query($conn, "SELECT * FROM tblattendance WHERE classId = '$_SESSION[classId]' AND dateTimeTaken = '$dateTaken' AND status = '1'");
    $count = mysqli_num_rows($qurty);

    if ($count > 0) {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Attendance has been taken for today!</div>";
    } else {
        // Update status for checked students
        for ($i = 0; $i < $N; $i++) {
            if (isset($check[$i])) { // If checkbox is checked
                $qquery = mysqli_query($conn, "UPDATE tblattendance SET status='1' WHERE admissionNo = '$check[$i]'");

                if ($qquery) {
                    $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Attendance Taken Successfully!</div>";
                } else {
                    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred!</div>";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
      <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
       <?php include "Includes/topbar.php";?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date : <?php echo $todaysDate = date("m-d-Y");?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <form method="post">
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                          <h6 class="m-0 font-weight-bold text-primary">All Student in (<?php echo $rrw['className'];?>) Class</h6>
                          <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes besides each student to take attendance!</i></h6>
                        </div>
                        <div class="table-responsive p-3">
                          <?php echo $statusMsg; ?>
                          <table class="table align-items-center table-flush table-hover">
                            <thead class="thead-light">
                              <tr>
                                <th>#</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Admission No</th>
                                <th>Class</th>
                                <th>Check</th>
                              </tr>
                            </thead>

                            <tbody>
                            <?php
                            $query = "SELECT tblstudents.Id, tblstudents.admissionNumber, tblclass.className, tblstudents.firstName,
                                      tblstudents.lastName, tblstudents.admissionNumber, tblstudents.dateCreated
                                      FROM tblstudents
                                      INNER JOIN tblclass ON tblclass.Id = tblstudents.classId
                                      WHERE tblstudents.classId = '$_SESSION[classId]'";
                            $rs = $conn->query($query);
                            $num = $rs->num_rows;
                            $sn = 0;
                            if ($num > 0) {
                                while ($rows = $rs->fetch_assoc()) {
                                    $sn++;
                                    echo "
                                    <tr>
                                      <td>".$sn."</td>
                                      <td>".$rows['firstName']."</td>
                                      <td>".$rows['lastName']."</td>
                                      <td>".$rows['admissionNumber']."</td>
                                      <td>".$rows['className']."</td>
                                      <td><input name='check[]' type='checkbox' value='".$rows['admissionNumber']."' class='form-control'></td>
                                    </tr>";
                                    echo "<input name='admissionNo[]' value='".$rows['admissionNumber']."' type='hidden' class='form-control'>";
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                            }
                            ?>
                            </tbody>
                          </table>
                          <br>
                          <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
                        </div>
                      </div>
                    </div>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>
