<?php
include_once 'header.php';
include_once 'navbar.php';
include_once 'helpers/session_helper.php';
include_once 'models/ChildMouth.php';
require_once 'models/Settings.php';

$child = new Child();
$childmouths = $child->getChild();
//show($childmouths);
?>
<div class="columns m-1">
    <div class="column">
        <h1 class="is-size-2 has-text-centered mb-2">Lapsesuu ei valeta</h1>
        <?php if ($childmouths) { # Kas DB saadi midagi
        ?>
            <table class="table is-fullwidth is-hoverable is-bordered">
                <thead class="has-text-centered">
                    <tr>
                        <th>Jrk</th>
                        <th>Tekst</th>
                        <th>Hinnang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $settings = new Settings();
                    $enabled = $settings->getAllowChangeRating();
                    $readonly = true; //Ei saa muuta
                    $userEmail = ''; // Pole sisse loginud
                    $setting = $enabled->allow;
                    $childs_ids = array();
                    if (isset($_SESSION['usersEmail'])) {
                        if ($enabled->allow) {
                            $readonly = false;
                        }
                        $userEmail = $_SESSION['usersEmail'];
                        $childs_ids = $child->getRatedUserChildIds($userEmail);
                        //show($childs_ids);
                    }
                    foreach ($childmouths as $key => $val) {
                    ?>
                        <tr>
                            <td class="has-text-right"><?php echo $val->id ?></td>
                            <td class=""><?php echo $val->child_text; ?></td>
                            <td class="has-text-centered">
                                <div class="my-rating" id="<?php echo $val->id; ?>"></div>
                                <?php
                                $found = false;
                                if (count($childs_ids) > 0) {
                                    foreach ($childs_ids as $k => $v) {
                                        if ($val->id == $v->mouth_id) {
                                            $found = true;
                                        }
                                    }
                                }
                                if (empty($userEmail)) { // Pole sisse loginud
                                    $readonly = true; // Ei saa muuta
                                } else if (!$found) { // Raamatut ei leitud nimekirjast
                                    $readonly = false; // Saab muuta
                                } else if ($found && !$setting) { // Leiti ja seadistus ei luba muuta
                                    $readonly = true; // Ei saa muuta
                                }
                                ?>
                                <script>
                                    readonly = <?php echo json_encode($readonly); ?>;
                                    useremail = <?php echo json_encode($userEmail); ?>;
                                    $(".my-rating").starRating({
                                        starSize: 25,
                                        initialRating: <?php echo $val->rating; ?>,
                                        readOnly: readonly,
                                        callback: function(currentRating, $el) {
                                            let rate = currentRating; // Mitu  * valiti?
                                            let id = $el[0].id; // my-rating id=
                                            console.log(rate, id, useremail); // TEST
                                            $.ajax({
                                                type: 'POST',
                                                url: 'setMouthValue.php',
                                                data: {
                                                    rate: rate,
                                                    id: id,
                                                    uemail: useremail
                                                },
                                                success: function(data) {
                                                    console.log('Rating updated');
                                                    location.reload(true);
                                                },
                                                error: function(data) {
                                                    console.log('Rating error');
                                                }
                                            });
                                        }
                                    });
                                </script>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php
        }
        ?>
        <h4 class="is-size-6 has-text-centered has-text-danger">Kirjed läbi</h4>
        <?php

        ?>
    </div>
</div>

<?php
include 'footer.php';
?>