<?php 
include 'Includes/dbcon.php';
session_start();
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
    <title>Code Camp BD - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpe00g');">
    <!-- Login Content -->
    <div class="container-login">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card shadow-sm my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-form">
                                    <h5 align="center">STUDENT ATTENDANCE SYSTEM</h5>
                                    <div class="text-center">
                                        <img src="img/logo/attnlg.jpg" style="width:100px;height:100px">
                                        <br><br>
                                        <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                                    </div>
                                    <form class="user" method="post" action="">
                                        <div class="form-group">
                                            <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                                        </div>
                                    </form>

                                    <?php
                                    if (isset($_POST['login'])) {
                                        $username = $_POST['username'];
                                        $password = md5($_POST['password']);

                                        // Check in tbladmin
                                        $adminQuery = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
                                        $adminRs = $conn->query($adminQuery);

                                        if ($adminRs->num_rows > 0) {
                                            $adminData = $adminRs->fetch_assoc();

                                            // Set session variables for admin
                                            $_SESSION['userId'] = $adminData['Id'];
                                            $_SESSION['firstName'] = $adminData['firstName'];
                                            $_SESSION['lastName'] = $adminData['lastName'];
                                            $_SESSION['emailAddress'] = $adminData['emailAddress'];

                                            echo "<script type=\"text/javascript\">
                                            window.location = (\"Admin/index.php\")
                                            </script>";
                                        } else {
                                            // Check in tblclassteacher
                                            $teacherQuery = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password'";
                                            $teacherRs = $conn->query($teacherQuery);

                                            if ($teacherRs->num_rows > 0) {
                                                $teacherData = $teacherRs->fetch_assoc();

                                                // Set session variables for class teacher
                                                $_SESSION['userId'] = $teacherData['Id'];
                                                $_SESSION['firstName'] = $teacherData['firstName'];
                                                $_SESSION['lastName'] = $teacherData['lastName'];
                                                $_SESSION['emailAddress'] = $teacherData['emailAddress'];
                                                $_SESSION['classId'] = $teacherData['classId'];

                                                echo "<script type=\"text/javascript\">
                                                window.location = (\"ClassTeacher/index.php\")
                                                </script>";
                                            } else {
                                                // Invalid credentials
                                                echo "<div class='alert alert-danger' role='alert'>
                                                Invalid Username/Password!
                                                </div>";
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>
