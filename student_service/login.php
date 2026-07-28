<?php
session_start();
include('db.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Password Check
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid Password!";
            }
        } else {
            $error = "User not found!";
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Login</title>

    <link rel="stylesheet" href="login.css" />

    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    />
  </head>
  <body>
    <div class="container">
      <div class="login-box">
        <div class="logo">
          <h1>WELCOME TO PORTAL</h1>
          <p>Student Portal</p>
        </div>

        <!-- ভুল থাকলে Error Message দেখাবে -->
        <?php if (!empty($error)): ?>
          <p style="color: red; text-align: center; margin-bottom: 15px; font-weight: 500;">
            <?php echo htmlspecialchars($error); ?>
          </p>
        <?php endif; ?>

        <!-- Form action & method আপডেট করা হয়েছে -->
        <form action="login.php" method="POST">
          <div class="input-group">
            <label>Email / Student ID</label>

            <div class="input-box">
              <i class="fa-solid fa-user"></i>
              <!-- name="email" যোগ করা হয়েছে -->
              <input type="email" name="email" placeholder="Enter Email" required />
            </div>
          </div>

          <div class="input-group">
            <label>Password</label>

            <div class="input-box">
              <i class="fa-solid fa-lock"></i>
              <!-- name="password" যোগ করা হয়েছে -->
              <input type="password" name="password" placeholder="Enter Password" required />
            </div>
          </div>

          <button type="submit">Login</button>
        </form>

        <div class="extra">
          <a href="#">Forgot Password?</a>

          <br /><br />

          <a href="../home.html">⬅️ Back to Home</a>
        </div>
      </div>
    </div>
  </body>
</html>