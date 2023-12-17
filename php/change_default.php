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


    if (isset($_POST['submit'])) {

            if($_POST['type'] === "rental0"){
                $sql = "UPDATE `apartments` SET default_rent='".$_POST['rental']."' WHERE bedroom=0";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Rental Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "rental1"){
                $sql = "UPDATE `apartments` SET default_rent='".$_POST['rental']."' WHERE bedroom=1";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Rental Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "rental2"){
                $sql = "UPDATE `apartments` SET default_rent='".$_POST['rental']."' WHERE bedroom=2";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Rental Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "security"){
                $sql = "UPDATE `apartments` SET default_security='".$_POST['security']."'";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Security Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "insurance"){
                $sql = "UPDATE `apartments` SET default_insurance='".$_POST['insurance']."'";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Insurance Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "service"){
                $sql = "UPDATE `apartments` SET default_service='".$_POST['service']."'";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Service Charge Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }elseif($_POST['type'] === "parking"){
                $sql = "UPDATE `apartments` SET default_parking='".$_POST['parking']."'";
                $query = mysqli_query($conn, $sql);
                if ($query == true) {
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Parking Rental Amount updated successfully',
                            showConfirmButton: false,
                            // timer: 2000
                        });

                        setTimeout(function () {
                            window.location.href = '../settings.php';
                        }, 800);
                    </script>
                    <?php
                }
            }

            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed to process',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../settings.php';
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