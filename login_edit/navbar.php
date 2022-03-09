<?php
require_once 'models/Settings.php';
$settings = new Settings();
$enabled = $settings->getAllowChangeRating();
//print_r($enabled);
?>
<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">
            <a href="index.php" class="navbar-item">
                Avaleht
            </a>
            <a href="books.php" class="navbar-item is-warning">
                Raamatud
            </a>
            <a href="child_mouth.php" class="navbar-item is-warning">
                Lapsesuu
            </a>
        </div>

        <div class="navbar-end">
            <div class="navbar-item">
                <?php if (!isset($_SESSION['usersId'])) : ?>
                    <a href="login.php" class="navbar-item button is-primary">
                        Logi sisse
                    </a>
                    <a href="signup.php" class="navbar-item button is-light">
                        <strong>Registreeri kasutaja</strong>
                    </a>

                <?php else : ?>
                    <label for="" class="checkbox">
                        <input type="checkbox" name="allow" id="ip_setting" value="<?php echo $enabled->allow; ?>" <?php if($enabled->allow) {echo 'checked'; } ?>>
                        Luba muuta hinnangut
                    </label>
                    <a href="./controllers/Users.php?q=logout" class="navbar-item button is-danger">
                        <strong>Logi välja</strong>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        // https://bulma.io/documentation/components/navbar/
        // Check for click events on the navbar burger icon
        $(".navbar-burger").click(function() {

            // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
            $(".navbar-burger").toggleClass("is-active");
            $(".navbar-menu").toggleClass("is-active");
        });
        $("#ip_setting").change(function() {
            let allow = "<?php echo $enabled->allow; ?>"; // Tekstina 
            //console.log(allow); // Kontrolliks konsooli
            if(allow == 1) {
                allow = 0; // Numbrina
            } else {
                allow = 1; // Numbrina
            };
            $.ajax({
                type: 'POST',
                url: 'setAllowValue.php',
                data: {
                    allow: allow, 
                    id: 1
                },
                success: function(data) {
                    //console.log(data);
                    console.log('Settings updated');
                    location.reload(true);
                }, 
                error: function(data) {
                    //console.log(data);
                    console.log('Settings error');
                }
            });
        });
    });
</script>