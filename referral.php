<?php
session_start();
$ref = isset($_GET['ref']) ? $_GET['ref'] : '';
$product = isset($_GET['product']) ? $_GET['product'] : '';

// If not set, fallback to homepage
if (!$ref || !$product) {
    header('Location: index.php');
    exit;
}

// Parse product type and id
if (preg_match('/^(book|course|test)_(\d+)$/', $product, $matches)) {
    $type = $matches[1];
    $id = $matches[2];
} else {
    // Invalid product param
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['id']) && $_SESSION['id']) {
// Track referral for logged-in users
    $_SESSION['affiliate_ref'] = $ref;
    $_SESSION['affiliate_product'] = $product;
    // Redirect to product page    
    if ($type === 'book') {
        header('Location: book_details.php?id=' . $id);
    } elseif ($type === 'course') {
        header('Location: course_details.php?id=' . $id);
    } elseif ($type === 'test') {
        header('Location: test_details.php?id=' . $id);
    } else {
        header('Location: index.php');
    }
    exit;
} else {
    // Not logged in, redirect to register with referral and product
    header('Location: register.php?ref=' . urlencode($ref) . '&product=' . urlencode($product));
    exit;
}
