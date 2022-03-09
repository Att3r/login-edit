<?php
include_once 'header.php';
require_once 'helpers/session_helper.php';
include 'navbar.php';

?>
<?php flash('register') ?>
<div class="columns is-centered">
    <div class="column is-one-fifth mt-2">
        <div class="card">
            <header class="card-header">
                <p class="card-header-title">
                    Registreerimis vorm

                </p>
            </header>    
            <div class="card-content">
                <div class="content">
                    <form method="post" action="./controllers/Users.php">
                        <input type="hidden" name="type" value="register">
                        <label for="" class="label">Nimi</label>
                        <input class="input" type="text" name="usersName" placeholder="Full name...">
                        <label for="" class="label">E-mail</label>
                        <input class="input" type="text" name="usersEmail" placeholder="Email...">
                        <label for="" class="label">Kasutajanimi</label>
                        <input class="input" type="text" name="usersUid" placeholder="Username...">
                        <label for="" class="label">Parool</label>
                        <input class="input" type="password" name="usersPwd" placeholder="Password...">
                        <label for="" class="label">Korda parooli</label>
                        <input class="input" type="password" name="pwdRepeat" placeholder="Repeat password"><br><br>
                        <button class="button is-primary" type="submit" name="submit">Registreeri kasutaja</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include_once 'footer.php'
?>