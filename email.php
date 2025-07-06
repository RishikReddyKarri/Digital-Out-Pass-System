<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'connect.php'; // Assuming your connection file is one level above the current directory

try {
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'digitaloutpass@gmail.com';
    $mail->Password = 'atsx seau uixf lvzd';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('digitaloutpass@gmail.com');

    $mail->addReplyTo('digitaloutpass@gmail.com');
    $mail->isHTML(true);
    $mail->Subject = 'Digital Outpass System';

    // Fetch recipient email and status from respective tables
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['rollnumber']) && !empty($_SESSION['rollnumber'])) {
        $sno = $_SESSION['rollnumber'];  
    echo $sno ; 
    echo "hi";
    $query = "SELECT sd.email, pd.status 
              FROM student_details sd 
              INNER JOIN perpermissions_details pd ON sd.rollnumber = pd.rollnumber where pd.sno= '$sno'"; 
    $result = mysqli_query($connect, $query);
    //echo $_SESSION['rollnumber'];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $recipientEmail = $row['email'];
            $status = $row['status'];
            $mail->addAddress($recipientEmail);

            // Set mail body based on the status
            if ($status == 'ACCEPTED') {
                $mail->Body = "<h2>Request Accepted</h2><p>Your request has been accepted.</p>";
            } else if($status != 'ACCEPTED') {
                $mail->Body = "<h2>Request Rejected</h2><p>Your request has been rejected due to  $status </p>";
            } 

            if ($mail->send()) {
                // Sent successfully
                echo "Message has been sent to $recipientEmail with status: $status";
            } else {
                echo "Error sending notification to $recipientEmail: " . $mail->ErrorInfo;
            }

            // Clear all recipients for the next iteration
            $mail->clearAddresses();
        }
    } else {
        echo "No recipient found in permission details";
    }
}

} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>
