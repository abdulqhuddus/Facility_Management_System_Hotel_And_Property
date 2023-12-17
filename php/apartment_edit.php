<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require "../config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

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

    if (isset($_POST['submit'])) {
        // print_r($_POST);
        // exit;

        date_default_timezone_set('Asia/Dubai');
        $date = date("Y-m-d H:i:sa");

        $sql_file = "SELECT * FROM `contact_update` ORDER BY `id` DESC LIMIT 1";
        $query_file = mysqli_query($conn, $sql_file);
        $row_file = mysqli_fetch_assoc($query_file);
        $last_id = $row_file['id'];
        $random = mt_rand(1000,9999);
        $new_name = "CON_UPD_DOC" . $random . ($last_id + 1);
        $filename_eid = $_FILES['eid_file']['name'];
        $file_name_eid = $new_name.'_'.$filename_eid;
        $destination_eid = '../attachments/' . $file_name_eid;
        $extension = pathinfo($filename_eid, PATHINFO_EXTENSION);
        $eid_file = $_FILES['eid_file']['tmp_name'];
        $eid_size = $_FILES['eid_file']['size'];

        if (!in_array($extension, ['zip', 'pdf', 'docx'])){
            ?>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'You file extension must be .zip, .pdf, .docx',
                    showConfirmButton: false,
                    // timer: 2000
                });
            
                setTimeout(function() {
                    window.location.href = '../apartment.php';
                }, 2000);
                </script>
            <?php
                } elseif ($cheque_size > 10000000) { // file shouldn't be larger than 10Megabyte
            ?>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'File size is too large!',
                    showConfirmButton: false,
                    // timer: 2000
                });
            
                setTimeout(function() {
                    window.location.href = '../apartment.php';
                }, 2000);
                </script>
            <?php
                }else{
                    // echo "Attachment clear to Proceed";
                }

            if(move_uploaded_file($eid_file, $destination_eid)){

            $get_contract = "SELECT * FROM `contracts` WHERE invoice_id='".$_POST['contract_number']."'";
            $rs_contract = mysqli_query($conn, $get_contract);
            $row_contract = mysqli_fetch_assoc($rs_contract);

            $sqlinsert = "INSERT INTO contact_update (`apt_id`, `date`, `status`, `updated_by`, `name`, `mobile`, `email`, `invoice_id`, `old_eid_number`, `old_eid_date`, `old_file_name`, `new_eid_number`, `new_eid_date`, `new_file_name`) VALUES ('".$_POST['id']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['name']."', '".$_POST['mobile']."', '".$_POST['email']."', '".$_POST['contract_number']."', '".$row_contract['eid']."', '".$row_contract['eid_expiry']."', '".$row_contract['eid_name']."', '".$_POST['eid']."', '".$_POST['eid_expiry']."', '".$file_name_eid."')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

            $maininsert = "UPDATE `contracts` SET `name`='".$_POST['name']."', mobile='".$_POST['mobile']."', email='".$_POST['email']."', eid='".$_POST['eid']."', eid_expiry='".$_POST['eid_expiry']."', eid_name='".$file_name_eid."', updated_by='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";

            $mainresult = mysqli_query($conn, $maininsert);

            if ($mainresult == true) {

                $apt_update = "UPDATE `apartments` SET `name`='".$_POST['name']."', mobile='".$_POST['mobile']."', email='".$_POST['email']."', eid='".$_POST['eid']."', eid_expiry='".$_POST['eid_expiry']."', updated_by='".$updated_by."', updated_at='".$date."' WHERE door='".$_POST['id']."'";

                $aptupdate = mysqli_query($conn, $apt_update);

                if ($aptupdate == true) {
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Updated successfully',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../dashboard.php';
                    }, 2000);
                </script>
                <?php
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Failed',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../apartment.php';
                    }, 2000);
                </script>
                <?php
            }

            } else {
                ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Contact Update Failed!',
                showConfirmButton: false,
                // timer: 2000
            });

            setTimeout(function() {
                window.location.href = '../apartment.php';
            }, 800);
            </script>
            <?php
            }

        } else {
            ?>
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: 'Contact Update Failed!',
            showConfirmButton: false,
            // timer: 2000
        });

        setTimeout(function() {
            window.location.href = '../apartment.php';
        }, 800);
        </script>
        <?php
        }

    }else {
        ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Failed',
        text: 'Failed to upload attachment',
        showConfirmButton: false,
        // timer: 2000
    });

    setTimeout(function() {
        window.location.href = '../apartment.php';
    }, 800);
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