<?php
session_start();
include("db.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) == 1)
    {
        $user = mysqli_fetch_assoc($result);

        // IMPORTANT: password is hashed in your system
        if(password_verify($password, $user['password']))
        {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role']    = $user['role'];

            header("Location: dashboard.php");
            exit();
        }
    }

    echo "<script>alert('Invalid Login'); window.location='login.php';</script>";
}
?>
