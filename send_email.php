<?php
$mail_From = 'rupinderkaur183@gmail.com';
$password_From = 'Qwert@12345';

$mail_to = $mail_From;

$name = $_POST['name'];
$radio = $_POST['radio'];
$user_email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$status_message = '';

date_default_timezone_set('Etc/UTC');

require 'classes/PHPMailerAutoload.php';
$mail = new PHPMailer;

$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 465;
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth = true;
$mail->Username = $mail_From;
$mail->Password = 'awzy xdjb lfet ntgg';
$mail->setFrom($mail_From, $name);
$mail->addAddress($mail_to, 'IRegained');
$mail->Subject = 'New Contact Inquiry from ' . $radio . ', Mail from ' . $name . ' received';
$mail->msgHTML('<!DOCTYPE html>
<html>
<body>
<p><b>Person : </b>' . $radio . '</p>
<p><b>Name : </b>' . $name . '</p>
<p><b>Email : </b>' . $user_email . '</p>
<p><b>Location : </b>' . $location . '</p>
<p><b>Subject : </b>' . $subject . '</p>
<p><b>Message : </b>' . $message . '</p>
</body>
</html>');

// Database insertion for new inquiry
$createdate = date('Y-m-d H:i:s');

$link = mysqli_connect("localhost:4306", "root", "", "iregained1");

if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$sql = "INSERT INTO contact_us (name, person, mail, phone, location, subject, message, date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, 'ssssssss', $name, $radio, $user_email, $phone, $location, $subject, $message, $createdate);

if (mysqli_stmt_execute($stmt)) {
    $status_message .= " Records inserted successfully.";
} else {
    $status_message .= " ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($link);

//echo $status_message;

// Send mail and redirect asynchronously using JavaScript
echo '<script>
    setTimeout(function() {
        window.location.href = "mailSuccess.php";
    }, 0);
</script>';

if (!$mail->send()) {
    $status_message = "Mailer Error: " . $mail->ErrorInfo;
    header("Location: contact.php");
    exit; // Ensure script stops execution after redirection
}

?>
