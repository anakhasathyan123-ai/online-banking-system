<?php
session_start();

/* USER LOGIN CHECK */
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
