<?php
    class LoginChecker
    {
        private function isLoggedIn()
        {
            if (!isset($_COOKIE[session_name()])) return false;
            if (session_id() == '') {
                session_start();
            }
            if (!isset($_SESSION["userId"])) return false;
            return true;
        }
    
    
        public function checkUserIsLoggedInOrRedirect()
        {
            if ($this->isLoggedIn()) {
                return;
            };
            header("Location: /");
            exit;
        }
    }
