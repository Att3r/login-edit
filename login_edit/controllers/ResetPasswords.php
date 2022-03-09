<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once '../models/ResetPassword.php';
require_once '../helpers/session_helper.php';
require_once '../models/User.php';
//Require PHP Mailer
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/SMTP.php';

class ResetPasswords
{
    private $resetModel;
    private $userModel;
    private $mail;

    public function __construct()
    {
        $this->resetModel = new ResetPassword;
        $this->userModel = new User;
        //Setup PHPMailer
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gamil.com';
        $this->mail->Port = 587;
        $this->mail->SMPTSecure = 'tls';
        $this->mail->SMTPAuth = true;
        
        
        $this->mail->Username = 'atter2207@gmail.com';
        $this->mail->Password = 'yfmbzvjnibzdkioy';

        $this->mail->setFrom('atter2207@gmail.com', 'Minult'); # Kellelt (arendajalt/minult)
        $this->mail->addAddress($_POST['usersEmail']); # Kellele kiri saadetakse
        
    
    }

    public function sendEmail()
    {
        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $usersEmail = trim($_POST['usersEmail']);

        if (empty($usersEmail)) {
            flash("reset", "palun sisesta email");
            redirect("../reset-password.php");
        }

        if (!filter_var($usersEmail, FILTER_VALIDATE_EMAIL)) {
            flash("reset", "Vigane email");
            redirect("../reset-password.php");
        }
        //Will be used to query the user from the database
        $selector = bin2hex(random_bytes(8));
        //Will be used for confirmation once the database entry has been matched
        $token = random_bytes(32);
        //URL will vary depending on where the website is being hosted from
        $url = 'http://atter.kehtnakhk.ee/login_edit/create-new-password.php?selector='.$selector.'&
        validator='.bin2hex($token);

        //Expiration date will last for half an hour
        $expires = date("U") + 1800;
        if (!$this->resetModel->deleteEmail($usersEmail)) {
            die("There was an error");
        }
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        if (!$this->resetModel->insertToken($usersEmail, $selector, $hashedToken, $expires)) {
            die("There was an error");
        }
        //Can Send Email Now
        $subject = "Reset your password";
        $message = "<p>We recieved a password reset request.</p>";
        $message .= "<p>Here is your password reset link: </p>";
        $message .= "<a href='" . $url . "'>" . $url . "</a>";

        $this->mail->setFrom('TheBoss@gmail.com');
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        $this->mail->addAddress($usersEmail);

        $this->mail->send();

        flash("reset", "Vaata oma emaili", 'form-message form-message-green');
        redirect("../reset-password.php");
    }

    public function resetPassword()
    {
        //Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
            'selector' => trim($_POST['selector']),
            'validator' => trim($_POST['validator']),
            'pwd' => trim($_POST['pwd']),
            'pwd-repeat' => trim($_POST['pwd-repeat'])
        ];
        $url = '../create-new-password.php?selector=' . $data['selector'] . '&validator=' . $data['validator'];

        if (empty($_POST['pwd'] || $_POST['pwd-repeat'])) {
            flash("newReset", "Palun täida kõik väljad");
            redirect($url);
        } else if ($data['pwd'] != $data['pwd-repeat']) {
            flash("newReset", "Paroolid ei ühti");
            redirect($url);
        } else if (strlen($data['pwd']) < 6) {
            flash("newReset", "Vale parool");
            redirect($url);
        }

        $currentDate = date("U");
        if (!$row = $this->resetModel->resetPassword($data['selector'], $currentDate)) {
            flash("newReset", "Vabandust, see link ei tööta.");
            redirect($url);
        }

        $tokenBin = hex2bin($data['validator']);
        $tokenCheck = password_verify($tokenBin, $row->pwdResetToken);
        if (!$tokenCheck) {
            flash("newReset", "Palun kinnita uuesti taastamise kinnitus");
            redirect($url);
        }

        $tokenEmail = $row->pwdResetEmail;
        if (!$this->userModel->findUserByEmailOrUsername($tokenEmail, $tokenEmail)) {
            flash("newReset", "Tekkis viga");
            redirect($url);
        }

        $newPwdHash = password_hash($data['pwd'], PASSWORD_DEFAULT);
        if (!$this->userModel->resetPassword($newPwdHash, $tokenEmail)) {
            flash("newReset", "Tekkis viga");
            redirect($url);
        }

        if (!$this->resetModel->deleteEmail($tokenEmail)) {
            flash("newReset", "Tekkis viga");
            redirect($url);
        }

        flash("newReset", "Parool uuendatud", 'form-message form-message-green');
        redirect($url);
    }
}

$init = new ResetPasswords;

//Ensure that user is sending a post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($_POST['type']) {
        case 'send':
            $init->sendEmail();
            break;
        case 'reset':
            $init->resetPassword();
            break;
        default:
            header("location: ../index.php");
    }
} else {
    header("location: ../index.php");
}
