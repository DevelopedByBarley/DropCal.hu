<?php 
    require_once 'app/models/User_Model.php';
    
    class Authentication extends UserModel{

        public function __construct()
        {
            parent::__construct();
        }
        

        public function authentication($body)
    {
        session_start();
        $email = filter_var($body["email"] ?? '', FILTER_SANITIZE_EMAIL);
        $password = filter_var($body["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $verificationCode = rand(1000, 9999);
        $emailBody = "     
        <h1>A te Belépés hitelesítő kódód:</h1>
            <h3>$verificationCode
            </h3> ";

        $stmt = $this->pdo->prepare("SELECT * FROM `users` WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            header("Location: /user/login");
            return;
        }
        $hash = $user["password"];

        $isVerified = password_verify($password, $hash);

        if (!$isVerified) {
            header("Location: /user/login");
            return;
        }
        $_SESSION["verificationCode"] = $verificationCode;
        if ($body["remember_me"] === "on") {
            $_SESSION["isRemember"] = true;
        }

        $this->mailer->send($email, $emailBody);

        header("Location: /user/login/authentication/" . $user["userId"]);
    }
    }
