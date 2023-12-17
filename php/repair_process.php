<?php
error_reporting(E_ERROR | E_PARSE);
if($_POST['type'] === "Repair"){

    $sql_file = "SELECT * FROM `repairs` ORDER BY `id` DESC LIMIT 1";
    $query_file = mysqli_query($conn, $sql_file);
    $row_file = mysqli_fetch_assoc($query_file);
    $last_id = $row_file['id'];
    $new_name = "REPR_DOC" . ($last_id + 1);
    $invoice_id = "REPR_INV" . ($last_id + 1);

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
        $_SESSION['maintenance_type'] = $_POST['maintenance_type'];
        $_SESSION['apt_id'] = $_POST['id'];
        $_SESSION['pay_mode'] = $_POST['pay_mode'];
        $_SESSION['date'] = $date;
        $_SESSION['updated_by'] = $updated_by;
        $_SESSION['invoice_id'] = $invoice_id;

            $sqlinsert = "INSERT INTO transactions (`apt_id`, `type`, `amount`, `date`, `status`, `updated_by`, `pay_mode`, `name`, `invoice_id`) VALUES ('".$_POST['id']."', '".$_POST['type']."', '".$_POST['amount']."', '".$date."', 'Completed', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['customer_name']."', '$invoice_id')";

            $queryinsert = mysqli_query($conn, $sqlinsert);

            if ($queryinsert == true) {

                $maininsert = "INSERT INTO repairs (`type`, apt_id, contract_number, `name`, invoice_id, `date`, updated_by, pay_mode, attachment, file_name, file_size, download_count, amount, `status`) VALUES('".$_POST['maintenance_type']."', '".$_POST['id']."', '".$_POST['contract_number']."', '".$_POST['customer_name']."', '".$invoice_id."', '".$date."', '".$updated_by."', '".$_POST['pay_mode']."', '".$_POST['pay_mode']."', '".$file_name."', '".$size."', '0', '".$_POST['amount']."', 'Completed')";

                $mainresult = mysqli_query($conn, $maininsert);

                if ($mainresult == true) {

                    $aptupdate = "UPDATE apartments SET last_repair_type='".$_POST['maintenance_type']."', last_repair_date='".$date."', updated_by='".$updated_by."', updated_at='".$date."' WHERE id='".$_POST['id']."'";

                    $aptup_result = mysqli_query($conn, $aptupdate);

                    if ($aptup_result == true) {

                            // $message = "Your Maintenance is successfully created with ID ".$invoice_id."%0a%0aSaleel Real Estate";
                            // $nmob = substr($_POST['customer_mobile'], 1);
                            // $add_prefix = '971';
                            // $mob = $add_prefix.$nmob;
                            //     $ch = curl_init();
                            //     curl_setopt($ch, CURLOPT_URL, "http://51.210.118.93:8080/websmpp/websms");
                            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            //     curl_setopt($ch, CURLOPT_POST, 1);
                            //     curl_setopt($ch, CURLOPT_POSTFIELDS, "user=ALIhsanTR&pass=AlIhsan@5&sid=AL%20IHSAN&mno=".$mob."&type=4&text=".$message."");
                            //     $headers = array();
                            //     $headers[]= 'Content-Type: application/x-www-form-urlencoded';
                            //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            //     $result = curl_exec($ch);
                            //     if (curl_errno($ch)){
                            //        echo 'ERROR:'. curl_error($ch);
                            //         echo " Please try again later";
                            //     }
                            //     curl_close($ch);
        
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Maintenance added successfully with Apartment Update',
                            showConfirmButton: false,
                            // timer: 2000
                        });
    
                        setTimeout(function () {
                            window.location.href = 'invoice_repair.php';
                        }, 800);
                    </script>
                    <?php

        } else {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Failed to update Maintenance on Apartment',
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
                text: 'Failed to update Maintenance',
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
                    text: 'Failed to insert transaction query',
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