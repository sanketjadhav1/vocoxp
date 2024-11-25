

    <?php
    include_once 'connection.php';
$connection = connection::getInstance();
$mysqli = $connection->getConnection();
$key_id = 'rzp_live_wXmbkXPVVABiDl';
    $secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
    $headers = [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
    ];
function create_customer() {
    // Razorpay API call to create or retrieve customer
    $note="customer create";
    $headers = [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
    ];
    // Visitor data
                    $postdata = array(
                        "name" => 'namrata',
                        "email" => 'namrata.r.shrivas@gmail.com',
                        "contact" => '9820898379',
                        "notes" => array(
                            "notes_key_1" => $note,
                            "notes_key_2" => ""
                        )
                    );

                    // Create customer on Razorpay
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.razorpay.com/v1/customers',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => json_encode($postdata),
                        CURLOPT_HTTPHEADER => $headers,
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);

                    $customerRes = json_decode($response, true);

                    if (isset($customerRes['error'])) {
                        // Check if error is due to customer already existing
                        if ($customerRes['error']['code'] == 'BAD_REQUEST_ERROR') {
                            // Retrieve customer ID from the error message
                            // Assuming visitor_email is unique; use Razorpay's customer search endpoint to retrieve ID
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => 'https://api.razorpay.com/v1/customers?email=' . $email,
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_HTTPHEADER => $headers,
                            ));
                            
                            $response = curl_exec($curl);
                            curl_close($curl);

                            $existingCustomer = json_decode($response, true);
                            if (isset($existingCustomer['items'][0]['id'])) {
                                $customerId = $existingCustomer['items'][0]['id'];
                            } else {
                                echo json_encode(['error_code' => '101', 'message' => 'Unable to retrieve existing customer']);
                                exit;
                            }
                        } else {
                            // Handle other errors
                            $errMessage = $customerRes['error']['description'];
                            echo json_encode(['res' => 'error', 'message' => $errMessage]);
                            exit;
                        }
                    } else {
                        $customerId = $customerRes['id'];
                    }
    return $customerId ?? null;
}
    function create_qr_code($name, $amount_in_paise, $customer_id,$headers) {
        $qrNote = "QR code payment of " . ($amount_in_paise / 100) . " INR";
        $pdesc = "Razorpay QR code Payment";

        $unique_identifier = uniqid('payment_');
        $qrpostData = array(
            "type" => "upi_qr",
            "name" => $name,
            "usage" => "single_use",
            "fixed_amount" => true,
            "payment_amount" => $amount_in_paise,
            "description" => $pdesc,
            "customer_id" => $customer_id,
            "notes" => array(
                "purpose" => $qrNote,
                "unique_identifier" => $unique_identifier
            )
        );

        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/qr_codes',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($qrpostData),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response1 = curl_exec($curl1);

        if (curl_errno($curl1)) {
            echo json_encode([
                "error_code" => 101,
                "message" => "CURL Error: " . curl_error($curl1)
            ]);
            curl_close($curl1);
            exit;
        }

        curl_close($curl1);

        $qr_response = json_decode($response1, true);

        if (isset($qr_response['error'])) {
            echo json_encode([
                "error_code" => 101,
                "message" => "Razorpay Error: " . $qr_response['error']['description']
            ]);
            exit;
        }

        // print_r($qr_response);

        return $qr_response ?? null;
    }

  
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = 'sanket';
$amount_in_paise = 100;
        $customerId = create_customer();

// $customerId = 'GFET234242';
$key_id = 'rzp_live_wXmbkXPVVABiDl';
$secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
$headers = [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
];

        $qr_response = create_qr_code($name, $amount_in_paise, $customerId,$headers);

        if ($qr_response) {
            echo "<h3>QR Code generated successfully!</h3>";
            echo "<p>Scan the QR code below to make the payment:</p>";
            echo "<pre>" . print_r($qr_response, true) . "</pre>";

            if (isset($qr_response['image_url'])) {
               $u_i = $qr_response['notes']['unique_identifier'];
               $qr_id = $qr_response['id'];

                $stmt = "INSERT INTO `visitor_payment_transaction_all` (`v_transaction_id`, `paid_amount`, `payment_status`, `gateway_id`) VALUES ('$u_i', '$amount_in_paise', 'requested', '$qr_id')";
            mysqli_query($mysqli, $stmt);
                echo '<img src="' . $qr_response['image_url'] . '" alt="QR Code" />';
            } else {
                echo "<p>QR Code URL not available in the response.</p>";
            }
        }
    }
    ?>

