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
if(($_POST['type'] === "cancel_cheque_2") || ($_POST['type'] === "cancel_cheque_3") || ($_POST['type'] === "cancel_cheque_4") || ($_POST['type'] === "cancel_cheque_5") || ($_POST['type'] === "cancel_cheque_6")){

    $sql_file = "SELECT * FROM `cheque_cancellation` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $random = mt_rand(1000,9999);
    $new_name = "CHQ_CNL_DOC" . $random . ($last_id + 1);
    $invoice_id = "CHQ_CNL_INV" . ($last_id + 1);

    if($_POST['type'] === "cancel_cheque_2"){$filename_cheque = $_FILES['cancel_2_cheque']['name'];}
    elseif($_POST['type'] === "cancel_cheque_3"){$filename_cheque = $_FILES['cancel_3_cheque']['name'];}
    elseif($_POST['type'] === "cancel_cheque_4"){$filename_cheque = $_FILES['cancel_4_cheque']['name'];}
    elseif($_POST['type'] === "cancel_cheque_5"){$filename_cheque = $_FILES['cancel_5_cheque']['name'];}
    elseif($_POST['type'] === "cancel_cheque_6"){$filename_cheque = $_FILES['cancel_6_cheque']['name'];}
    $file_name_cheque = $new_name.'_'.$filename_cheque;

    // destination of the file on the server
    $destination_cheque = '../attachments/' . $file_name_cheque;

    // get the file extension
    $extension = pathinfo($filename_cheque, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    if($_POST['type'] === "cancel_cheque_2"){$cheque_file = $_FILES['cancel_2_cheque']['tmp_name'];}
    elseif($_POST['type'] === "cancel_cheque_3"){$cheque_file = $_FILES['cancel_3_cheque']['tmp_name'];}
    elseif($_POST['type'] === "cancel_cheque_4"){$cheque_file = $_FILES['cancel_4_cheque']['tmp_name'];}
    elseif($_POST['type'] === "cancel_cheque_5"){$cheque_file = $_FILES['cancel_5_cheque']['tmp_name'];}
    elseif($_POST['type'] === "cancel_cheque_6"){$cheque_file = $_FILES['cancel_6_cheque']['tmp_name'];}

    // File size
    if($_POST['type'] === "cancel_cheque_2"){$cheque_size = $_FILES['cancel_2_cheque']['size'];}
    elseif($_POST['type'] === "cancel_cheque_3"){$cheque_size = $_FILES['cancel_3_cheque']['size'];}
    elseif($_POST['type'] === "cancel_cheque_4"){$cheque_size = $_FILES['cancel_4_cheque']['size'];}
    elseif($_POST['type'] === "cancel_cheque_5"){$cheque_size = $_FILES['cancel_5_cheque']['size'];}
    elseif($_POST['type'] === "cancel_cheque_6"){$cheque_size = $_FILES['cancel_6_cheque']['size'];}

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

    if(move_uploaded_file($cheque_file, $destination_cheque)){
        // echo "Replacement cheque uploaded";

            $_SESSION['name'] = $_POST['customer_name'];
            $_SESSION['customer_mobile'] = $_POST['customer_mobile'];
            $_SESSION['amount'] = $_POST['amount'];
            // $_SESSION['type'] = $_POST['type'];
            $_SESSION['apt_id'] = $_POST['id'];
            $pay_mode = "Cheque";
            // $_SESSION['pay_mode'] = $pay_mode;
            $_SESSION['date'] = $date;
            $_SESSION['updated_by'] = $updated_by;
            $_SESSION['invoice_id'] = $_POST['contract_number'];

            $get_contract = "SELECT * FROM `contracts` WHERE invoice_id='".$_POST['contract_number']."'";
            $rs_contract = mysqli_query($conn, $get_contract);
            $row_contract = mysqli_fetch_assoc($rs_contract);

            if($_POST['type'] === "cancel_cheque_2"){$cheque_number = $row_contract['cheque_2_number']; $cheque_date = $row_contract['cheque_2_date']; $cheque_name= $row_contract['cheque_2_name']; $cheque_bank = $row_contract['cheque_2_bank'];}
            elseif($_POST['type'] === "cancel_cheque_3"){$cheque_number = $row_contract['cheque_3_number']; $cheque_date = $row_contract['cheque_3_date']; $cheque_name= $row_contract['cheque_3_name']; $cheque_bank = $row_contract['cheque_3_bank'];}
            elseif($_POST['type'] === "cancel_cheque_4"){$cheque_number = $row_contract['cheque_4_number']; $cheque_date = $row_contract['cheque_4_date']; $cheque_name= $row_contract['cheque_4_name']; $cheque_bank = $row_contract['cheque_4_bank'];}
            elseif($_POST['type'] === "cancel_cheque_5"){$cheque_number = $row_contract['cheque_5_number']; $cheque_date = $row_contract['cheque_5_date']; $cheque_name= $row_contract['cheque_5_name']; $cheque_bank = $row_contract['cheque_5_bank'];}
            elseif($_POST['type'] === "cancel_cheque_6"){$cheque_number = $row_contract['cheque_6_number']; $cheque_date = $row_contract['cheque_6_date']; $cheque_name= $row_contract['cheque_6_name']; $cheque_bank = $row_contract['cheque_6_bank'];}

            if($_POST['type'] === "cancel_cheque_2"){$new_cheque_number = $_POST['cheque_2_number']; $new_cheque_date = $_POST['cheque_2_date']; $new_cheque_bank = $_POST['cheque_2_bank'];}
            elseif($_POST['type'] === "cancel_cheque_3"){$new_cheque_number = $_POST['cheque_3_number']; $new_cheque_date = $_POST['cheque_3_date']; $new_cheque_bank = $_POST['cheque_3_bank'];}
            elseif($_POST['type'] === "cancel_cheque_4"){$new_cheque_number = $_POST['cheque_4_number']; $new_cheque_date = $_POST['cheque_4_date']; $new_cheque_bank = $_POST['cheque_4_bank'];}
            elseif($_POST['type'] === "cancel_cheque_5"){$new_cheque_number = $_POST['cheque_5_number']; $new_cheque_date = $_POST['cheque_5_date']; $new_cheque_bank = $_POST['cheque_5_bank'];}
            elseif($_POST['type'] === "cancel_cheque_6"){$new_cheque_number = $_POST['cheque_6_number']; $new_cheque_date = $_POST['cheque_6_date']; $new_cheque_bank = $_POST['cheque_6_bank'];}

            $sqlinsert = "INSERT INTO cheque_cancellation (`apt_id`, `amount`, `date`, `status`, `updated_by`, `name`, `mobile`, `invoice_id`, `old_cheque_number`, `old_cheque_date`, `old_cheque_name`, `old_cheque_bank`) VALUES ('".$_POST['id']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['customer_name']."', '".$_POST['customer_mobile']."', '$invoice_id', '".$cheque_number."', '".$cheque_date."', '".$cheque_name."', '".$cheque_bank."')";
            
            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                if($_POST['type'] === "cancel_cheque_2"){$continsert = "UPDATE contracts SET cheque_2_status='Returned', `updated_by`='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";}
                elseif($_POST['type'] === "cancel_cheque_3"){$continsert = "UPDATE contracts SET cheque_3_status='Returned', `updated_by`='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";}
                elseif($_POST['type'] === "cancel_cheque_4"){$continsert = "UPDATE contracts SET cheque_4_status='Returned', `updated_by`='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";}
                elseif($_POST['type'] === "cancel_cheque_5"){$continsert = "UPDATE contracts SET cheque_5_status='Returned', `updated_by`='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";}
                elseif($_POST['type'] === "cancel_cheque_6"){$continsert = "UPDATE contracts SET cheque_6_status='Returned', `updated_by`='".$updated_by."' WHERE invoice_id='".$_POST['contract_number']."'";}

                $contupdate = mysqli_query($conn, $continsert);

                if ($contupdate == true) {

                        if($_POST['type'] === "cancel_cheque_2"){$rentinsert = "UPDATE rents SET status='Returned', `updated_by`='".$updated_by."', date='".$date."' WHERE status='Completed' AND type='quarter_2' AND contract_number='".$_POST['contract_number']."'";}
                    elseif($_POST['type'] === "cancel_cheque_3"){$rentinsert = "UPDATE rents SET status='Returned', `updated_by`='".$updated_by."', date='".$date."' WHERE status='Completed' AND type='quarter_3' AND contract_number='".$_POST['contract_number']."'";}
                    elseif($_POST['type'] === "cancel_cheque_4"){$rentinsert = "UPDATE rents SET status='Returned', `updated_by`='".$updated_by."', date='".$date."' WHERE status='Completed' AND type='quarter_4' AND contract_number='".$_POST['contract_number']."'";}
                    elseif($_POST['type'] === "cancel_cheque_5"){$rentinsert = "UPDATE rents SET status='Returned', `updated_by`='".$updated_by."', date='".$date."' WHERE status='Completed' AND type='quarter_5' AND contract_number='".$_POST['contract_number']."'";}
                    elseif($_POST['type'] === "cancel_cheque_6"){$rentinsert = "UPDATE rents SET status='Returned', `updated_by`='".$updated_by."', date='".$date."' WHERE status='Completed' AND type='quarter_6' AND contract_number='".$_POST['contract_number']."'";}

                    $rentupdate = mysqli_query($conn, $rentinsert);

                    if ($rentupdate == true) {

                            if($_POST['type'] === "cancel_cheque_2"){$quarter = "quarter_2";}
                        elseif($_POST['type'] === "cancel_cheque_3"){$quarter = "quarter_3";}
                        elseif($_POST['type'] === "cancel_cheque_4"){$quarter = "quarter_4";}
                        elseif($_POST['type'] === "cancel_cheque_5"){$quarter = "quarter_5";}
                        elseif($_POST['type'] === "cancel_cheque_6"){$quarter = "quarter_6";}

                        $get_rent_inv = "SELECT invoice_id FROM rents WHERE type='".$quarter."' AND contract_number='".$_POST['contract_number']."' AND status='Returned' ORDER BY ID DESC LIMIT 1";
                        $get_rent_inv_rs = mysqli_query($conn, $get_rent_inv);
                        $get_rent_inv_rw = mysqli_fetch_assoc($get_rent_inv_rs);

                        $trninsert = "UPDATE transactions SET status='Returned', date='".$date."' WHERE invoice_id='".$get_rent_inv_rw['invoice_id']."'";                       
                            
                        $trnupdate = mysqli_query($conn, $trninsert);

                        if ($trnupdate == true) {

                        $message = "Your cheque ".$cheque_number." has been cancelled. Transaction ID is ".$invoice_id."%0a%0aSaleel Real Estate";
                        $nmob = substr($_POST['customer_mobile'], 1);
                        $add_prefix = '971';
                        $mob = $add_prefix.$nmob;
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, "http://51.210.118.93:8080/websmpp/websms");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "user=ALIhsanTR&pass=AlIhsan@5&sid=AL%20IHSAN&mno=".$mob."&type=4&text=".$message."");
                            $headers = array();
                            $headers[]= 'Content-Type: application/x-www-form-urlencoded';
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            $result = curl_exec($ch);
                            if (curl_errno($ch)){
                               echo 'ERROR:'. curl_error($ch);
                                echo " Please try again later";
                            }
                            curl_close($ch);
    
                            ?>
                            <script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Cheque cancelled successfully',
                                showConfirmButton: false,
                                // timer: 2000
                            });

                            setTimeout(function() {
                                window.location.href = 'invoice_contract2.php?invoice_id=<?php echo $_POST['contract_number']; ?>';
                            }, 800);
                            </script>
                            <?php
                            } else {
                                    ?>
                                <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: 'Cheque cancellation Failed!',
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
                                   text: 'Cheque replacement Failed!',
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
                        text: 'Cheque replacement Failed!',
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
                text: 'Cheque cancellation Failed!',
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
            exit;
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


    }else {
        exit;
        ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Failed',
        text: 'Unauthorized Sumit!',
        showConfirmButton: false,
        // timer: 2000
    });

    setTimeout(function() {
        window.location.href = '../apartment.php';
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