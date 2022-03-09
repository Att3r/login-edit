<?php
include_once 'header.php';
include_once './helpers/session_helper.php';
include 'navbar.php';

?>

<div class="columns is-centered">
    <div class="column is-one-fifth mt-2">


        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Taasta parool
                </p>
            </header>
            <div class="card-content">

                <div class="content">
                    <form method="post" action="./controllers/ResetPasswords.php">
                        <input type="hidden" name="type" value="send" />
                        <label for="" class="label">Sisesta E-mail</label>
                        <input class="input" type="text" name="usersEmail" placeholder="E-mail..."><br><br>
                        <button class="button is-danger" type="submit" name="submit">Saada kinnitus e-mailile</button>
                    </form>
                </div>
            </div>
            <?php flash('reset') ?>
        </div>
    </div>
</div>
<?php
include_once 'footer.php'
?>