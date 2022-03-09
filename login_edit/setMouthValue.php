<?php
include_once 'models/ChildMouth.php';

//Võtame vormilt info
$id = $_POST['id'];
$rate = $_POST['rate'];
$uemail = $_POST['uemail'];

$child = new Child();
$isRated = $child->findRatedMouthByIdAndEmail($id, $uemail);
if(!$isRated) { // Pole veel hinnatud
    $child->addUserNewRating($id, $rate, $uemail);
} else {
    $child->updateUserRating($isRated->id, $rate);
    //echo $isRated->id.' '.$id; / Testimiseks
}