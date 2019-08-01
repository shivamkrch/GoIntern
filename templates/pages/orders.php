<?php
include_once $_SERVER['DOCUMENT_ROOT']."/config/db.php";

$email = $_COOKIE['user-email'];
$type = $_COOKIE['user-type'];

$db = new DB();
// Get the logged in ser's details
$db->query("SELECT * FROM ".($type=='rest'?'restaurants':'customers')." WHERE email=:email");
$db->bind(':email', $email);
$res = $db->single();
// If no user found go to login page by logging out the user and clearing cookies
if($res == ''){
    header('location: /logout');
}

include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/header.php';
include_once $_SERVER['DOCUMENT_ROOT']."/templates/partials/profile-header.php";
if($type == 'cust'){
    // Show customers orders if the user is customer
    include_once $_SERVER['DOCUMENT_ROOT']."/templates/pages/customers/orders.php";
}else{
    // Show restaurants orders if the user is restaurant
    include_once $_SERVER['DOCUMENT_ROOT']."/templates/pages/restaurants/orders.php";
}

$db->terminate();
include_once $_SERVER['DOCUMENT_ROOT'] . '/templates/partials/footer.html'; ?>
<script>
    document.title = "<?=$res['name'] ?> | Orders | FoodShala";
</script>