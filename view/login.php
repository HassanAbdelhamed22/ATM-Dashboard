<?php
session_start();
include 'controllers/DBControllers.php';
$dbController = new DBController();
$dbController->open();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // unset error message
    unset($error);

    // get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // validate input
    if (empty($username)) {
        $error = 'Username is required';
    } else if (empty($password)) {
        $error = 'Password is required';
    } else {
        // authenticate user
        $query = "SELECT id FROM atm_admin WHERE uname='$username' AND password='$password'";
        $result = mysqli_query($dbController->connection, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            // reset login attempts count
            unset($_SESSION['login_attempts']);
        
            // set session variables
            $_SESSION['username'] = $username;
            // redirect to dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            // increment login attempts count
            if (!isset($_SESSION['login_attempts'])) {
                $_SESSION['login_attempts'] = 1;
            } else {
                $_SESSION['login_attempts']++;
            }
        
            // check if user has exceeded the maximum number of login attempts
            if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
                // get the admin id for the given username
                $query = "SELECT id FROM atm_admin WHERE uname='$username'";
                $result = mysqli_query($dbController->connection, $query);
                $admin_id = mysqli_fetch_assoc($result)['id'];
        
                // check if notification has already been inserted
                $query = "SELECT COUNT(*) FROM notification WHERE admin_id='$admin_id' AND message='Admin with username $username has exceeded the maximum number of login attempts.'";
                $result = mysqli_query($dbController->connection, $query);
                $count = mysqli_fetch_assoc($result)['COUNT(*)'];
        
                if ($count == 0) {
                    // insert a notification for the admin
                    $message = "Admin with username $username has exceeded the maximum number of login attempts.";
                    $sql = "INSERT INTO notification (admin_id, message, read_status, created_at)
                            VALUES ('$admin_id', '$message', false, NOW())";
                    mysqli_query($dbController->connection, $sql);
                }
        
                // display an error message to the user
                $error = "You have exceeded the maximum number of login attempts. Please contact the administrator.";
            } else {
                $error = 'Invalid username or password';
            }
        }
    }
}

mysqli_close($dbController->connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>DASHMIN - Bootstrap Admin Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Sign In Start -->
        <div class="container">
            <div class="row justify-content-center align-items-center vh-100">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card">
                    <div class="card-body">
    <h2 class="text-center mb-4">Login</h2>
    
    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
            <div class="invalid-feedback">
                Please enter your username.
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">
                Please enter your password.
            </div>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Login</button>
        </div>
    </form>
</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign In End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>