<?php
session_start();

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

// Select a random category for CAPTCHA
$categories = ['traffic_light', 'car', 'tree','bridge','deer','tiger'];
$captchaCategory = $categories[array_rand($categories)];
$_SESSION['captcha_category'] = $captchaCategory;

// Filter images by the selected category
$captchaImages = array_filter($images, function($image) use ($captchaCategory) {
    return $image['category'] === $captchaCategory;
});

// Select some images from other categories to mix with CAPTCHA images
$otherImages = array_filter($images, function($image) use ($captchaCategory) {
    return $image['category'] !== $captchaCategory;
});
$otherImages = array_slice($otherImages, 0, 4);

// Combine and shuffle images
$captchaImages = array_merge($captchaImages, $otherImages);
shuffle($captchaImages);

// Prepare the response
$response = [
    'question' => 'Select all images with ' . str_replace('_', ' ', $captchaCategory),
    'images' => array_map(function($image) {
        return ['id' => $image['id'], 'url' => $image['url']];
    }, $captchaImages)
];

echo json_encode($response);
?>
