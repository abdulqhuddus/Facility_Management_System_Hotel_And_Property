<?php session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
require "config.php";
$get_vac = "SELECT count(id) as Vacant FROM apartments WHERE status=0";
$res_vac = mysqli_query($conn, $get_vac);
$row_vac = mysqli_fetch_assoc($res_vac);

$get_occ = "SELECT count(id) as Occupied FROM apartments WHERE status=1";
$res_occ = mysqli_query($conn, $get_occ);
$row_occ = mysqli_fetch_assoc($res_occ);

$get_clr = "SELECT count(next_pay_date) as 'Cleared' FROM apartments WHERE status=1 AND contract_to >= CURDATE() AND next_pay_date > DATE_ADD(CURRENT_DATE(), INTERVAL 365 DAY)";
$res_clr = mysqli_query($conn, $get_clr);
$row_clr = mysqli_fetch_assoc($res_clr);

// $get_del = "SELECT count(next_pay_date) as 'Delayed' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 11 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 20 DAY)";
$get_del = "SELECT count(next_pay_date) as 'Delayed' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 0 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 20 DAY)";
$res_del = mysqli_query($conn, $get_del);
$row_del = mysqli_fetch_assoc($res_del);

// $get_non = "SELECT count(next_pay_date) as 'UnPaid' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 0 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 10 DAY)";
$get_non = "SELECT count(next_pay_date) as 'UnPaid' FROM apartments WHERE status=1 AND next_pay_date < CURRENT_DATE()";
$res_non = mysqli_query($conn, $get_non);
$row_non = mysqli_fetch_assoc($res_non);

$get_expired = "SELECT count(id) as Expired FROM apartments WHERE status=1 AND contract_to <= CURDATE() AND next_pay_date > DATE_ADD(CURRENT_DATE(), INTERVAL 365 DAY)";
$res_expired = mysqli_query($conn, $get_expired);
$row_expired = mysqli_fetch_assoc($res_expired);
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
    <!-- Main css -->
    <link rel="stylesheet" href="css/style-en.css">
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/fdf5fc6483.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <div class="login-section">
        <!--<div class="sampledesk"><img src="images/sampledesk.png"></div>
  <div class="samplemob"><img src="images/samplemob.png"></div>-->
        <div style="width:100%;margin:auto;text-align:center;">
            <img src="images/tower.png" style="width: 150px;">
        </div>
        <div class="login" style="width:97%;max-width:1800px;margin-top:10px;">
            <!--<img src="images/logo.png">-->
            <div class="login-wrapper">
                <div class="dual-mode">
                    <div class="dual-1" style="border: 30px solid white;padding: 20px;">
                        <div style="width:100%;display:block;">
                            <div style="width:100%;display:flex;padding-bottom:10px;">
                                <a href="vacant.php?tower=1" style="width:100%;background:green;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Vacant<br><span style="font-size:20px;"><?php echo $row_vac['Vacant']; ?></span>
                                </a>
                                <a href="occupied.php?tower=1" style="width:100%;background:darkblue;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Occupied<br><span style="font-size:20px;"><?php echo $row_occ['Occupied']; ?></span>
                                </a>
                                <a href="cleared.php?tower=1" style="width:100%;background:purple;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Cleared<br><span style="font-size:20px;"><?php echo $row_clr['Cleared']; ?></span>
                                </a>
                                <a href="expired.php?tower=1" style="width:100%;background:yellow;color:black;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Expired<br><span style="font-size:20px;"><?php echo $row_expired['Expired']; ?></span>
                                </a>
                                <a href="delayed.php?tower=1" style="width:100%;background:orange;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Upcoming<br><span style="font-size:20px;"><?php echo $row_del['Delayed']; ?></span>
                                </a>
                                <a href="unpaid.php?tower=1" style="width:100%;background:red;color:white;font-size:10px;line-height:2;font-weight:bold;border-radius:5px;">UnPaid<br><span style="font-size:20px;"><?php echo $row_non['UnPaid']; ?></span>
                                </a>
                            </div>
                            <img src="images/tower1.png" style="width: 70%;margin-bottom:-5px;">
                            <a href="dashboard.php?tower=1"><button  style="width:285px;font-size:30px;height:60px;background:black;padding:10px;border-radius:10px;color:white;font-weight:bold">Ihsan 1</button></a>
                        </div>
                    </div>
                    <div class="dual-1" style="border: 30px solid white;padding: 20px;">
                        <div style="width:100%;display:block;">
                            <div style="width:100%;display:flex;padding-bottom:10px;">
                                <div style="width:100%;background:green;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Vacant<br><span style="font-size:20px;">23</span>
                                </div>
                                <div style="width:100%;background:darkblue;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Occupied<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:purple;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Cleared<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:yellow;color:black;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Expired<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:orange;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Upcoming<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:red;color:white;font-size:10px;line-height:2;font-weight:bold;border-radius:5px;">UnPaid<br><span style="font-size:20px;">0</span>
                                </div>
                            </div>
                            <img src="images/tower2.png" style="width: 70%;margin-bottom:-5px;">
                            <a href="dashboard_tower_2.php?tower=2"><button  style="width:285px;font-size:30px;height:60px;background:black;padding:10px;border-radius:10px;color:white;font-weight:bold">Ihsan 2</button></a>
                        </div>
                    </div>
                    <div class="dual-1" style="border: 30px solid white;padding: 20px;">
                        <div style="width:100%;display:block;">
                            <div style="width:100%;display:flex;padding-bottom:10px;">
                                <div style="width:100%;background:green;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Vacant<br><span style="font-size:20px;">20</span>
                                </div>
                                <div style="width:100%;background:darkblue;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Occupied<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:purple;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Cleared<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:yellow;color:black;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Expired<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:orange;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Upcoming<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:red;color:white;font-size:10px;line-height:2;font-weight:bold;border-radius:5px;">UnPaid<br><span style="font-size:20px;">0</span>
                                </div>
                            </div>
                            <img src="images/tower3.png" style="width: 70%;margin-bottom:-5px;">
                            <a href="dashboard.php?tower=3"><button  style="width:285px;font-size:30px;height:60px;background:black;padding:10px;border-radius:10px;color:white;font-weight:bold">Tower 77</button></a>
                        </div>
                    </div>
                    <div class="dual-1" style="border: 30px solid white;padding: 20px;">
                        <div style="width:100%;display:block;">
                            <div style="width:100%;display:flex;padding-bottom:10px;">
                                <div style="width:100%;background:green;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Vacant<br><span style="font-size:20px;">20</span>
                                </div>
                                <div style="width:100%;background:darkblue;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Occupied<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:purple;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Cleared<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:yellow;color:black;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Expired<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:orange;color:white;font-size:10px;line-height:2;font-weight:bold;border-right:3px solid white;border-radius:5px;">Upcoming<br><span style="font-size:20px;">0</span>
                                </div>
                                <div style="width:100%;background:red;color:white;font-size:10px;line-height:2;font-weight:bold;border-radius:5px;">UnPaid<br><span style="font-size:20px;">0</span>
                                </div>
                            </div>
                            <img src="images/tower4.png" style="width: 70%;margin-bottom:-5px;">
                            <a href="dashboard.php?tower=3"><button  style="width:285px;font-size:30px;height:60px;background:black;padding:10px;border-radius:10px;color:white;font-weight:bold">Ihsan 3</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-section" style="position:fixed;bottom:0px;">
            <div class="footer1">
                <div class="footer1-1"></div>
                <div class="footer1-2"><img src="images/logo.png"></div>
                <div class="footer1-3"></div>
            </div>
            <div class="footer2"><i class="fa-solid fa-building"></i> Saleel Real Estate</div>
        </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Page specific script -->
</body>

</html>