<?php
    require_once 'app/models/User_Model.php';
    class EmailVerification extends UserModel{
        public function __construct()
        {
            parent::__construct();
        }


        public function verificationEmail($email)
    {

        session_start();
        $verificationCode = rand(1000, 9999);
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $emailBody = "";

        $_SESSION["emailVerificationCode"] = $verificationCode;

        if (!$user) {
            $emailBody .= "
            <h1>A te email hitelesítő kódód:</h1>
            <h3>$verificationCode</h3> 
        ";
        } else {
            $emailBody .= "
            <h1>Ezen az email címen már egyszer regisztráltál , vagy valaki megpróbált a te adataiddal regisztrálni!</h1>
            <p>Kérlek ezen a linken <a href=\"#\">link</a> próbálj meg belépni!</p> 
        ";
        }

        $this->mailer->send($email, $emailBody);
    }

    public function sendVerification($verificationCode)
    {
        session_start();
        $isVerified = $verificationCode === (int)$_SESSION["emailVerificationCode"];
        if (!$isVerified) {
            echo json_encode(
                ["state" => false]
            );
            return;
        };

        $_SESSION["isEmailVerified"] = true;

        echo json_encode(
            ["state" => true]
        );
    }


    }
