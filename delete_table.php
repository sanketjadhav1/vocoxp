<?php 
include 'connection.php';
//Application Vocoxp databese connection//
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
//Central Database Connection//
$connection1 = database::getInstance();
$mysqli1 = $connection1->getConnection();


date_default_timezone_set('Asia/kolkata');
 $current_date = date("Y-m-d");
// die();
$system_date = date("d-m-Y");
$system_date_time = date("Y-m-d H:i:s");

$ver_query = "SELECT DISTINCT `agency_id` FROM `direct_verification_details_all`";
$ver_result = $mysqli->query($ver_query);
  if ($ver_result->num_rows > 0) {
    // Fetch the data in a loop
    while ($row = $ver_result->fetch_assoc()) {
         if (!empty($row['agency_id'])) {
            $agency_id = $row['agency_id'];
            $agency_id = $row['agency_id'];
            // Query for Aadhar data
             $aadhar_query = "DELETE FROM `direct_aadhar_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($aadhar_query);
            $edited_aadhar_query = "DELETE FROM  `edited_direct_aadhar_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_aadhar_query);

            // Query for DL data
            $dl_query = "DELETE FROM `direct_dl_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($dl_query);

            $edited_dl_query = "DELETE FROM `edited_direct_dl_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_dl_query);
           
            // Query for Voter data
            $voter_query = "DELETE FROM `direct_voter_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($voter_query);

            $edited_voter_query = "DELETE FROM `edited_direct_voter_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_voter_query);
            
            // Query for International data
            $international_query = "DELETE FROM `direct_international_passport_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($international_query);

            $edited_international_query = "DELETE FROM `edited_direct_international_passport_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_international_query);
            
            // Query for Pan data
            $pan_query = "DELETE FROM `direct_pan_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($pan_query);

            $edited_pan_query = "DELETE FROM `edited_direct_pan_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_pan_query);
           
            // Query for Passport data
            $passport_query = "DELETE FROM `direct_passport_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($passport_query);

            $edited_passport_query = "DELETE FROM `edited_direct_passport_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($edited_passport_query);

            // Query for Direct Verification data
            $passport_query = "DELETE FROM `direct_verification_details_all` WHERE `agency_id`='$agency_id'";
             $mysqli->query($passport_query);

             //Query for Verification Id
             $verification_header = "SELECT `verification_id`, `name` FROM `verification_header_all` WHERE `status`='1'";
          $verification_result = $mysqli1->query($verification_header);
          while ($vrow = $verification_result->fetch_assoc()) 
           {
            $verification_id = $vrow['verification_id'];

            $select_data = "SELECT SUM(base_amount) AS base_amount, SUM(cgst_amount) AS cgst_amount , SUM(sgst_amount) AS sgst_amount FROM wallet_payment_transaction_all WHERE `verification_id` = '$verification_id' AND `agency_id` = '$agency_id' AND DATE_FORMAT(transaction_on, '%m-%d') = DATE_FORMAT('$current_date', '%m-%d')";
          $select_result = $mysqli->query($select_data);
            $select_array = mysqli_fetch_assoc($select_result);
            if($select_array['base_amount']!=""){
  $base_amount=$select_array['base_amount']."<br>";
            $cgst_amount=$select_array['cgst_amount']."<br>";
            $sgst_amount=$select_array['sgst_amount']."<br>";
        $select_count = "SELECT Count(*) as count FROM `wallet_payment_transaction_all` WHERE `verification_id` = '$verification_id' AND `agency_id` = '$agency_id' AND DATE_FORMAT(transaction_on, '%m-%d') = DATE_FORMAT('$current_date', '%m-%d')";
          $count_result = $mysqli->query($select_count);
            $count_array = mysqli_fetch_assoc($count_result);
           $count=$select_array['count']."<br>";
           $insert_query = "INSERT INTO `wallet_payment_transaction_all`(`agency_id`, `user_id`, `requested_from`, `purchase_type`, `verification_id`, `base_amount`, `cgst_amount`, `sgst_amount`, `transaction_on`, `line_type`, `quantity`, `settled_for`)  
    VALUES ('$agency_id', '$agency_id', '2', '1', '$verification_id', '$base_amount', '$cgst_amount', '$sgst_amount', '$current_date', '2', '$count','$current_date')";
        $query_result = $mysqli->query($insert_query);
        
            }
         }
          // Query for Delete wallet data
            $wallet_query = "DELETE FROM `wallet_payment_transaction_all` WHERE `line_type`='1' and `requested_from`='2'";
             $mysqli->query($wallet_query);

        }
    }
}
// wallet_payment_transaction_all
?>