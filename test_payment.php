
<?php
// Your Razorpay credentials
// $key_id = 'your_key_id'; // Replace with your Razorpay Key ID
// $secret = 'your_secret_key'; // Replace with your Razorpay Secret Key
$description = "Click the below link pay job token amount";
        // $name = 'Sanket Kanawade';
        $contact = $_POST['mobile'];
        $amount = $_POST['amount'];
        $email = $_POST['email'];
        $name = $_POST['name'];

        $amount_in_paise = $amount * 100;
 $key_id = 'rzp_live_wXmbkXPVVABiDl';
$secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
$unique_identifier = uniqid('payment_');
// Razorpay API endpoint for creating payment links
$api_endpoint = 'https://api.razorpay.com/v1/payment_links';

// Data to send in the POST request
$data = [
    'amount' => $amount_in_paise, // Amount in paise (1000 paise = ₹10)
    'currency' => 'INR', 
    'reference_id' => '#523442101',
    'description' => $description,
    'customer' => [
        'name' => $name,
        'contact' => $contact,
        'email' => $email
    ],
    'notify' => [
        'sms' => true,
        'email' => true
    ],
    'notes' => [
                'policy_name' => 'Verification',
                'unique_identifier' => $unique_identifier,
            ],
    'reminder_enable' => true,
     'callback_url' => 'https://mounarchtech.com/vocoxp/verification.php?end_user_id=END-00190', 
            'callback_method' => 'get', 
    'options' => [
        'checkout' => [
            'method' => [
                'netbanking' => true,
                'card' => true,
                'upi' => true,
                'wallet' => true
            ]
        ]
    ]
];

// Convert the data array to JSON
$data_json = json_encode($data);

// Set up cURL headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
];

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $api_endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
    exit;
}

// Close cURL session
curl_close($ch);

// Decode the response JSON and print the result
$response_data = json_decode($response, true);
  header('Content-Type: application/json');
        echo json_encode($response_data);
// echo '<pre>';
// print_r($response_data);
// echo '</pre>';

?>

<?PHP
// // Razorpay credentials
// $description = "Click the below link pay job token amount";
//         // $name = 'Sanket Kanawade';
//         $contact = $_POST['mobile'];
//         $amount = $_POST['amount'];
//         $email = $_POST['email'];
//         $name = $_POST['name'];

//         $amount_in_paise = $amount * 100;
//  $key_id = 'rzp_live_wXmbkXPVVABiDl';
// $secret = 'h0QEqFVRQsYlzhFSgML3Dl2J';
 
// // Your Razorpay credentials
// // $key_id = 'your_key_id'; // Replace with your Razorpay Key ID
// // $secret = 'your_secret_key'; // Replace with your Razorpay Secret Key

// // Razorpay API endpoint for creating an order
// $api_endpoint = 'https://api.razorpay.com/v1/orders';

// // Data to send in the POST request (order details)
// $data = [
//     'amount' =>$amount_in_paise, // Amount in paise (50000 paise = ₹500)
//     'currency' => 'INR',
//     'receipt' => 'Receipt #20',
//     'notes' => [
//         'name' => $name,
//         'email' => $email,
//         'contact' => $contact,
//         'amount' => $amount
//     ]
// ];

// // Convert the data array to JSON
// $data_json = json_encode($data);

// // Set up cURL headers
// $headers = [
//     'Content-Type: application/json',
//     'Authorization: Basic ' . base64_encode($key_id . ':' . $secret)
// ];

// // Initialize cURL session
// $ch = curl_init();

// // Set cURL options
// curl_setopt($ch, CURLOPT_URL, $api_endpoint);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// // Execute the cURL request and capture the response
// $response = curl_exec($ch);

// // Check for cURL errors
// if (curl_errno($ch)) {
//     echo 'cURL error: ' . curl_error($ch);
//     exit;
// }

// // Close cURL session
// curl_close($ch);

// // Decode the response JSON and print the result
// echo$response_data = json_decode($response, true);
// // echo '<pre>';
// // print_r($response_data);
// // echo '</pre>';

?>
