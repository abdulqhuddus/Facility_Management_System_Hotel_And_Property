<?php
error_reporting(E_ERROR | E_PARSE);
if($_POST['type'] === "Contract"){

    if(empty($_POST['cash_1_amount'])){$amount = $_POST['cheque_1_amount']; }
    else{$amount = $_POST['cash_1_amount']; }

    $sql_file = "SELECT * FROM `transactions` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $random1 = mt_rand(1000,9999);
    $random2 = mt_rand(1000,9999);
    $random3 = mt_rand(1000,9999);
    $random4 = mt_rand(1000,9999);
    $random5 = mt_rand(1000,9999);
    $random6 = mt_rand(1000,9999);
    $random7 = mt_rand(1000,9999);
    $new_name1 = "CONT_DOC" . $random1 . ($last_id + 1);
    $new_name2 = "CONT_DOC" . $random2 . ($last_id + 1);
    $new_name3 = "CONT_DOC" . $random3 . ($last_id + 1);
    $new_name4 = "CONT_DOC" . $random4 . ($last_id + 1);
    $new_name5 = "CONT_DOC" . $random5 . ($last_id + 1);
    $new_name6 = "CONT_DOC" . $random6 . ($last_id + 1);
    $new_name7 = "CONT_DOC" . $random7 . ($last_id + 1);
    $invoice_id = "CONT_INV" . ($last_id + 1);

    $filename_cheque2 = $_FILES['cheque_2_file']['name'];
    $file_name_cheque2 = $new_name1.'_'.$filename_cheque2;

    $filename_cheque3 = $_FILES['cheque_3_file']['name'];
    $file_name_cheque3 = $new_name2.'_'.$filename_cheque3;

    $filename_cheque4 = $_FILES['cheque_4_file']['name'];
    $file_name_cheque4 = $new_name3.'_'.$filename_cheque4;

    if($_POST['cheque_5_number'] != NULL){
    $filename_cheque5 = $_FILES['cheque_5_file']['name'];
    $file_name_cheque5 = $new_name5.'_'.$filename_cheque5;
    $filename_cheque6 = $_FILES['cheque_6_file']['name'];
    $file_name_cheque6 = $new_name6.'_'.$filename_cheque6;
    }else{}

    if(!empty($_POST['cheque_1_number'])){
    $filename_cheque1 = $_FILES['cheque_1_file']['name'];
    $file_name_cheque1 = $new_name7.'_'.$filename_cheque1;
    }

    $filename_eid = $_FILES['eid_file']['name'];
    $file_name_eid = $new_name4.'_'.$filename_eid;

    // destination of the file on the server
    $destination_cheque2 = '../attachments/' . $file_name_cheque2;
    $destination_cheque3 = '../attachments/' . $file_name_cheque3;
    $destination_cheque4 = '../attachments/' . $file_name_cheque4;

    if($_POST['cheque_5_number'] != NULL){
    $destination_cheque5 = '../attachments/' . $file_name_cheque5;
    $destination_cheque6 = '../attachments/' . $file_name_cheque6;
    }else{}

    if(!empty($_POST['cheque_1_number'])){
    $destination_cheque1 = '../attachments/' . $file_name_cheque1;
    }

    $destination_eid = '../attachments/' . $file_name_eid;
    // get the file extension
    $extension1 = pathinfo($filename_cheque2, PATHINFO_EXTENSION);
    $extension2 = pathinfo($filename_cheque3, PATHINFO_EXTENSION);
    $extension3 = pathinfo($filename_cheque4, PATHINFO_EXTENSION);

    if($_POST['cheque_5_number'] != NULL){
    $extension5 = pathinfo($filename_cheque5, PATHINFO_EXTENSION);
    $extension6 = pathinfo($filename_cheque6, PATHINFO_EXTENSION);
    }else{}

    if(!empty($_POST['cheque_1_number'])){
    $extension7 = pathinfo($filename_cheque1, PATHINFO_EXTENSION);
    }

    $extension4 = pathinfo($filename_eid, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $cheque_2_file = $_FILES['cheque_2_file']['tmp_name'];
    $cheque_2_size = $_FILES['cheque_2_file']['size'];

    $cheque_3_file = $_FILES['cheque_3_file']['tmp_name'];
    $cheque_3_size = $_FILES['cheque_3_file']['size'];

    $cheque_4_file = $_FILES['cheque_4_file']['tmp_name'];
    $cheque_4_size = $_FILES['cheque_4_file']['size'];

    if($_POST['cheque_5_number'] != NULL){
    $cheque_5_file = $_FILES['cheque_5_file']['tmp_name'];
    $cheque_5_size = $_FILES['cheque_5_file']['size'];
    $cheque_6_file = $_FILES['cheque_6_file']['tmp_name'];
    $cheque_6_size = $_FILES['cheque_6_file']['size'];
    }else{}

    if(!empty($_POST['cheque_1_number'])){
    $cheque_1_file = $_FILES['cheque_1_file']['tmp_name'];
    $cheque_1_size = $_FILES['cheque_1_file']['size'];
    }else{}

    $eid_file = $_FILES['eid_file']['tmp_name'];
    $eid_size = $_FILES['eid_file']['size'];


    if ((!in_array($extension1, ['zip', 'pdf', 'docx'])) && (!in_array($extension2, ['zip', 'pdf', 'docx'])) && (!in_array($extension3, ['zip', 'pdf', 'docx'])) && (!in_array($extension4, ['zip', 'pdf', 'docx']))  && (!in_array($extension7, ['zip', 'pdf', 'docx']))){
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
    } elseif (($_FILES['cheque_2_file']['size'] > 10000000) && ($_FILES['cheque_3_file']['size'] > 10000000) && ($_FILES['cheque_4_file']['size'] > 10000000) && ($_FILES['eid_file']['size'] > 10000000) && ($_FILES['cheque_5_file']['size'] > 10000000) && ($_FILES['cheque_6_file']['size'] > 10000000)  && ($_FILES['cheque_1_file']['size'] > 10000000)) { // file shouldn't be larger than 10Megabyte
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
    }else{
        // echo "Attachment clear to Proceed";
    }

    if($_POST['cheque_5_number'] != NULL){

    if((move_uploaded_file($cheque_2_file, $destination_cheque2)) && (move_uploaded_file($cheque_3_file, $destination_cheque3)) && (move_uploaded_file($cheque_4_file, $destination_cheque4)) && (move_uploaded_file($eid_file, $destination_eid)) && (move_uploaded_file($cheque_5_file, $destination_cheque5)) && (move_uploaded_file($cheque_6_file, $destination_cheque6))){

        if(!empty($_POST['cheque_1_number'])){if(move_uploaded_file($cheque_1_file, $destination_cheque1)){}}
        
            $_SESSION['name'] = $_POST['customer_name'];
            $_SESSION['amount'] = $amount;
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['apt_id'] = $_POST['id'];
            $_SESSION['pay_mode'] = $_POST['pay_mode'];
            $_SESSION['date'] = $date;
            $_SESSION['updated_by'] = $updated_by;
            $_SESSION['invoice_id'] = $invoice_id;
            $total_amount = $amount + $_POST['security'] + $_POST['insurance'] + $_POST['service_charge'];

            $sqlinsert = "INSERT INTO transactions (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `name`, `invoice_id`) VALUES ('".$_POST['id']."', '".$_POST['type']."', '".$total_amount."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['customer_name']."', '$invoice_id')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $_SESSION['name'] = $_POST['customer_name'];
                $_SESSION['customer_mobile'] = $_POST['customer_mobile'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['bedroom'] = $_POST['bedroom'];
                $_SESSION['amount'] = $amount;
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['apt_id'] = $_POST['id'];
                $_SESSION['pay_mode'] = $_POST['pay_mode'];
                $_SESSION['date'] = $date;
                $_SESSION['updated_by'] = $updated_by;
                $_SESSION['invoice_id'] = $invoice_id;
                $_SESSION['eid'] = $_POST['eid'];
                $_SESSION['eid_expiry'] = $_POST['eid_expiry'];
                $contract_to = date('Y-m-d', strtotime($_POST['contract_from']. ' + 364 days'));
                $total_amount = $amount + $_POST['security'] + $_POST['insurance'] + $_POST['service_charge'];
                $_SESSION['total_amount'] = $total_amount;
                $_SESSION['insurance'] = $_POST['insurance'];
                $_SESSION['security'] = $_POST['security'];
                $_SESSION['service_charge'] = $_POST['service_charge'];
                $_SESSION['contract_from'] = $_POST['contract_from'];
                $_SESSION['contract_to'] = $contract_to;
                $_SESSION['cheque_2_number'] = $_POST['cheque_2_number'];
                $_SESSION['cheque_3_number'] = $_POST['cheque_3_number'];
                $_SESSION['cheque_4_number'] = $_POST['cheque_4_number'];
                $_SESSION['cheque_5_number'] = $_POST['cheque_5_number'];
                $_SESSION['cheque_6_number'] = $_POST['cheque_6_number'];

                $maininsert = "INSERT INTO contracts (`cheque_1_bank`, `cheque_2_bank`, `cheque_3_bank`, `cheque_4_bank`, `cheque_5_bank`, `cheque_6_bank`, `cheque_1_size`, `cheque_1_name`, `cheque_1_date`, `cheque_1_number`, `apt_id`, `name`, `mobile`, `email`, `nationality`, `insurance`, `security`, `service_charge`, `contract_from`, `contract_to`, `bedroom`, `amount`, `date`, `updated_by`, `pay_mode`, `download_count`, `invoice_id`, `eid`, `eid_expiry`, `cheque_2_number`, `cheque_3_number`, `cheque_4_number`, `cheque_2_date`, `cheque_3_date`, `cheque_4_date`, `cheque_2_name`, `cheque_3_name`, `cheque_4_name`, `eid_name`, `cheque_2_size`, `cheque_3_size`, `cheque_4_size`, `eid_size`, `cheque_2_amount`, `cheque_3_amount`, `cheque_4_amount`, `status`, `cheque_5_number`, `cheque_5_amount`, `cheque_5_date`, `cheque_5_size`, `cheque_5_name`, `cheque_6_number`, `cheque_6_amount`, `cheque_6_date`, `cheque_6_size`, `cheque_6_name`, `total_cheques`) VALUES ('".$_POST['cheque_1_bank']."', '".$_POST['cheque_2_bank']."', '".$_POST['cheque_3_bank']."', '".$_POST['cheque_4_bank']."', '".$_POST['cheque_5_bank']."', '".$_POST['cheque_6_bank']."', '".$cheque_1_size."', '".$cheque_1_file."', '".$_POST['cheque_1_date']."', '".$_POST['cheque_1_number']."', '".$_POST['id']."', '".$_POST['customer_name']."', '".$_POST['customer_mobile']."', '".$_POST['email']."', '".$_POST['nationality']."', '".$_POST['insurance']."',  '".$_POST['security']."', '".$_POST['service_charge']."', '".$_POST['contract_from']."', '".$contract_to."', '".$_POST['bedroom']."', '".$amount."', '".$date."', '".$updated_by."', '".$_POST['pay_mode']."', '0', '$invoice_id', '".$_POST['eid']."', '".$_POST['eid_expiry']."', '".$_POST['cheque_2_number']."', '".$_POST['cheque_3_number']."', '".$_POST['cheque_4_number']."', '".$_POST['cheque_2_date']."', '".$_POST['cheque_3_date']."', '".$_POST['cheque_4_date']."', '".$file_name_cheque2."', '".$file_name_cheque3."', '".$file_name_cheque4."', '".$file_name_eid."', '".$cheque_2_size."', '".$cheque_3_size."', '".$cheque_4_size."', '".$eid_size."', '".$_POST['cheque_2_amount']."', '".$_POST['cheque_3_amount']."', '".$_POST['cheque_4_amount']."', 'Completed', '".$_POST['cheque_5_number']."', '".$_POST['cheque_5_amount']."', '".$_POST['cheque_5_date']."', '".$cheque_5_size."', '".$file_name_cheque5."', '".$_POST['cheque_6_number']."', '".$_POST['cheque_6_amount']."', '".$_POST['cheque_6_date']."', '".$cheque_6_size."', '".$file_name_cheque6."', '".$_POST['total_cheques']."')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {
                    
                    $rent = $amount + $_POST['cheque_2_amount'] + $_POST['cheque_3_amount'] + $_POST['cheque_4_amount'] + $_POST['cheque_5_amount'] + $_POST['cheque_6_amount'];

                    $aptinsert = "UPDATE apartments SET `last_payment`='".$amount."', `last_pay_date`='".$date."', `next_payment`='".$_POST['cheque_2_amount']."', `next_pay_date`='".$_POST['cheque_2_date']."', `rent`='".$rent."', `updated_at`='".$date."', `name`='".$_POST['customer_name']."', mobile='".$_POST['customer_mobile']."', email='".$_POST['email']."', nationality='".$_POST['nationality']."', contract_from='".$_POST['contract_from']."', contract_to='".$contract_to."', bedroom='".$_POST['bedroom']."', updated_by='".$updated_by."', eid='".$_POST['eid']."', eid_expiry='".$_POST['eid_expiry']."', contract_number='$invoice_id', status='1' WHERE id='".$_POST['id']."'";

                    $aptresult = mysqli_query($conn, $aptinsert);

                    if ($aptresult == true) {

                        $message = "Your Tenancy Contract is successfully created with ID ".$invoice_id."%0a%0aSaleel Real Estate";
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
                                    text: 'Contract added successfully with Apartment Update',
                                    showConfirmButton: false,
                                    // timer: 2000
                                });

                                setTimeout(function () {
                                    window.location.href = 'invoice_contract.php';
                                }, 800);
                            </script>
                            <?php
                         } else {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: 'Contract Adding Failed with Apartment update',
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
                        text: 'Contract Adding Failed',
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
                        text: 'Contract Adding Failed',
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

                setTimeout(function () {
                    window.location.href = '../apartment.php';
                }, 800);
            </script>
            <?php
        }


    }else{

        if((move_uploaded_file($cheque_2_file, $destination_cheque2)) && (move_uploaded_file($cheque_3_file, $destination_cheque3)) && (move_uploaded_file($cheque_4_file, $destination_cheque4)) && (move_uploaded_file($eid_file, $destination_eid))){

            if(!empty($_POST['cheque_1_number'])){if(move_uploaded_file($cheque_1_file, $destination_cheque1)){}}
        
            $_SESSION['name'] = $_POST['customer_name'];
            $_SESSION['amount'] = $amount;
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['apt_id'] = $_POST['id'];
            $_SESSION['pay_mode'] = $_POST['pay_mode'];
            $_SESSION['date'] = $date;
            $_SESSION['updated_by'] = $updated_by;
            $_SESSION['invoice_id'] = $invoice_id;
            $total_amount = $amount + $_POST['security'] + $_POST['insurance'] + $_POST['service_charge'];

            $sqlinsert = "INSERT INTO transactions (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `name`, `invoice_id`) VALUES ('".$_POST['id']."', '".$_POST['type']."', '".$total_amount."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['customer_name']."', '$invoice_id')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $_SESSION['name'] = $_POST['customer_name'];
                $_SESSION['customer_mobile'] = $_POST['customer_mobile'];
                $_SESSION['email'] = $_POST['email'];
                $_SESSION['bedroom'] = $_POST['bedroom'];
                $_SESSION['amount'] = $amount;
                $_SESSION['type'] = $_POST['type'];
                $_SESSION['apt_id'] = $_POST['id'];
                $_SESSION['pay_mode'] = $_POST['pay_mode'];
                $_SESSION['date'] = $date;
                $_SESSION['updated_by'] = $updated_by;
                $_SESSION['invoice_id'] = $invoice_id;
                $_SESSION['eid'] = $_POST['eid'];
                $_SESSION['eid_expiry'] = $_POST['eid_expiry'];
                $contract_to = date('Y-m-d', strtotime($_POST['contract_from']. ' + 365 days'));
                $total_amount = $amount + $_POST['security'] + $_POST['insurance'] + $_POST['service_charge'];
                $_SESSION['total_amount'] = $total_amount;
                $_SESSION['insurance'] = $_POST['insurance'];
                $_SESSION['security'] = $_POST['security'];
                $_SESSION['service_charge'] = $_POST['service_charge'];
                $_SESSION['contract_from'] = $_POST['contract_from'];
                $_SESSION['contract_to'] = $contract_to;
                $_SESSION['cheque_2_number'] = $_POST['cheque_2_number'];
                $_SESSION['cheque_3_number'] = $_POST['cheque_3_number'];
                $_SESSION['cheque_4_number'] = $_POST['cheque_4_number'];
                $_SESSION['total_cheques'] = $_POST['total_cheques'];

                $maininsert = "INSERT INTO contracts (`cheque_1_bank`, `cheque_2_bank`, `cheque_3_bank`, `cheque_4_bank`, `cheque_1_size`, `cheque_1_name`, `cheque_1_date`, `cheque_1_number`, `apt_id`, `name`, `mobile`, `email`, `nationality`, `insurance`, `security`, `service_charge`, `contract_from`, `contract_to`, `bedroom`, `amount`, `date`, `updated_by`, `pay_mode`, `download_count`, `invoice_id`, `eid`, `eid_expiry`, `cheque_2_number`, `cheque_3_number`, `cheque_4_number`, `cheque_2_date`, `cheque_3_date`, `cheque_4_date`, `cheque_2_name`, `cheque_3_name`, `cheque_4_name`, `eid_name`, `cheque_2_size`, `cheque_3_size`, `cheque_4_size`, `eid_size`, `cheque_2_amount`, `cheque_3_amount`, `cheque_4_amount`, `status`, `total_cheques`) VALUES ('".$_POST['cheque_1_bank']."', '".$_POST['cheque_2_bank']."', '".$_POST['cheque_3_bank']."', '".$_POST['cheque_4_bank']."', '".$cheque_1_size."', '".$file_name_cheque1."', '".$_POST['cheque_1_date']."', '".$_POST['cheque_1_number']."', '".$_POST['id']."', '".$_POST['customer_name']."', '".$_POST['customer_mobile']."', '".$_POST['email']."', '".$_POST['nationality']."', '".$_POST['insurance']."',  '".$_POST['security']."', '".$_POST['service_charge']."', '".$_POST['contract_from']."', '".$contract_to."', '".$_POST['bedroom']."', '".$amount."', '".$date."', '".$updated_by."', '".$_POST['pay_mode']."', '0', '$invoice_id', '".$_POST['eid']."', '".$_POST['eid_expiry']."', '".$_POST['cheque_2_number']."', '".$_POST['cheque_3_number']."', '".$_POST['cheque_4_number']."', '".$_POST['cheque_2_date']."', '".$_POST['cheque_3_date']."', '".$_POST['cheque_4_date']."', '".$file_name_cheque2."', '".$file_name_cheque3."', '".$file_name_cheque4."', '".$file_name_eid."', '".$cheque_2_size."', '".$cheque_3_size."', '".$cheque_4_size."', '".$eid_size."', '".$_POST['cheque_2_amount']."', '".$_POST['cheque_3_amount']."', '".$_POST['cheque_4_amount']."', 'Completed', '".$_POST['total_cheques']."')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {

                    $rent = $amount + $_POST['cheque_2_amount'] + $_POST['cheque_3_amount'] + $_POST['cheque_4_amount'];

                    $aptinsert = "UPDATE apartments SET `last_payment`='".$amount."', `last_pay_date`='".$date."', `next_payment`='".$_POST['cheque_2_amount']."', `next_pay_date`='".$_POST['cheque_2_date']."', `rent`='".$rent."', `updated_at`='".$date."', `name`='".$_POST['customer_name']."', mobile='".$_POST['customer_mobile']."', email='".$_POST['email']."', nationality='".$_POST['nationality']."', contract_from='".$_POST['contract_from']."', contract_to='".$contract_to."', bedroom='".$_POST['bedroom']."', updated_by='".$updated_by."', eid='".$_POST['eid']."', eid_expiry='".$_POST['eid_expiry']."', contract_number='$invoice_id', status='1' WHERE id='".$_POST['id']."'";

                    $aptresult = mysqli_query($conn, $aptinsert);

                    if ($aptresult == true) {

                        $message = "Your Tenancy Contract is successfully created with ID ".$invoice_id."%0a%0aSaleel Real Estate";
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
                                    text: 'Contract added successfully with Apartment Update',
                                    showConfirmButton: false,
                                    // timer: 2000
                                });

                                setTimeout(function () {
                                    window.location.href = 'invoice_contract.php';
                                }, 800);
                            </script>
                            <?php
                         } else {
                                ?>
                                <script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: 'Contract Adding Failed with Apartment update',
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
                        text: 'Contract Adding Failed',
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
                        text: 'Contract Adding Failed',
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

                setTimeout(function () {
                    window.location.href = '../apartment.php';
                }, 800);
            </script>
            <?php
        }


    }



}else{
    ?>
    <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to load the script',
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