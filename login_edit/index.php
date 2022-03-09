<?php
include_once 'header.php';
include 'navbar.php';
?>

<div class="columns is-centered">
    <div class="column is-one-fifth mt-2">
        <h1 id="index-text">Tere,
            <?php
            if (isset($_SESSION['usersId'])) {
                echo explode(" ", $_SESSION['usersName'])[0];
            } else {
                echo 'külaline';
            }
            ?>
        </h1>
    </div>
</div>

<?php
include_once 'footer.php'
?>