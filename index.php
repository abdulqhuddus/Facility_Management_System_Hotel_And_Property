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
    <div class="login-section" style="padding-top: 75px;">
        <!--<div class="sampledesk"><img src="images/sampledesk.png"></div>
  <div class="samplemob"><img src="images/samplemob.png"></div>-->
        <div class="login" style="max-width:800px;">
            <!--<img src="images/logo.png">-->
            <div class="login-wrapper">
                <div class="dual-mode">
                    <div class="dual-1"><img src="images/tower.png" style="width: 175px;"></div>
                    <div class="dual-2">
                        <!-- <p><span style="color:red"><?php echo "".$_SESSION['error']."<br>"; ?></span>Registered Mobile
                            Number</p> -->
                            <form id="quickForm" action="php/login_process.php" method="POST"
                                    enctype="multipart/form-data" style="background:white;border-radius:20px;max-width: 500px;margin: auto;padding:10px;">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="email">Username</label>
                                            <input type="email" class="form-control" placeholder="Enter your username"
                                                id="email" name="email" style="border:1px solid lightgrey">
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" placeholder="Enter your password"
                                                id="password" name="password" style="border:1px solid lightgrey">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <center><button name="login" type="submit" class="btn btn-primary" style="background:black">Submit</button></center>
                                    </div>
                                </form>
                        <div id="results" style="margin-bottom:10px;"></div>
                    </div>
                </div>

                <div class="login-disclaimer" style="color:black; border:1px solid black"><i class="fa-solid fa-triangle-exclamation"></i>Unauthorized access is
                    prohibited!</div>
            </div>
        </div>
        <div class="footer-section" style="position:fixed;bottom:0px;">
            <div class="footer1" style="width:25%;">
                <!-- <div class="footer1-1"></div> -->
                <div class="footer1-2"><img src="images/logo.png"></div>
                <!-- <div class="footer1-3"></div> -->
            </div>
            <div class="footer2" style="width:75%;"><i class="fa-solid fa-building"></i> Salil Real Estate</div>
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
    <script>
        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    alert("Form successful submitted!");
                }
            });
            $('#quickForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your username"
                    },
                    password: {
                        required: "Please enter your password"
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>