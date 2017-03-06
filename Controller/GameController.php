<?php

namespace Controller;

use Model\GameModel;

class GameController extends BaseController
{
    protected $name = 'Game';

    public  function createroom()
    {
        $GameModel = new GameModel();
        $GameModel->in_game($_SESSION['userId']);
        $oneRoomPlayer = $GameModel->oneRoomPlayer($_SESSION['user']);
        if($oneRoomPlayer[0]['count(*)'] == 0) {
            $GameModel->createRoom($_POST['room'], $_SESSION['user']);
        }
        $this->render("room");
    }

    public function enterroom()
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

    public function outputroom()
    {
        $GameModel = new GameModel();
        $GameModel->destroyRoom($_SESSION['user']);
        $this->render("outputroom");
    }

    public function onclick()
{
    if (isset($_POST['id']))
    {
        $GameModel = new GameModel();
        $cell = 'cell_' . $_POST['id'];
        $room = $GameModel->findRoom($_SESSION['user']);
        $GameModel->step($room[0]['room_name'], $cell, $_SESSION['user']);
        $players = $GameModel->findPlayers($room[0]['room_name']);
        $GameModel->passivePlayer($room[0]['room_name'], $_SESSION['user']);
        $success = $GameModel->playersInGame($players[0]['first_player'], $players[0]['second_player']);
        $win = $GameModel->win($room[0]['room_name'], $players[0]['first_player'], $players[0]['second_player']);
        $pic = $GameModel->whoIsThisPlayer($_SESSION['user'], $room[0]['room_name']);
        $answer = array('success' => $success, 'win' => $win, 'pic' => $pic);
        if ($win != '')
        {

        }
        echo json_encode($answer); exit;
    }
}

    public function game()
    {
        $GameModel = new GameModel();
        $room = $GameModel->findRoom($_SESSION['user']);
        $allstep = $GameModel->actualField($room[0]['room_name']);
        $passive = $GameModel->whoIsPassive($room[0]['room_name']);
        $firstPlayerName = $GameModel->findFirstPlayer($room[0]['room_name']);
        $answer = array('allstep' => $allstep, 'active' => $passive[0]['passive'], 'firstPlayerName' => $firstPlayerName[0]['first_player'], 'yourName' => $_SESSION['user']);
        echo json_encode($answer); exit;
    }
}