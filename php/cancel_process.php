<?php
if($_POST['type'] === "cancellation"){

    $sql_file = "SELECT * FROM `cancel_contracts` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $new_name = "CANC_DOC" . ($last_id + 1);
    $invoice_id = "CANC_INV" . ($last_id + 1);

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
    } else {echo "Attachment clear to Proceed";}
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
        $_SESSION['refund'] = $_POST['refund'];
        $_SESSION['security'] = $_POST['security'];
        $_SESSION['service_charge'] = $_POST['service_charge'];
        $_SESSION['maintenance_charge'] = $_POST['maintenance_charge'];
        $_SESSION['cancellation_type'] = $_POST['cancellation_type'];
        $_SESSION['date'] = $date;
        $_SESSION['updated_by'] = $updated_by;
        $_SESSION['invoice_id'] = $invoice_id;
        $_SESSION['total_amount'] = ($_POST['amount'] + $_POST['service_charge'] + $_POST['maintenance_charge']) - ($_POST['refund'] + $_POST['security']);

            $sqlinsert = "INSERT INTO transactions (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `name`, `invoice_id`, `refund`, `security`, `maintenance`) VALUES ('".$_POST['id']."', '".$_POST['type']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['customer_name']."', '$invoice_id', '".$_POST['refund']."', '".$_POST['security']."', '".$_POST['maintenance_charge']."')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $maininsert = "INSERT INTO cancel_contracts (`apt_id`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `file_name`, `file_size`, `download_count`, `name`, `invoice_id`, `contract_number`, `refund`, `cancellation_type`, `security`, `service_charge`, `maintenance`) VALUES ('".$_POST['id']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '$file_name', '$size', '0', '".$_POST['customer_name']."', '$invoice_id', '".$_POST['contract_number']."', '".$_POST['refund']."', '".$_POST['cancellation_type']."', '".$_POST['security']."', '".$_POST['service_charge']."', '".$_POST['maintenance_charge']."')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {

                    $get_contract_id = "SELECT * FROM contracts WHERE invoice_id='".$_POST['contract_number']."'";
                    $result_contract_id = mysqli_query($conn, $get_contract_id);
                    $row_contract_id = mysqli_fetch_assoc($result_contract_id);

                    if($row_contract_id['cheque_2_status'] === 'Unpaid'){$cheque_2_status = "Cancelled";}else{$cheque_2_status = $row_contract_id['cheque_2_status'];}                             
                    if($row_contract_id['cheque_3_status'] === 'Unpaid'){$cheque_3_status = "Cancelled";}else{$cheque_3_status = $row_contract_id['cheque_3_status'];}                              
                    if($row_contract_id['cheque_4_status'] === 'Unpaid'){$cheque_4_status = "Cancelled";}else{$cheque_4_status = $row_contract_id['cheque_4_status'];} 
                    if($row_contract_id['cheque_5_status'] === 'Unpaid'){$cheque_5_status = "Cancelled";}else{$cheque_5_status = $row_contract_id['cheque_5_status'];} 
                    if($row_contract_id['cheque_6_status'] === 'Unpaid'){$cheque_6_status = "Cancelled";}else{$cheque_6_status = $row_contract_id['cheque_6_status'];} 
                    
                    $aptinsert = "UPDATE contracts SET cheque_2_status='".$cheque_2_status."', cheque_3_status='".$cheque_3_status."', cheque_4_status='".$cheque_4_status."', cheque_5_status='".$cheque_5_status."', cheque_6_status='".$cheque_6_status."', updated_by='".$updated_by."', date='".$date."' WHERE invoice_id='".$_POST['contract_number']."'";

                    $aptresult = mysqli_query($conn, $aptinsert);

                    if ($aptresult == true) {  

                        $aptupdate = "UPDATE apartments SET parking='0', status='0', next_payment='NULL', next_pay_date='NULL', parking='0', name='NULL', mobile='NULL', email='NULL', contract_from='NULL', contract_to='NULL', rent='0', eid='NULL', eid_expiry='NULL', nationality='NULL', contract_number='NULL', updated_by='".$updated_by."', updated_at='".$date."' WHERE id='".$_POST['id']."'";

                        $aptup_result = mysqli_query($conn, $aptupdate);

                        if ($aptup_result == true) {

                            $parktupdate = "UPDATE parking_id SET apt_id='0', contract_number='NULL', status='0', parking_contract='NULL', name='NULL', updated_by='".$updated_by."', updated_at='".$date."' WHERE apt_id='".$_POST['id']."'";

                            $park_result = mysqli_query($conn, $parktupdate);

                            if ($park_result == true) {

                        $message = "Your tenancy contract is cancelled with ID ".$invoice_id."%0a%0aجمعية الاحسان الخيرية";
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
                        text: 'Contract cancelled successfully with Apartment Update',
                        showConfirmButton: false,
                        // timer: 2000
                    });

                    setTimeout(function () {
                        window.location.href = 'invoice_cancel.php';
                    }, 800);
                </script>
                <?php
            } else {
                ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Contract cancelling failed with Apartment update',
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
                    text: 'Contract cancelling failed with Apartment update',
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
                    text: 'Contract cancelling failed with Apartment update',
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