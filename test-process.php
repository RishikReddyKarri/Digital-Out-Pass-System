<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location:logout.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rollnumber = $_POST['rollnumber']; // Retrieve rollnumber from the form
    
    // Retrieve other form fields
    
    $file_name = $_FILES['student_image']['name'];
    $file_loc = $_FILES['student_image']['tmp_name'];
    $file_size = $_FILES['student_image']['size'];
    $file_type = $_FILES['student_image']['type'];
    $folder = "upload/";

    // Check if the file was uploaded successfully
    if (is_uploaded_file($file_loc)) {
        if (move_uploaded_file($file_loc, $folder . $file_name)) {
            $img = file_get_contents($folder . $file_name);
            include "connect.php";
            $sql = "UPDATE `student_details` SET `student_image`=? WHERE `rollnumber`=?";
            $stmt = mysqli_prepare($connect, $sql);
            // Assuming `student_image` is a BLOB type in the database
            mysqli_stmt_bind_param($stmt, "si", $img, $rollnumber); // Assuming 'si' for BLOB and integer
            mysqli_stmt_execute($stmt);
            $check = mysqli_stmt_affected_rows($stmt);
            // ... rest of your code ...
        } else {
            // Handle file move failure
        }
    } else {
        // Handle file upload failure
    }
}
?>
