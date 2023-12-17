<?php
error_reporting(E_ERROR | E_PARSE);
if($_POST['type'] === "Rent"){

    $sql_file = "SELECT * FROM `rents` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $new_name = "RENT_DOC" . ($last_id + 1);
    $invoice_id = "RENT_INV" . ($last_id + 1);

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
        
        $_SESSION['name'] = $_POST['customer_name'];
        $_SESSION['customer_mobile'] = $_POST['customer_mobile'];
        $_SESSION['amount'] = $_POST['amount'];
        $_SESSION['type'] = $_POST['type'];
        $_SESSION['apt_id'] = $_POST['id'];
        $_SESSION['pay_mode'] = $_POST['pay_mode'];
        $_SESSION['date'] = $date;
        $_SESSION['updated_by'] = $updated_by;
        $_SESSION['invoice_id'] = $invoice_id;

            $sqlinsert = "INSERT INTO transactions (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `name`, `invoice_id`) VALUES ('".$_POST['id']."', '".$_POST['type']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['customer_name']."', '$invoice_id')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $maininsert = "INSERT INTO rents (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `file_name`, `file_size`, `download_count`, `name`, `invoice_id`, `contract_number`) VALUES ('".$_POST['id']."', '".$_POST['quarter']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '$file_name', '$size', '0', '".$_POST['customer_name']."', '$invoice_id', '".$_POST['contract_number']."')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {

                    $get_contract_id = "SELECT * FROM apartments WHERE door='".$_POST['id']."'";
                    $result_contract_id = mysqli_query($conn, $get_contract_id);
                    $row_contract_id = mysqli_fetch_assoc($result_contract_id);

                    if($_POST['quarter'] == "quarter_2"){
                        $aptinsert = "UPDATE contracts SET cheque_2_status='Paid', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$row_contract_id['contract_number']."'";
                    }
                    elseif($_POST['quarter'] == "quarter_3"){
                        $aptinsert = "UPDATE contracts SET cheque_3_status='Paid', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$row_contract_id['contract_number']."'";
                    }
                    elseif($_POST['quarter'] == "quarter_4"){
                        $aptinsert = "UPDATE contracts SET cheque_4_status='Paid', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$row_contract_id['contract_number']."'";
                    }
                    elseif($_POST['quarter'] == "quarter_5"){
                        $aptinsert = "UPDATE contracts SET cheque_5_status='Paid', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$row_contract_id['contract_number']."'";
                    }
                    elseif($_POST['quarter'] == "quarter_6"){
                        $aptinsert = "UPDATE contracts SET cheque_6_status='Paid', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$row_contract_id['contract_number']."'";
                    }

                    $aptresult = mysqli_query($conn, $aptinsert);

                    if ($aptresult == true) {

                        $get_nextpay = "SELECT * FROM contracts WHERE invoice_id='".$row_contract_id['contract_number']."'";
                        $result_nextpay = mysqli_query($conn, $get_nextpay);
                        $row_next_pay = mysqli_fetch_assoc($result_nextpay);

                        if($row_next_pay['cheque_2_status'] === 'Unpaid'){$next_payment = $row_next_pay['cheque_2_amount']; $next_pay_date = $row_next_pay['cheque_2_date'];}
                             else{
                                if($row_next_pay['cheque_3_status'] === 'Unpaid'){$next_payment = $row_next_pay['cheque_3_amount']; $next_pay_date = $row_next_pay['cheque_3_date'];}
                                else{
                                    if($row_next_pay['cheque_4_status'] === 'Unpaid'){$next_payment = $row_next_pay['cheque_4_amount']; $next_pay_date = $row_next_pay['cheque_4_date'];}
                                    else{
                                        if($row_next_pay['total_cheques'] == "4"){$next_payment = "0"; $next_pay_date = "2050-12-30";}
                                        else{
                                        if($row_next_pay['cheque_5_status'] === 'Unpaid'){$next_payment = $row_next_pay['cheque_5_amount']; $next_pay_date = $row_next_pay['cheque_5_date'];}
                                        else{
                                            if($row_next_pay['cheque_6_status'] === 'Unpaid'){$next_payment = $row_next_pay['cheque_6_amount']; $next_pay_date = $row_next_pay['cheque_6_date'];}
                                            else{$next_payment = "0"; $next_pay_date = "2050-12-30";}
                                            }
                                        }
                                    }
                                }
                             }  

                        $aptupdate = "UPDATE apartments SET next_payment='".$next_payment."', next_pay_date='".$next_pay_date."', last_payment='".$_POST['amount']."', last_pay_date='".$date."', updated_by='".$updated_by."', updated_at='".$date."' WHERE id='".$_POST['id']."'";

                        $aptup_result = mysqli_query($conn, $aptupdate);

                        if ($aptup_result == true) {

                        $message = "Your rent is successfully received with ID ".$invoice_id."%0a%0aSaleel Real Estate";
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
                        text: 'Rent added successfully with Apartment Update',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = 'invoice.php';
                    }, 800);
                </script>
                <?php
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Rent Adding Failed with Apartment update',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
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
                    text: 'Rent Adding Failed with Apartment update',
                    showConfirmButton: false,
                    // timer: 2000
                });

                setTimeout(function () {
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
                    text: 'Failed to insert query',
                    showConfirmButton: false,
                    // timer: 2000
                });

                setTimeout(function () {
                    window.location.href = '../apartment.php';
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
                    text: 'Failed to update query',
                    showConfirmButton: false,
                    // timer: 2000
                });

                setTimeout(function () {
                    window.location.href = '../apartment.php';
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
                    text: 'Failed to upload attachment',
                    showConfirmButton: false,
                    // timer: 2000
                });

                setTimeout(function () {
                    window.location.href = '../apartment.php';
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
                    text: 'Unauthorized Access!',
                    showConfirmButton: false,
                    // timer: 2000
                });

                setTimeout(function () {
                    window.location.href = '../apartment.php';
                }, 800);
    </script>
    <?php
    }
?>