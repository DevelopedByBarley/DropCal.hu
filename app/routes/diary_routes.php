<?php
    require 'app/controllers/Diary_Controller.php';

    $r->addRoute('GET', '/diary/currentDiary', [DiaryController::class, 'getDiaryByDate']);

?>