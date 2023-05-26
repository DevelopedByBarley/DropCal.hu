<?php 
    class Toast {

        public function setToastMessage($message) {
            $_SESSION["toastMessage"] = $message;
        }

        public function getToastMessageAndShow() {
            $isToastShow = isset($_SESSION["toastMessage"]) ? true : false;
            $message = $_SESSION["toastMessage"];

            if ($isToastShow) {
                echo "<script type=\"text/javascript\">showToast(\"$message\");</script>";
                unset($_SESSION["toastMessage"]);
            }
        }
    }
?>