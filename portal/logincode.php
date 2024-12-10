<?php
session_start();
include('dbcon.php');

if (isset($_POST['login_btn'])) {
    if (!empty(trim($_POST['email'])) && !empty(trim($_POST['password'])) && !empty(trim($_POST['user_type']))) {
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $selected_user_type = mysqli_real_escape_string($con, $_POST['user_type']);

        $login_query = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        if (mysqli_num_rows($login_query_run) > 0) {
            $row = mysqli_fetch_array($login_query_run);

            if ($row['verify_status'] == "1") {
                if ($row['user_type'] == $selected_user_type) {
                    $_SESSION['authenticated'] = TRUE;
                    $_SESSION['auth_user'] = [
                        'username' => $row['name'],
                        'email' => $row['email'],
                        'user_type' => $selected_user_type,
                    ];

                    switch ($selected_user_type) {
                        case "Admin":
                            $redirect_url = "admin_dashboard.php";
                            break;
                        case "Dean":
                        case "Taskforce":
                            $redirect_url = "dean_dashboard.php";
                            break;
                        case "Faculty/Staff":
                            $redirect_url = "staff_dashboard.php";
                            break;
                        case "Accreditor":
                            $redirect_url = "accreditor_dashboard.php";
                            break;
                        default:
                            if (!isset($_SESSION['status'])) {
                                $_SESSION['status'] = "Invalid user type.";
                            }
                            $redirect_url = "login.php";
                            break;
                    }
                    if (!isset($_SESSION['status'])) {
                        $_SESSION['status'] = "You are logged in successfully.";
                    }
                    header("Location: $redirect_url");
                    exit(0);
                } else {
                    if (!isset($_SESSION['status'])) {
                        $_SESSION['status'] = "Please select the correct user type for your account.";
                    }
                    header("Location: login.php");
                    exit(0);
                }
            } else {
                if (!isset($_SESSION['status'])) {
                    $_SESSION['status'] = "Please verify your email address to login.";
                }
                header("Location: login.php");
                exit(0);
            }
        } else {
            if (!isset($_SESSION['status'])) {
                $_SESSION['status'] = "Invalid email or password.";
            }
            header("Location: login.php");
            exit(0);
        }
    } else {
        if (!isset($_SESSION['status'])) {
            $_SESSION['status'] = "All fields are mandatory.";
        }
        header("Location: login.php");
        exit(0);
    }
}
?>
