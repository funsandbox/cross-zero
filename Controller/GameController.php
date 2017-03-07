<?php

namespace Controller;

use Model\GameModel;
use Model\RatingModel;

class GameController extends BaseController
{
    protected $name = 'Game';
    public  $userName = '';

    public  function createroom() // создание новой комнаты
    {
        $GameModel = new GameModel();
        $GameModel->in_game($_SESSION['userId']);
        $oneRoomPlayer = $GameModel->oneRoomPlayer($_SESSION['user']);
        if($oneRoomPlayer[0]['count(*)'] == 0) { // проверка, что бы у игрока не было других открытых комнат
            $GameModel->createRoom($_POST['room'], $_SESSION['user']);
        }
        $this->render("room");
    }

    public function enterroom() // вход в открытую комнату второго игрока
    {
        $GameModel = new GameModel();
        $match = false;
        $playerInOpenRoom = $GameModel->playerInOpenRoom();
        if (!empty($_SESSION['user'])) {
            foreach ($playerInOpenRoom as $value) {
                if ($value['first_player'] == $_SESSION['user']) {
                    $match = true;
                }
            }
            if (!$match) {
                $GameModel->secondPlayer($_GET['room'], $_SESSION['user']);
                $GameModel->in_game($_SESSION['userId']);
            }
        }
        $this->render("room");
    }

    public function outputroom() //выход из комнаты
    {
        $GameModel = new GameModel();
        $GameModel->destroyRoom($_SESSION['user']); // уничтожение комнаты после выхода их неё одного из игроков
        $this->render("outputroom");
    }

    public function onclick() // обработчик клика по ячейке
{
    if (isset($_POST['id']))
    {
        $GameModel = new GameModel();
        $cell = 'cell_' . $_POST['id'];
        if (!empty($_SESSION['user']))
        {
            $this->userName = $_SESSION['user'];
        }
        $room = $GameModel->findRoom($this->userName); // поиск комнаты в которой сделали нажатие
        $GameModel->step($room[0]['room_name'], $cell, $this->userName); // занесение сделаного хода в БД
        $players = $GameModel->findPlayers($room[0]['room_name']); // запись ников обоих игроков
        $GameModel->passivePlayer($room[0]['room_name'], $this->userName); // определение какой игрок пропускает ход после клика
        $success = $GameModel->playersInGame($players[0]['first_player'], $players[0]['second_player']); // проверка не вышел ли кто то из комнаты во время игры
        $win = $GameModel->win($room[0]['room_name'], $players[0]['first_player'], $players[0]['second_player']); // проверка выиграл ли кто то после клика
        $pic = $GameModel->whoIsThisPlayer($this->userName, $room[0]['room_name']); // выбор картинки для установки в ячейку
        $answer = array('success' => $success, 'win' => $win, 'pic' => $pic); // формирование ответа с сервера
        if ($win != '')
        {
            $RatingModel = new RatingModel;
            $RatingModel->rating($win, $players); // внесение всех необходимых изменений в рейтинги обоих игроков
        }
        echo json_encode($answer); exit;
    }
}

    public function game() // постоянная связь с сервером раз в секунду
    {
        $GameModel = new GameModel();
        if (!empty($_SESSION['user']))
        {
            $this->userName = $_SESSION['user'];
        }
        $room = $GameModel->findRoom($this->userName);
        $allstep = $GameModel->actualField($room[0]['room_name']);
        $passive = $GameModel->whoIsPassive($room[0]['room_name']);
        $firstPlayerName = $GameModel->findFirstPlayer($room[0]['room_name']);
        $answer = array('allstep' => $allstep, 'active' => $passive[0]['passive'], 'firstPlayerName' => $firstPlayerName[0]['first_player'], 'yourName' => $this->userName);
        echo json_encode($answer); exit;
    }
}