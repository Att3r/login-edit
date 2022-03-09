<?php
include_once 'header.php';
include_once './helpers/session_helper.php';
include 'navbar.php';

?>

<div class="columns is-centered">
    <div class="column is-one-fifth mt-2">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <form method="post" action="./controllers/Users.php">
                        <input type="hidden" name="type" value="login">
                        <label class="label" for="name/email">Kautajanimi</label>
                        <input class="input" type="text" name="name/email" placeholder="Username/Email...">
                        <label class="label" for="usersPwd">Parool</label>
                        <input class="input" type="password" name="usersPwd" placeholder="Password..."><br><br>
                        <button class="button is-primary" type="submit" name="submit">Logi sisse</button>
                    </form>

                    <div class="form-sub-msg"><a href="./reset-password.php">Unustasid parooli?</a></div>
                </div>
                <?php flash('login') ?>
            </div>
        </div>
    </div>
</div>
<?php
include_once 'footer.php'
?>