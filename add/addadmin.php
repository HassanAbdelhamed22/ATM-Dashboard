<?php 
session_start();
include '../controllers/DBControllers.php';
$dbController = new DBController();
$dbController->open();

// check for database connection errors
if (!$dbController->connection) {
    die("Connection failed: " . mysqli_connect_error());
}
        
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $uname = $_POST['uname'];
    // escape input data to prevent SQL injection
    $name = mysqli_real_escape_string($dbController->connection, $name);
    $email = mysqli_real_escape_string($dbController->connection, $email);
    $password = mysqli_real_escape_string($dbController->connection, $password);
    $uname = mysqli_real_escape_string($dbController->connection, $uname);
    // Check for duplicate account number
    $query = "SELECT * FROM atm_admin WHERE email = '$email' OR uname = '$uname'";
    $result = mysqli_query($dbController->connection, $query);
    if(mysqli_num_rows($result) > 0){
        // Account number already exists
        $_SESSION['exist_msg'] = "Account number already exists";
    }
    else{
        $query = "INSERT INTO atm_admin (name, email, password,uname) VALUES ('$name', '$email', '$password', '$uname')";
    
    $result = mysqli_query($dbController->connection, $query);
    if(!$result){
        die("Query failed: " . mysqli_error($dbController->connection));
    }
    else{
        $_SESSION['update_msg'] = "You have successfully added the data";
    }

    }

    
} 
if(isset($_SESSION['exist_msg'])){
    echo '<div class="position-fixed w-100" style="top: 0; left: 0; z-index: 9999;">';
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo '<strong>Failed!</strong> ' . $_SESSION['exist_msg'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    echo '</div>';
    // Unset the session variable to remove the message
    unset($_SESSION['exist_msg']);
}
// enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
 


// Check if update message is set
if(isset($_SESSION['update_msg'])){
    echo '<div class="position-fixed w-100" style="top: 0; left: 0; z-index: 9999;">';
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo '<strong>Success!</strong> ' . $_SESSION['update_msg'];
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
    echo '</div>';
    // Unset the session variable to remove the message
    unset($_SESSION['update_msg']);
}
?>
<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="../view/dashboard.php" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <?php
                

                // Get the total number of users
                $query = "SELECT name AS n FROM user";
    $result = mysqli_query($dbController->connection, $query);
    if(!$result){
        die("Query failed: " . mysqli_error($dbController->connection));
    }

    $row = mysqli_fetch_assoc($result);
    $total = $row['n'];

    // Display the total value
    ?>
                <h6 class="mb-0"><?php echo $total ?></h6>
                <span>Admin</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="../view/dashboard.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user me-2"></i>User</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../view/user.php" class="dropdown-item">User Management</a>
                    <a href="../add/adduser.php" class="dropdown-item">Add User</a>
                </div>
            </div>
            <a href="../view/transaction.php" class="nav-item nav-link"><i class="fa fa-exchange-alt me-2"></i>Transactions</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user-alt me-2"></i>Account</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../view/account.php" class="dropdown-item">Account</a>
                    <a href="../add/addaccount.php" class="dropdown-item">Add Account</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-money-bill me-2"></i>ATM</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../view/atm.php" class="dropdown-item">ATM</a>
                    <a href="../add/addatm.php" class="dropdown-item">Add ATM</a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-sim-card  me-2"></i>Credit Card</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../view/card.php" class="dropdown-item">Credit Card</a>
                    <a href="../add/addcard.php" class="dropdown-item">Add Credit Card</a>
                </div>
            </div>
            <a href="../view/report.php" class="nav-item nav-link"><i class="fa fa-file me-2"></i>Report</a>
            <a href="../view/notification.php" class="nav-item nav-link"><i class="fa fa-bell me-2"></i>Notification</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-user me-2"></i>Admin</a>
                <div class="dropdown-menu bg-transparent border-0">
                    <a href="../view/admin.php" class="dropdown-item">ADMIN Management</a>
                    <a href="../add/addADMIN.php" class="dropdown-item">Add Admin</a>
                </div>
            </div>
            <a href="../view/security.php" class="nav-item nav-link"><i class="fa fa-shield-alt me-2"></i>Security</a>
        </div>
    </nav>
</div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <form class="d-none d-md-flex ms-4">
                    <input class="form-control border-0" type="search" placeholder="Search">
                </form>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">Jhon send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notificatin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Profile updated</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">New user added</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <h6 class="fw-normal mb-0">Password changed</h6>
                                <small>15 minutes ago</small>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="../img/user.jpg" alt="" style="width: 40px; height: 40px;">
                            <?php
                            // Get the total value from the user table
    $query = "SELECT name AS n FROM user";
    $result = mysqli_query($dbController->connection, $query);
    if(!$result){
        die("Query failed: " . mysqli_error($dbController->connection));
    }

    $row = mysqli_fetch_assoc($result);
    $total = $row['n'];

    // Display the total value
    ?>
                            <span class="d-none d-lg-inline-flex"><?php echo $total ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            <a href="myprofile.html" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="#" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->



            <!-- Form Start -->
            <div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-sm-12 col-xl-6 w-100">
            <div class="bg-light rounded h-100 p-4">
                <h6 class="mb-4">Add Admin</h6>
                <?php if(isset($_SESSION['update_msg'])): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['update_msg']; ?>
                    </div>
                    <?php unset($_SESSION['update_msg']); ?>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="uname" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="uname" name="uname">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
            <!-- Footer Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light rounded-top p-4">
        <div class="row">
            <div class="col-12 col-sm-6 text-center text-sm-start">
                &copy; <a href="#">Your Site Name</a>, All Right Reserved. 
            </div>
            <div class="col-12 col-sm-6 text-center text-sm-end">
                <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                Designed By <a href="https://htmlcodex.com">HTML Codex</a>
            </div>
        </div>
    </div>
    </div>
    <!-- Footer End -->
    </div>
    <!-- Content End -->
    
    
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/chart/chart.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    
    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
    </body>
    
    </html>