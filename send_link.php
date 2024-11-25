<?php 
    $description = "Click the below link pay job token amount";
        // $name = 'Sanket Kanawade';
        $contact = $_POST['mobile'];
        $amount = $_POST['amount'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $end_user_id = $_POST['end_user_id'];
        $bulk_id = $_POST['bulk_id'];
        $gst_number = $_POST['gst_number'];
        $agency_id = $_POST['agency_id'];

        $amount_in_paise = $amount * 100;
        // $key_id = 'rzp_live_jLSe7OELxkMCHf';
        // $secret = 'zGkDGFtbyPqPdYpwCNadvQFw';
         $key_id = 'rzp_live_wXmbkXPVVABiDl';
         $secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
        $unique_identifier = uniqid('payment_');
        $api_endpoint = 'https://api.razorpay.com/v1/payment_links';

       $data = json_encode([
            'amount' => $amount_in_paise,
            'currency' => 'INR',
            'description' => $description,
            'customer' => [
                'name' => $name,
                'email' => $email,
                'contact' => $contact,

            ],
            'notify' => [
                'sms' => true,
                'email' => true,
            ],
            // 'reminder_enable' => true,
            // 'notes' => [
            //     'policy_name' => 'Jeevan Bima',
            //     'unique_identifier' => $unique_identifier

            // ],
            'reminder_enable' => true,
            'callback_url' => 'https://mounarchtech.com/vocoxp/verify_pay.php?end_user_id='.$end_user_id.'&paid_amount='.$amount.'&bulk_id='.$bulk_id.'&gst_number='.$gst_number.'&agency_id='.$agency_id, 
            'callback_method' => 'get', 
            'notes' => [
                'policy_name' => 'Verification',
                'unique_identifier' => $unique_identifier,
            ]
        ]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
        ];

        $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, $api_endpoint);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_URL, $api_endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            exit;
        }
        curl_close($ch);
        $response_data = json_decode($response, true);
        header('Content-Type: application/json');
        echo json_encode($response_data);
        exit();
    ?>

