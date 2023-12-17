<?php
session_start();
//---------Disable Errors---------//
// ini_set('display_errors', 0);
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

//---------Enable Errors---------//
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "../config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
date_default_timezone_set('Asia/Dubai');
$date = date("Y-m-d H:i:sa");
$get_user = "SELECT * FROM admin_users WHERE mobile='".$_SESSION["mobile"]."'";
$user_query = mysqli_query($conn, $get_user);
$user_row = mysqli_fetch_assoc($user_query);
$updated_by = "".$user_row['first_name']." ".$user_row['last_name']."";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>

<body>
    <?php

    $_SESSION['apt_id'] = $_POST['id'];
    $_POST['type'];

    if (isset($_POST['submit'])) {

            if($_POST['type'] === "Contract"){
                require "contract_process.php";
            }elseif($_POST['type'] === "cancellation"){
                require "cancel_process.php";
            }elseif($_POST['type'] === "Rent"){
                require "rent_process.php";
            }elseif($_POST['type'] === "Repair"){
                require "repair_process.php";
            }elseif($_POST['type'] === "Renewal"){
                require "renewal_process.php";
            }elseif($_POST['type'] === "Parking"){
                require "parking_process.php";
            }

            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed to load process',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        // window.location.href = '../apartment.php';
                    }, 800);
                </script>
                <?php
            }

    ?>
<div style="width:100%;margin:auto;text-align:center;justify-content:center;">
    <img src="../images/loader.gif" style="width:500px;">
</div>
<div style="width:100%;margin:auto;text-align:center;justify-content:center;">
    <img src="../images/tower.png" style="width:120px;">
</div>
</body>
</html>