<?php
session_start();
include 'controllers/DBControllers.php';

$dbController = new DBController();
$dbController->open();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get form data
    $name = mysqli_real_escape_string($dbController->connection, $_POST['name']);
    $username = mysqli_real_escape_string($dbController->connection, $_POST['username']);
    $email = mysqli_real_escape_string($dbController->connection, $_POST['email']);
    $password = mysqli_real_escape_string($dbController->connection, $_POST['password']);

    // validate input
    if (empty($username) && empty($password)) {
        // both username and password are empty
        $error = '';
    } else if (empty($username)) {
        // username is empty
        $error = '';
    } else if (empty($password)) {
        // password is empty
        $error = '';
    } else {
        // Check if username or email already exists
        $query = "SELECT * FROM atm_admin WHERE uname = '$username' OR email = '$email'";
        $result = mysqli_query($dbController->connection, $query);

        if (mysqli_num_rows($result) > 0) {
            // username or email already exists
            $error = 'Username or email already exists';
        } else {
            // Hash password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into database
            $query = "INSERT INTO atm_admin (name, uname, email, password) VALUES ('$name', '$username', '$email', '$password_hashed')";
            $result = mysqli_query($dbController->connection, $query);

            if ($result) {
                // set session variables
                $_SESSION['username'] = $username;
                // redirect to dashboard
                header('Location: login.php');
                exit;
            } else {
                $error = 'Error creating user';
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
        
        <!-- Spinner End -->

        <!-- Sign Up Start -->
        <div class="container-fluid">
            <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4">
                    <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2>Sign Up</h2>
                        </div>

                        <?php if (!isset($error) && !empty($error)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group mb-3">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Sign Up</button>
                            </div>
                        </form>

                        <div class="mt-3 text-center">
                            <p class="mb-0">Already have an account? <a href="login.php">Sign In</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sign Up End -->
    </div>

    <!-- Libraries JS File -->
    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Customized Bootstrap JS File -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Template JS File -->
    <script src="js/scripts.js"></script>
</body>
</html>