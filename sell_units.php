<?php
/**
 * Handles market sales
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');
nocache_headers();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($userId) || !is_user_logged_in()) {
    wp_redirect(get_permalink(3582));
    exit;
}

$activeTab = $_POST['currentTab'] ? sanitize_text_field($_POST['currentTab']) : 'air';
$marketRedirectUrl = get_permalink(3938) . $activeTab;
$userId = get_current_user_id();
$userData = get_user_meta($userId);

$totalMoney = $userData['money'][0];
$userLock = $userData['user_lock'][0];
$marketSellMultiplier = (2.2 * 0.5);

$specialUnitsArray = [
    'spyplane',
    'sniper',
    'thief',
    'saboteur',
    'spy'
];

if ($userLock == 1) {
    update_user_meta($userId, 'user_lock', 0);
    $_SESSION['status'] = 'Please try again.';
    wp_redirect(get_permalink(3582));
    exit;
} else {
    update_user_meta($userId, 'user_lock', 1);
    include 'units_array.php';

    $specialSelling = 0;
    foreach ($units as $key => $order) {
        if (!empty($_POST["$key"])) {
            $ownedUnits = $userData[$key.'_owned'][0];
            $soldUnits = ceil($_POST["$key"]);

            if ($_POST[$key] < 0) {
                $_SESSION['status'] = 'Enter a valid number';
                wp_redirect($marketRedirectUrl);
                exit;
            }

            $letterCheck = isset($_POST[$key]) ? $_POST[$key] : 0;
            if (!is_numeric($letterCheck)) {
                $_SESSION['status'] = 'Enter a valid number';
                wp_redirect($marketRedirectUrl);
                exit;
            }

            if ($ownedUnits < $soldUnits) {
                $soldUnits = $ownedUnits;
            }
            if (in_array($key, $specialUnitsArray)) {
                $specialSelling += $_POST[$key];
            }
        }
    }

    $specials_sold = $userData['special_sold_today'][0];

    if (($specials_sold+$specialSelling) > 50) {
        $_SESSION['status'] = 'Cannot sell more than 50 special units per day';
        wp_redirect($marketRedirectUrl);
        exit;
    } else {
        update_user_meta($userId, 'special_sold_today', $specials_sold+$specialSelling);
    }


    $totalSelling = 0;

    foreach ($units as $key => $order) {
        if (!empty($_POST["$key"])) {
            $ownedUnits = $userData[$key.'_owned'][0];
            $soldUnits = ceil($_POST["$key"]);
            if ($_POST["$key"] < 0) {
                $_SESSION['status'] = 'Enter a valid number';
                wp_redirect($marketRedirectUrl);
                exit;
            }
            if (empty($_POST["$key"])) {
                $letterCheck = 0;
            } else {
                $letterCheck = $_POST["$key"];
            }
            if (!is_numeric($letterCheck)) {
                $_SESSION['status'] = 'Enter a valid number';
                wp_redirect($marketRedirectUrl);
                exit;
            }

            if ($ownedUnits < $soldUnits) {
                $soldUnits = $ownedUnits;
            }
            if ($key == 'spy' || $key == 'spyplane' || $key == 'sniper') {
                $specialSelling+=$_POST["$key"];
            }
            $price = $order['price'] * $marketSellMultiplier;

            $soldAmount = $price * $soldUnits;
            $totalSelling += $soldAmount;
            update_user_meta($userId, $key.'_owned', $ownedUnits - $soldUnits);

            $unitsSold = $userData['units_sold'][0];
            update_user_meta($userId, 'units_sold', $unitsSold+$soldUnits);

            // Add log entry
            // Todo: Replace this with a standardized logger (E.g. Monolog)
            $file = 'marketselllog.txt';
            $current = file_get_contents($file);
            $current .= "ID: ".$userId."\n";
            $current .= "Units sold: ".$soldUnits."\nType: ".$key."\n\n";
            file_put_contents($file, $current);

            update_user_meta($userId, 'money', $totalMoney+$totalSelling);
        }
    }
    update_user_meta($userId, 'user_lock', 0);
    count_all_stats($userId);
    $_SESSION['status'] = $soldUnits.' units sold for a price of $ '. number_format($soldAmount, 0, ',', ' ');
    wp_redirect($marketRedirectUrl); //result
    exit;
}
