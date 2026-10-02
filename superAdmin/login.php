<?php session_start(); 
include('../includes/dbConfig.php'); 

$message = '';
$cond = "";

?>



<!DOCTYPE html>

<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>KINFOMEDIA</title>

    <meta name="description"
        content="Most Powerful &amp; Comprehensive Bootstrap 5 Admin Dashboard built for developers!" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/">


    <!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <script>
    (function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s),
            dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            '../../../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-5DDHKGP');
    </script>
    <!-- End Google Tag Manager -->

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template-free/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">


    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />


    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />


    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css">

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>

</head>

<body>

    <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0"
            style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <!-- <div id="messageBox"></div> -->
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <span class="app-brand-logo demo">

                                <img src="<?php echo $comp_logo; ?>" alt="Logo" style="height:50px;">
                            </span>
                            <!-- <span class="app-brand-text demo text-heading fw-bold">sneat</span> -->
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-1">Welcome to <?php echo $comp_name; ?></h4>
                        <p class="mb-6">Please sign-in to your account and start the adventure</p>

                        <form method="POST" id="formAuthentication" class="mb-6">
                            <div class="mb-6">
                                <label for="email" class="form-label">Email or Username</label>
                                <input type="text" class="form-control" id="email" name="username"
                                    placeholder="Enter your email or username" autofocus>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between mt-8">
                                    <div class="form-check mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="remember-me">
                                        <label class="form-check-label" for="remember-me">
                                            Remember Me
                                        </label>
                                    </div>
                                    <!-- <a href="auth-forgot-password-basic.html">
                                        <span>Forgot Password?</span>
                                    </a> -->
                                </div>
                            </div>
                            <div class="mb-6">
                                <!-- <button  name="submit-button" >Login</button> -->
                                <input type="submit" name="submit" class="btn btn-primary d-grid w-100" value="Login">
                            </div>
                        </form>

                        <!-- <p class="text-center">
                            <span>New on our platform?</span>
                            <a href="auth-register-basic.html">
                                <span>Create an account</span>
                            </a>
                        </p> -->
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <script src="../assets/js/main.js"></script>

    <script async defer src="../../../buttons.github.io/buttons.js"></script>

</body>

</html>

<?php 
if(isset($_POST['submit'])){
    $username_1 = $_POST['username'];
    $password_1 = $_POST['password'];

    if(empty($username_1) || empty($password_1)){
        // $message = '<div class="alert alert-warning" role="alert">Please fill both username and password!</div>';
        ?>
<script>
$(document).ready(function() {
    iziToast.warning({
        title: "Warning",
        message: "Please fill both username and password!",
        position: "topRight"
    });
});
</script>
<?php
    } else {
        
        $sql = "SELECT * FROM tbl_user WHERE username='$username_1' AND password='$password_1' AND status='1'";
        // echo $sql;
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) >0){
            if($row = mysqli_fetch_assoc($result)){
                $username = $row['username'];
                $password = $row['password'];
                $rank = $row['rank'];
                $_SESSION['loggedInUser']= $username;
                $logged_in_at = date('Y-m-d H:i:s');

                // if($username == $username_1 && $password == $password_1){
                    $sql_session = "insert into tbl_login(username,logged_in_at) Values('$username','$logged_in_at')";
                    $result_session = mysqli_query($conn, $sql_session);
                    if(isset($_SESSION['url'])){
                        $url = $_SESSION['url']; // holds url for last page visited.
                    }else{
                        if($rank == 'Admin')
                        {
                            $url = "dashboard";
                        } else if($rank == 'Hr'){
                            $url = 'hrDashboard';
                        }
                    } 
                // } else{
                //     $message = '<div class="alert alert-danger" role="alert">Please enter a valid username and password!</div>';
                // }
                
                
                ?>
<script type="text/javascript">
window.location = "<?= $url; ?>";
</script>
<?php
            }
        } else{
            // $message = '<div class="alert alert-danger" role="alert">Please enter a valid username and password!</div>';
            ?>
<script>
$(document).ready(function() {
    iziToast.warning({
        title: "Error",
        message: "Please enter a valid username and password!",
        position: "topRight"
    });
});
</script>
<?php
        }

       
    }
}
?>
<?php include('../includes/script.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("messageBox").innerHTML = `<?php echo $message; ?>`;
});
</script>