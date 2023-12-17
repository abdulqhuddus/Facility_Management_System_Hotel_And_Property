<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <meta property="og:image" content="images/preview.jpg">
    <title>Ihsan X</title>
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
        <div class="login">
            <!--<img src="images/logo.png">-->
            <div class="login-wrapper">
                <div class="dual-mode">
                    <div class="dual-1"><img src="images/tower.png" style="width: 175px;"></div>
                    <div class="dual-2">
                        <p><span style="color:red"><?php echo "".$_SESSION['error']."<br>"; ?></span>Registered Mobile
                            Number</p>
                        <form id="myForm" method="post">
                            <div class="form-group" id="mbl">
                                <input placeholder="05XXXXXXXX" type="number" name="mobile" id="mobile"
                                    class="form-control" maxlength="10" required />
                            </div>
                            <div class="login-btn">
                                <input type="button" id="submitFormData" onclick="SubmitFormData();" value="Submit" />
                            </div>
                        </form>
                        <div id="results" style="margin-bottom:10px;"></div>
                    </div>
                </div>

                <div class="login-disclaimer"><i class="fa-solid fa-triangle-exclamation"></i>Unauthorized access is
                    prohibited!</div>
            </div>
        </div>
        <div class="footer-section" style="position:fixed;bottom:0px;">
            <div class="footer1">
                <div class="footer1-1">Alihsan</div>
                <div class="footer1-2"><img src="images/logo.png"></div>
                <div class="footer1-3">Charity</div>
            </div>
            <div class="footer2"><i class="fa-solid fa-building"></i> Saleel Real Estate</div>
        </div>
        <?php session_destroy() ?>
        <!--===============================================================================================-->
        <script src="js/jquery.min.js"></script>
        <script src="js/main.js"></script>
        <!--===============================================================================================-->
        <script>
        function SubmitFormData() {
            var mobile = $("#mobile").val();
            var password = $("#password").val();
            $.post("lotp.php", {
                    mobile: mobile,
                    password: password
                },
                function(data) {
                    $('#results').html(data);
                    $('#myForm')[0].reset();
                });
            document.getElementById("myForm").style.display = "none";
        }
        </script>