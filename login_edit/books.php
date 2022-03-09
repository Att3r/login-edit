<?php
include_once 'header.php';
include_once 'navbar.php';
include_once 'helpers/session_helper.php';
include_once 'models/Book.php';
require_once 'models/Settings.php';

$book = new Book(); # Raamatu objekt
$books = $book->getBooks(); # Raamatud objektina
//show($books);
?>
<div class="columns m-1">
    <div class="column">
        <h1 class="is-size-2 has-text-centered mb-2">TOP 100 Rahva Raamatut 2021 aastal<sup title="Link lehe lõpus">*</sup></h1>
        <?php if ($books) {
        ?>
            <table class="table is-fullwidth is-hoverable is-bordered">
                <thead class="has-text-centered">
                    <tr>
                        <th>Jrk</th>
                        <th>Raamatu nimi</th>
                        <th>Raamatu autor</th>
                        <th>Hinnang</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $settings = new Settings();
                    $enabled = $settings->getAllowChangeRating();
                    $readonly = true; // True, ehk ei saa muuta
                    $userEmail = ''; // Kasutaja pole sisse loginud
                    $setting = $enabled->allow; // $setting on kas 1 või 0
                    $book_ids = array();
                    // Kontroll kas kasutaja on sisse loginud
                    if (isset($_SESSION['usersEmail'])) {
                        if ($enabled->allow) {
                            $readonly = false; // false ehk 0 ehk SAAB muuta
                        }
                        $userEmail = $_SESSION['usersEmail'];
                        $book_ids = $book->getRatedUserBooksIds($userEmail);
                        //show($book_ids);
                    }
                    foreach ($books as $key => $val) {
                    ?>
                        <tr>
                            <td class="has-text-right"><?php echo $val->id; ?></td>
                            <td><?php echo $val->book_name; ?></td>
                            <td><?php echo $val->book_author; ?></td>
                            <td class="has-text-centered">
                                <div class="my-rating" id="<?php echo $val->id; ?>"></div>
                                <?php
                                $found = false; //Kirjet ei ole
                                if (is_array($book_ids) && count($book_ids) > 0) {
                                    foreach ($book_ids as $k => $v) {
                                        if($val->id == $v->book_id) {
                                            $found = true;
                                        }
                                    }
                                }
                                if(empty($userEmail)) { // Pole sisse loginud
                                    $readonly = true; // Ei saa muuta
                                } else if(!$found) { // Raamatut ei leitud nimekirjast
                                    $readonly = false; // Saab muuta
                                } else if($found && !$setting) { // Leiti ja seadistus ei luba muuta
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
                                                url: 'setBookValue.php',
                                                data: {
                                                    id: id,
                                                    rate: rate,
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
        * - <a href="https://raamatud.postimees.ee/7419531/selgusid-rahva-raamatu-2021-aasta-populaarseimad-raamatud" target="_blank">https://raamatud.postimees.ee/7419531/selgusid-rahva-raamatu-2021-aasta-populaarseimad-raamatud</a>
    </div>
</div>

<?php
include_once 'footer.php';
?>