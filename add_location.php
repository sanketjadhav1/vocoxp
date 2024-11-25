<?php

$host = 'localhost';
$db = 'mounac53_vocoxp3_0';
$user = 'root';
$pass = '';

$codes = [
    '500' => 'Database connection failed',
    '400' => 'Required field is missing or invalid',
    '501' => 'Failed to insert record into database',
    '405' => 'Invalid request method',
    '401' => 'Invalid JSON input',
    '200' => 'Location added successfully',
    '409' => 'Conflict: visitor_location_id already exists'
];

header('Content-Type: application/json');

try 
{
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) 
{
    echo json_encode(
        [
        'status' => 'error',
        'error_code' => '500',
        'message' => $codes['500'],
        'error_details' => $e->getMessage()
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    $data = $_POST;

    error_log("Received Data: " . print_r($data, true));

    if (json_last_error() !== JSON_ERROR_NONE) 
    {
        echo json_encode(
            [
            'status' => 'error',
            'error_code' => '401',
            'message' => $codes['401'],
            'error_details' => json_last_error_msg()
        ]);
        exit;
    }

    $requiredFields = 
    [
        'agency_id', 'visitor_location_id', 'location_name',
        'operational_from', 'location_admins', 'location_state',
        'location_city', 'location_pincode', 'location_radius',
        'location_coordinates', 'type', 'visiting_hours','weekoffs'
    ];

    
    foreach ($requiredFields as $field) 
    {
        if (empty($data[$field])) 
        {
            echo json_encode(
                [
                'status' => 'error',
                'error_code' => '400',
                'message' => $codes['400'],
                'field' => $field
            ]);
            exit;
        }
    }

    
    $errors = [];

    if (!is_numeric($data['visitor_location_id'])) 
    {
        $errors[] = 'visitor_location_id must be numeric';
    }
    if (!is_numeric($data['location_pincode'])) 
    {
        $errors[] = 'location_pincode must be numeric';
    }
    if (!is_numeric($data['location_radius'])) 
    {
        $errors[] = 'location_radius must be numeric';
    }
    if (!is_numeric($data['type']) || !in_array((int)$data['type'], [1, 2, 3])) 
    {
        $errors[] = 'type must be one of the following values: 1, 2, or 3';
    }
    
    
    $weekoffs = explode(',', $data['weekoffs']);
    foreach ($weekoffs as $weekoff) 
    {
        if (!in_array((int)$weekoff, [1, 2, 3, 4, 5, 6, 7])) 
        {
            $errors[] = 'weekoffs must be a comma-separated list of values: 1, 2, 3, 4, 5, 6, or 7';
            break;
        }
    }

    $dateFields = ['visiting_hours'];
    foreach ($dateFields as $dateField) 
    {
        $visitingHours = explode(' @ ', $data[$dateField]);
        
        if (count($visitingHours) !== 2 ||
            !DateTime::createFromFormat('H:i:s', $visitingHours[0]) ||
            !DateTime::createFromFormat('H:i:s', $visitingHours[1])) 
        {
            $errors[] = "$dateField must be in 'H:i:s - H:i:s' format";
        } 
    }


    if (!empty($errors)) 
    {
        echo json_encode(
            [
            'status' => 'error',
            'error_code' => '401',
            'message' => $codes['401'],
            'errors' => $errors
        ]);
        exit;
    }

    try 
    {
        
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM agency_visitor_location_header_all WHERE visitor_location_id = :visitor_location_id");
        $stmtCheck->execute([':visitor_location_id' => $data['visitor_location_id']]);
        $count = $stmtCheck->fetchColumn();

        if ($count > 0) 
        {
            echo json_encode(
                [
                'status' => 'error',
                'error_code' => '409',
                'message' => $codes['409']
            ]);
            exit;
        }

        
        $pdo->beginTransaction();

        
        $stmt1 = $pdo->prepare("INSERT INTO agency_visitor_location_header_all
        (
            agency_id, visitor_location_id, location_name,
            operational_from, location_admins, location_state,
            location_city, location_pincode, location_radius,
            location_coordinates)
            VALUES
            (:agency_id, :visitor_location_id, :location_name,
            :operational_from, :location_admins, :location_state,
            :location_city, :location_pincode, :location_radius,
            :location_coordinates)"
        );

        $params = [
            ':agency_id' => $data['agency_id'],
            ':visitor_location_id' => $data['visitor_location_id'],
            ':location_name' => $data['location_name'],
            ':operational_from' => $data['operational_from'],
            ':location_admins' => $data['location_admins'],
            ':location_state' => $data['location_state'],
            ':location_city' => $data['location_city'],
            ':location_pincode' => $data['location_pincode'],
            ':location_radius' => $data['location_radius'],
            ':location_coordinates' => $data['location_coordinates']
        ];

        if (!$stmt1->execute($params)) 
        {
            throw new Exception('Failed to insert record into primary table');
        }

        
        $stmt2 = $pdo->prepare("INSERT INTO visitor_location_setting_details_all
        (
            agency_id, visitor_location_id, type, visiting_hours, weekoffs)
        VALUES
        (:agency_id, :visitor_location_id, :type, :visiting_hours, :weekoffs)"
        );

        
        $weekoffsStr = implode(',', array_map('intval', $weekoffs));

        $params2 = [
            ':agency_id' => $data['agency_id'],
            ':visitor_location_id' => $data['visitor_location_id'],
            ':type' => $data['type'],
            ':visiting_hours' => $data['visiting_hours'],
            ':weekoffs' => $weekoffsStr
        ];

        if (!$stmt2->execute($params2)) 
        {
            throw new Exception('Failed to insert record into secondary table');
        }

        
        $pdo->commit();

        echo json_encode(
            [
            'status' => 'success',
            'success_code' => '200',
            'message' => $codes['200']
        ]);

    } 
    catch (Exception $e) 
    {
        
        $pdo->rollBack();

        echo json_encode(
            [
            'status' => 'error',
            'error_code' => '501',
            'message' => $codes['501'],
            'error_details' => $e->getMessage()
        ]);
    }

} 
else 
{
    echo json_encode(
        [
        'status' => 'error',
        'error_code' => '405',
        'message' => $codes['405']
    ]);
}
