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

<style>
html{
    background: #b1b1b1;
    background-image: url(../../assets/images/home-bg.png);
    background-size: 100%;
    background-repeat: repeat;
}
body{
    background:transparent;
}
</style>

<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">



            <!-- Main content -->

            <section class="content">

                <div class="container-fluid">

                    <div class="row" style="margin-top:250px;margin-top:250px;">

                        <div class="col-sm-3"></div>



                        <div class="col-sm-6">

                            <div class="">

                                <form id="quickForm" action="php/login_process.php" method="POST"

                                    enctype="multipart/form-data" style="background:white;border-radius:20px;max-width: 500px;margin: auto;padding:5%;">

                                    <div class="card-body">

                                        <div class="form-group">

                                            <label for="email">Username</label>

                                            <input type="email" class="form-control" placeholder="Enter your username"

                                                id="email" name="email">

                                        </div>

                                        <div class="form-group">

                                            <label for="password">Password</label>

                                            <input type="password" class="form-control" placeholder="Enter your password"

                                                id="password" name="password">

                                        </div>

                                    </div>

                                    <!-- /.card-body -->

                                    <div class="card-footer">

                                        <center><button name="login" type="submit" class="btn btn-primary">Submit</button></center>

                                    </div>

                                </form>

                            </div>

                        </div>



                        <div class="col-sm-3"></div>

                    </div>

                    <!-- /.row -->

                </div><!-- /.container-fluid -->

            </section>

            <!-- /.content -->

        </div>

        <!-- /.content-wrapper -->





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