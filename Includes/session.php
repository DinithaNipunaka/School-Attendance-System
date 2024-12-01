<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start a session only if one doesn't already exist
}

if (!isset($_SESSION['userId'])) {
    echo "<script type=\"text/javascript\">
          window.location = ('../index.php');
          </script>";
    exit;
}
?>
