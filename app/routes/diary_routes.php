<?php
        require 'app/controllers/Diary_Controller.php';
         $r->addRoute('POST', '/diary/change_date', [DiaryController::class, 'changeDiaryDate']);

?>