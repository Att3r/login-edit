<?php
include_once 'models/Settings.php';
$settings = new Settings();
$allow = $_POST['allow'];
$id = $_POST['id'];
if($settings->setAllowValue($id, $allow)) {
    echo 'Ok';
} else {
    echo 'error';
}
?>