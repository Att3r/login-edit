<?php
if (empty($_GET['selector']) || empty($_GET['validator'])) {
    echo 'Could not validate your request!';
} else {
    $selector = $_GET['selector'];
    $validator = $_GET['validator'];

    if (ctype_xdigit($selector) && ctype_xdigit($validator)) { ?>
        <?php
        include_once 'header.php';
        include_once './helpers/session_helper.php';
        ?>

        <div class="columns is-centered">
            <div class="column is-one-fifth mt-2">
                <div class="card">
                    <div class="card-content">
                        <div class="content">
                            <?php flash('newReset') ?>

                            <form method="post" action="./controllers/ResetPasswords.php">
                                <input type="hidden" name="type" value="reset" />
                                <input type="hidden" name="selector" value="<?php echo $selector ?>" />
                                <input type="hidden" name="validator" value="<?php echo $validator ?>" />
                                <label for="" class="label">Sisesta uus parool</label>
                                <input class="input" type="password" name="pwd" placeholder="Enter a new password...">
                                <label for="" class="label">Korda uut parooli</label>
                                <input class="input" type="password" name="pwd-repeat" placeholder="Repeat new password..."><br><br>
                                <button class="button is-warning" type="submit" name="submit">Muuda parool</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        include_once 'footer.php'
        ?>

<?php
    } else {
        echo 'Could not validate your request!';
    }
}
?>