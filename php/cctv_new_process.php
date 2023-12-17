<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require "../config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../index.php");
    exit;
}
$get_user = "SELECT * FROM admin_users WHERE mobile='".$_SESSION["mobile"]."'";
$user_query = mysqli_query($conn, $get_user);
$user_row = mysqli_fetch_assoc($user_query);
$updated_by = "".$user_row['first_name']." ".$user_row['last_name']."";
date_default_timezone_set('Asia/Dubai');
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
if(ISSET($_POST['submit'])){

    $sql_file = "SELECT * FROM `maintenance` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $new_name = "MNTC_CCTV_DOC" . ($last_id + 1);
    $invoice_id = "MNTC_CCTV_INV" . ($last_id + 1);

    $filename = $_FILES['myfile']['name'];
    $file_name = $new_name.'_'.$filename;

    // destination of the file on the server
    $destination = '../attachments/' . $file_name;

    // get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['myfile']['tmp_name'];
    $size = $_FILES['myfile']['size'];

    if (!in_array($extension, ['zip', 'pdf', 'docx'])) {
        ?>
        <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'You file extension must be .zip, .pdf, .docx',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../apartment.php';
                    }, 2000);
        </script>
        <?php
    } elseif ($_FILES['myfile']['size'] > 10000000) { // file shouldn't be larger than 10Megabyte
        ?>
        <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'File size is too large!',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../apartment.php';
                    }, 2000);
        </script>
        <?php
    } else {
        // echo "Attachment clear to Proceed";
    }
        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            
        date_default_timezone_set('Asia/Dubai');
        $date = date("Y-m-d H:i:sa");
        
        $_SESSION['supplier_name'] = $_POST['supplier_name'];
        $_SESSION['contact_person'] = $_POST['contact_person'];
        $_SESSION['contact_number'] = $_POST['contact_number'];
        $_SESSION['type'] = $_POST['type'];
        if($_POST['cash_amount'] != ''){$amount = $_POST['cash_amount'];}else{$amount = $_POST['cheque_amount'];}
        $_SESSION['amount'] = $amount;
        $_SESSION['contract_amount'] = $_POST['contract_amount'];
        if($_POST['cash'] != ''){$pay_mode = $_POST['cash'];}else{$pay_mode = $_POST['cheque'];}
        $_SESSION['pay_mode'] = $pay_mode;
        $_SESSION['date'] = $date;
        $_SESSION['updated_by'] = $updated_by;
        $_SESSION['invoice_id'] = $invoice_id;
        $balance = $_POST['contract_amount'] - $amount;
        $_SESSION['balance'] = $balance;
        if($balance > 0){$status='1';}
        elseif($balance === 0){$status=0;}
        elseif($balance < 0){$status=0;}

           $sqlinsert = "INSERT INTO maintenance (`type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `file_name`, `file_size`,  `supplier_name`, `contact_person`, `contact_number`, `invoice_id`, `balance`, `contract_to`, `contract_from`, `notes`) VALUES ('".$_POST['type']."', '".$_POST['contract_amount']."', '".$date."', '1', '".$updated_by."', '".$pay_mode."', '$file_name', '".$size."', '".$_POST['supplier_name']."', '".$_POST['contact_person']."', '".$_POST['contact_number']."', '$invoice_id', '$balance', '".$_POST['contract_to']."', '".$_POST['contract_from']."', '".$_POST['notes']."')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $maininsert = "INSERT INTO maintenance_data (`type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `file_name`, `file_size`,  `supplier_name`, `invoice_id`, `contract_number`, `notes`, `cheque_date`, `cheque_number`, `cheque_bank`, `contract_amount`) VALUES ('".$_POST['type']."', '".$amount."', '".$date."', 'Completed', '".$updated_by."', '".$pay_mode."', '$file_name', '".$size."', '".$_POST['supplier_name']."', '$invoice_id', '$invoice_id', '".$_POST['notes']."', '".$_POST['cheque_date']."', '".$_POST['cheque_number']."', '".$_POST['cheque_bank']."', '".$_POST['contract_amount']."')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {
    
                ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Successfully added',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = '../maintenance.php';
                    }, 800);
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
                        window.location.href = '../maintenance.php';
                    }, 800);
                </script>
                <?php
            }

        }else{
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
                        window.location.href = '../maintenance.php';
                    }, 800);
                </script>
                <?php
            }
        }else{
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
                    window.location.href = '../maintenance.php';
                }, 800);
            </script>
            <?php
        }
    }else{
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
                window.location.href = '../maintenance.php';
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