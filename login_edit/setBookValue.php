<?php
include_once 'models/Book.php';

//Võtame vormilt info
$id = $_POST['id'];
$rate = $_POST['rate'];
$uemail = $_POST['uemail'];

$book = new Book();
$isRated = $book->findRatedBookByIdAndEmail($id, $uemail);
if(!$isRated) { // Pole veel hinnatud
    $book->addUserNewRating($id, $rate, $uemail);
} else {
    $book->updateUserRating($isRated->id, $rate);
    //echo $isRated->id.' '.$id; / Testimiseks
}