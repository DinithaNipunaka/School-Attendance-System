<?php
// Include database connection file
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Get the record ID from the URL
if(isset($_GET['Id']) && isset($_GET['classArmId'])) {
    $id = $_GET['Id'];
    $classArmId = $_GET['classArmId'];

    // Fetch the record details based on the ID
    $sql = "SELECT * FROM tblclassteacher WHERE Id = ? AND classArmId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $classArmId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if record exists
    if($result->num_rows == 1) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found!";
        exit;
    }
} else {
    echo "Invalid request!";
    exit;
}

// Update the record
if(isset($_POST['update'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $emailAddress = $_POST['emailAddress'];
    $phoneNo = $_POST['phoneNo'];
    $className = $_POST['className'];
    $classArmName = $_POST['classArmName'];

    $sql = "UPDATE students SET firstName=?, lastName=?, emailAddress=?, phoneNo=?, className=?, classArmName=? WHERE Id=? AND classArmId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssii", $firstName, $lastName, $emailAddress, $phoneNo, $className, $classArmName, $id, $classArmId);

    if($stmt->execute()) {
        echo "<script>alert('Record updated successfully!'); window.location.href='your_table_page.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link href="img/logo/attnlg.jpg" rel="icon">
<?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet"> <!-- Link to your CSS file for styling -->
</head>
<body>
    <h2>Edit Record</h2>
    <form action="" method="POST">
        <label>First Name:</label>
        <input type="text" name="firstName" value="<?php echo $row['firstName']; ?>" required><br>

        <label>Last Name:</label>
        <input type="text" name="lastName" value="<?php echo $row['lastName']; ?>" required><br>

        <label>Email Address:</label>
        <input type="email" name="emailAddress" value="<?php echo $row['emailAddress']; ?>" required><br>

        <label>Phone Number:</label>
        <input type="text" name="phoneNo" value="<?php echo $row['phoneNo']; ?>" required><br>

        <label>Class Name:</label>
        <input type="text" name="className" value="<?php echo $row['className']; ?>" required><br>

        <label>Class Arm Name:</label>
        <input type="text" name="classArmName" value="<?php echo $row['classArmName']; ?>" required><br>

        <button type="submit" name="update">Update</button>
    </form>
</body>
</html>
