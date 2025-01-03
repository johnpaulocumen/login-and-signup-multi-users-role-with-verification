<?php 
session_start();
include('dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($name,$user_type,$email,$verify_token)
{
    $mail = new PHPMailer(true);
    // $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->SMTPAuth   = true;

    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Username   = 'xxxxxxxx@gmail.com';                     //SMTP username
    $mail->Password   = 'xxxx xxxx xxxx xxxx';                               //SMTP password

    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('xxxxxxxx@gmail.com', $name);
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Email Verification from QAMS";

    $email_template = "
    <h3>You have successfully registered as $user_type, with QAMS.</h3>
    <h3>Verify your email address to login with the given link below.</h3>
    <br/><br/>
    <a href='http://localhost/login-and-signup-multi-users-role-with-verification/verify-email.php?token=$verify_token'>Click here</a>
    ";

    $mail->Body = $email_template;
    $mail->send();
    echo 'Message has been sent';
}

if(isset($_POST['signup_btn']))
{
    $name = $_POST['name'];
    $user_type = $_POST['user_type'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $verify_token = md5(rand());

    if(empty($name) || empty($user_type) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['status'] = "All fields are required.";
        header("Location: signup.php");
        exit(0); // Stop further execution
    }

    if ($user_type == 'Default select user type') {
        $_SESSION['status'] = "Please select a valid user type.";
        header("Location: signup.php");
        exit(0); // Stop further execution
    }

    if ($user_type == 'Dean' || $user_type == 'Taskforce') {
        $_SESSION['status'] = "To create an account for Dean or Taskforce, please ask the admin to create your account.";
        header("Location: signup.php");
        exit(0); // Stop further execution
    }

    // Check if confirm password matches password
    if($password !== $confirm_password) {
        $_SESSION['status'] = "Passwords do not match.";
        header("Location: signup.php");
        exit(0); // Stop further execution
    }

    //email exist or not
    $check_email_query = "SELECT email FROM users WHERE email='$email' LIMIT 1";
    $check_email_query_run = mysqli_query($con, $check_email_query);

    if(mysqli_num_rows($check_email_query_run) > 0)
    {
        $_SESSION['status'] = "Email address is already exists.";
        header("Location: signup.php");
    }

    else
    {
        //Insert User Data
        $query = "INSERT INTO users(name,user_type,email,password,confirm_password,verify_token) VALUES ('$name','$user_type','$email','$password','$confirm_password','$verify_token')";
        $query_run = mysqli_query($con, $query);

        if($query_run)
        {
            sendemail_verify("$name","$user_type", "$email", "$verify_token");
            $_SESSION['status'] = "The registration was successful. Please check your email to verify the account!";
            header("Location: signup.php");
        }
        else 
        {
            $_SESSION['status'] = "Registration Failed";
            header("Location: signup.php");
        }

    }

}


?>