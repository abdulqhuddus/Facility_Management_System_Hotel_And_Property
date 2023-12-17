<?php

session_start();

include '../config.php';

?>





<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

</head>



<body>

    <?php

    if (isset($_POST['login'])) {

        // print_r($_POST);

        $email = mysqli_real_escape_string($conn, $_POST['email']);

        $password = $_POST['password'];



        $sql = "SELECT * FROM admin_users WHERE `email` = '$email'";

        $query = mysqli_query($conn, $sql);



        if (mysqli_num_rows($query) > 0) {

            $row = mysqli_fetch_assoc($query);

            $first_name = $row['first_name'];

            $last_name = $row['last_name'];

            $email = $row['email'];

            $mobile = $row['mobile'];

            $role = $row['role'];

            $id = $row['id'];

            $db_password = $row['password'];



            if (password_verify($password, $db_password)) {

                $_SESSION['FIRST_NAME'] = $first_name;

                $_SESSION['LAST_NAME'] = $last_name;

                $_SESSION['mobile'] = $mobile;

                $_SESSION['EMAIL'] = $email;

                $_SESSION['ROLE'] = $role;

                $_SESSION['DEPARTMENT'] = $department;

                $_SESSION["id"] = $id;

                $_SESSION["loggedin"] = true;

                ?>

                <script>

                    Swal.fire({

                        icon: 'success',

                        title: 'Success',

                        text: 'Logged in successfully',

                        showConfirmButton: false,

                        // timer: 2000

                    });



                    setTimeout(function () {

                        window.location.href = '../landing.php';

                    }, 2000);

                </script>

                <?php

            } else {

                ?>

                <script>

                    Swal.fire({

                        icon: 'error',

                        title: 'Failed',

                        text: 'Password incorrect',

                        showConfirmButton: false,

                    });



                    setTimeout(function () {

                        window.location.href = '../index.php';

                    }, 2000);

                </script>

                <?php

            }

        } else {

            // echo "email not found";

            ?>

            <script>

                Swal.fire({

                    icon: 'error',

                    title: 'Failed',

                    text: 'Email incorrect',

                    showConfirmButton: false,

                    // timer: 2000

                });



                setTimeout(function () {

                    window.location.href = '../index.php';

                }, 2000);

            </script>

            <?php

        }





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