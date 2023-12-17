<?php
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <meta property="og:image" content="images/preview.jpg">
    <title>Saleel Real Estate</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="css/style-en.css">
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/fdf5fc6483.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script type="text/javascript"
        src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <!-- Date Picker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
</head>
<style>
.header {
    background-color: lightgrey;
}
</style>

<body>
    <div class="header">
        <div class="home" id="home" onclick="home()" style="border-right:0px;">
            <?php
            if($_SESSION['tower'] === '1'){
                ?>
            <a href="dashboard.php" style="width:300px;text-decoration:none;">
                <?php
            }elseif($_SESSION['tower'] === '2'){
                ?>
            <a href="dashboard_tower_2.php" style="width:300px;text-decoration:none;">
                <?php
            }elseif($_SESSION['tower'] === '3'){
                ?>
            <a href="dashboard.php" style="width:300px;text-decoration:none;">
                <?php
            }
            ?>
                <!-- <div class="home-icon"><i class="fa-solid fa-house"></i></div> -->
                <div class="home-logo" style="display:flex;"><img src="images/logo2.png" style="width:50%;">
                <p style="
                    color: white;
    font-size: 25px;
    border-left: 2px solid;
    margin: auto;
    padding-left: 10px;
    margin-top: 22px;
    line-height: 1;
    padding-bottom: 0px;
    margin-bottom: 0px;">Tower <?php echo $_SESSION['tower']; ?></p>
                </div>
            </a>
        </div>
        <div class="menu1" style="width:60px;border-left:0px;background:grey;">
            <a href="landing.php" style="color:white;padding-bottom: 0px;padding-top: 30px;">
            <i class="fa-regular fa-square-caret-down"></i>
            </a>
        </div>
        <div class="menu1">
            <a href="transactions.php">
            <i class="fa-regular fa-square-check"></i>Transactions
            </a>
        </div>
        <div class="menu1">
            <a href="contracts.php">
            <i class="fa-solid fa-file-circle-check"></i>Contracts
            </a>
        </div>
        <div class="menu1">
            <a href="rents.php">
            <i class="fa-solid fa-money-check-dollar"></i>Rents
            </a>
        </div>
        <div class="menu1">
            <?php
            if($_SESSION['tower'] === '1'){
                ?>
            <a href="parking_data.php">
                <?php
            }elseif($_SESSION['tower'] === '2'){
                ?>
            <a href="parking_data_tower_2.php">
                <?php
            }elseif($_SESSION['tower'] === '3'){
                ?>
            <a href="parking_data.php">
                <?php
            }
            ?>
            <i class="fa-solid fa-square-parking"></i>Parkings
            </a>
        </div>
        <div class="menu1">
            <a href="maintenance.php">
            <i class="fa-solid fa-file-invoice"></i>Maintenance
            </a>
        </div>
        <div class="menu1">
            <a href="settings.php">
            <i class="fa-solid fa-gear"></i>Settings
            </a>
        </div>
        <div class="menu6" id="menu6">
            <a href="logout.php">
                <i class="fa-solid fa-power-off"></i>Logout
            </a>
        </div>
    </div>
    </header>
    <?php include "scripts.php";?>