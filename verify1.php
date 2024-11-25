<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$selectedImages = isset($input['selectedImages']) ? $input['selectedImages'] : [];

// List of images and their categories
$images = [
    ['id' => 1, 'url' => 'images/traffic_light1.jpg', 'category' => 'traffic_light'],
    ['id' => 2, 'url' => 'images/car1.jpg', 'category' => 'car'],
    ['id' => 3, 'url' => 'images/tree2.jpg', 'category' => 'tree'],
    ['id'=>4,'url'=>'images/bridge2.jpeg','category'=>'bridge'],
    ['id' => 5, 'url' => 'images/deer.jpeg', 'category' => 'deer'],
    ['id' => 6, 'url' => 'images/tiger.jpeg', 'category' => 'tiger'],

    ['id' => 7, 'url' => 'images/traffic_light2.jpg', 'category' => 'traffic_light'],
    ['id' =>8, 'url' => 'images/car2.jpg', 'category' => 'car'],
    ['id' => 9, 'url' => 'images/tree1.jpg', 'category' => 'tree'],
    ['id'=>10,'url'=>'images/bridge1.jpeg','category'=>'bridge'],
    ['id' => 11, 'url' => 'images/deer1.jpeg', 'category' => 'deer'],
    ['id' => 12, 'url' => 'images/tiger1.jpeg', 'category' => 'tiger'],
];

$captchaCategory = $_SESSION['captcha_category'];

// Get correct image IDs
$correctImageIds = array_map(function($image) {
    return $image['id'];
}, array_filter($images, function($image) use ($captchaCategory) {
    return $image['category'] === $captchaCategory;
}));

// Check if selected images are correct
$isCorrect = !array_diff($correctImageIds, $selectedImages) && !array_diff($selectedImages, $correctImageIds);

echo json_encode(['success' => $isCorrect]);
?>
