<?php
//============================================================================
include "connect.php";
//include "email.php";
session_start();
if(!isset($_SESSION['admin'])){
    header('location:logout.php');
    //$_SESSION['roll number']=$sno;
}
//$_SESSION['rollnumber']=$sno;
//echo $_SESSION['rollnumber'] ; 
$sno=$_GET['q'];
$_SESSION['rollnumber']=$sno;
$query="UPDATE `perpermissions_details` SET `status`='ACCEPTED'  WHERE `sno`= '$sno'";
$sql=mysqli_query($connect,$query);
if ($sql) {
    echo '<script>alert("Request Accepted")</script>';
	echo "<script>window.location='./requests.php';</script>";
    include "email.php";
}
else{
    echo '<script>alert("Failed Try Again")</script>';
	echo "<script>window.location='./requests.php';</script>";
}
//============================================================================
?>