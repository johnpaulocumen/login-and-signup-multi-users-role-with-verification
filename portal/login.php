<?php 
session_start();
$page_title = "Login";
include('includes/header.php'); 
include('includes/navbar.php'); 
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="alert">
                    <?php 
                        if(isset($_SESSION['status'])) {
                            echo "<h5>".$_SESSION['status']."</h5>";
                            unset($_SESSION["status"]);
                        }
                    ?>
                </div>

                <div class="card shadow">
                    <div class="card-header">
                        <h5>Login</h5>
                    </div>
                    <div class="card-body">

                         <form action="logincode.php" method="POST">

                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label for="">Password</label>
                                <input type="text" name="password" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label style="display: inline-block; vertical-align: top;">Role: </label>
                                <div class="medium" style="display: inline-block;">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="user_type" value="Admin" class="form-check-input" >
                                        <label class="form-check-label">Admin</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="user_type" value="Dean" class="form-check-input" >
                                        <label class="form-check-label">Dean</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="user_type" value="Taskforce" class="form-check-input" >
                                        <label class="form-check-label">Taskforce</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="user_type" value="Faculty/Stafff" class="form-check-input" >
                                        <label class="form-check-label">Faculty/Staff</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="user_type" value="Accreditor" class="form-check-input" >
                                        <label class="form-check-label">Accreditor</label>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="form-group">
                                <button type="submit" name="login_btn" class="btn btn-primary">Login</button>
                                <a href="password-reset.php" class="float-end">Forgot password?</a>
                            </div>

                         </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>