<?php
class ResetPw
{
    private $renderer;
    private $pdo;
    private $mailer;

    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnect();
        $this->renderer = new Renderer();
        $this->mailer = new Mailer();
    }

    public function forgotPwForm()
    {

        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/user/reset_pw/Forgot_Pw_Form.php", []),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0
        ]);
    }

    public function newPwRequest()
    {

        $this->pwRequest($_POST);
    }

    public function resetPwForm()
    {
        $token = $_GET["token"] ?? null;
        $expires = $_GET["expires"] ?? null;
        $emailByToken = $this->checkTokenData($token, $expires);
        if (!$emailByToken) {
            echo "Token nem lejárt vagy nem létezik!";
            return;
        }


        echo $this->renderer->render("Layout.php", [
            "content" => $this->renderer->render("/pages/public/user/reset_pw/Reset_Pw_Form.php", [
                "emailByToken" => $emailByToken,
                "token" => $token
            ]),
            "currentStepId" =>  $_COOKIE["currentStepId"] ?? 0,

        ]);
    }

    public function setNewPw()
    {
        $this->newPw($_POST);
    }





    private function pwRequest($body)
    {
        $email = $body["email"];
        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $token = uniqid();
        $current_time = time();
        $expires = date('Y-m-d H:i:s', strtotime('+10 minutes', $current_time));


        $stmt = $this->pdo->prepare("INSERT INTO 
        `password_reset_tokens` 
        (`tokenId`, 
        `email`, 
        `token`, 
        `expires`, 
        `isUsed`, 
        `createdAt`)
        VALUES 
        (NULL, 
        :email, 
        :token, 
        :expires, 
        '0',
        current_timestamp());");



        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires', $expires);

        $stmt->execute();
        $body = $_SERVER["TOKEN_ADD"] . "/reset_pw?token=" . $token . "&expires=" . strtotime($expires);
        $subject = "Jelszó megváltoztatása!";
        !$user ? "" : $this->mailer->send($email, $body, $subject);

        header("Location: /user/login?isEmailSent=1");
    }


    private function checkTokenData($token)
    {


        $stmt = $this->pdo->prepare("SELECT * FROM `password_reset_tokens` WHERE token = :token AND isUsed = 0");

        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $token = $stmt->fetch(PDO::FETCH_ASSOC);


        if (!$token) {
            return false;
        }

        $currentDate = time();
        $expires = strtotime($token["expires"]);

        if ($currentDate > $expires) {

            return false;
        }

        return $token["email"];
    }

    private function newPw($body)
    {
        $password = $body["password"];
        $password_again = $body["password_again"];
        $email = $body["email"];
        $token = $body["token"];

        if ($password !== $password_again) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&pwVerifyProblem=1");
            exit;
        }

        $hashedPw = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE `users` SET `password` = :hashedPw WHERE `users`.`email` = :email;");

        $stmt->bindParam(':hashedPw', $hashedPw);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $stmt = $this->pdo->prepare("UPDATE `password_reset_tokens` SET `isUsed` = '1' WHERE `password_reset_tokens`.`token` = :token;");

        $stmt->bindParam(':token', $token);
        $stmt->execute();

        header("Location: /user/login?isPwUpdated=1");
    }
}
