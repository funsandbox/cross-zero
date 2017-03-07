<?php

namespace Model;

class GameModel extends BaseModel
{
    protected $table = 'games';

    public function in_game($id) // отметка, что пользователя начал игру
    {
        $query = "UPDATE users SET `in_game` = 1 WHERE id = {$id}";
        return $this->db->execute($query);
    }
    public function outputGame($login) // отметка, что пользователь закончил игры
    {
        $query = "UPDATE users SET `in_game` = 0 WHERE login = '{$login}'";
        return $this->db->execute($query);
    }
    public function createRoom($room, $firstPlayer) // создание комнаты
    {
        $room = $this->db->escape($room);
        $query = "INSERT INTO games SET `room_name` = '{$room}', `first_player` = '{$firstPlayer}'";
        return $this->db->execute($query);
    }

    public  function oneRoomPlayer($login) // проверка сколько игроков в комнаты
    {
        $result = $this->db->query('SELECT count(*) FROM ' . $this->table . " WHERE first_player = '{$login}'");
        return $result;
    }

    public  function openRoom() // список всех открытых комнат
    {
        $result = $this->db->query('SELECT room_name FROM ' . $this->table . " WHERE second_player = 0");
        return $result;
    }

    public  function playerInOpenRoom() // ник игрока в открытой комнате
    {
        $result = $this->db->query('SELECT first_player FROM ' . $this->table . " WHERE second_player = 0");
        return $result;
    }

    public function secondPlayer($room_name, $login) // запись второго игрока в комнату
    {
        $this->passivePlayer($room_name, $login);
        $query = 'UPDATE ' . $this->table . " SET `second_player` = '{$login}' WHERE `room_name` = '{$room_name}'";
        return $this->db->execute($query);
    }

    public function destroyRoom ($first_player) // уничтожение комнаты
    {
        $room = $this->findRoom($first_player);

        $secondPlayer = $this->findSecondPlayer($room[0]['room_name']);
        if ($first_player != $secondPlayer)
        {
            $this->outputGame($secondPlayer);
        } elseif ($first_player == $secondPlayer)
        {
            $secondPlayer = $this->findFirstPlayer($room[0]['room_name']);
            $this->outputGame($secondPlayer);
        }
        $this->outputGame($first_player);
        $query ='DELETE FROM ' . $this->table . " WHERE first_player = '{$first_player}'";
        return $this->db->execute($query);
    }

    public function step ($room, $cell, $player) // отметка какой ход был сделан
    {
        $query = 'UPDATE ' . $this->table . " SET `{$cell}` = '{$player}' WHERE `room_name` = '{$room}'";
        return $this->db->execute($query);
    }

    public function allstap ($room) // сбор всех ходов на данный момент
    {
        $result = $this->db->query('SELECT cell_1, cell_2, cell_3, cell_4, cell_5, cell_6, cell_7, cell_8, cell_9 FROM ' . $this->table . " WHERE `room_name` = '{$room}'");
        return $result;
    }

    public function win ($room, $firstPlayer, $secondPlayer) // проверка не завершена ли игра
    {
        $win = '';
        $first = $second = array();
        $allstep = $this->allstap($room);
        foreach ($allstep[0] as $key => $value)
        {
            if ($value == $firstPlayer)
            {
                $first[] = $key;
            }
            if ($value == $secondPlayer)
            {
                $second[] = $key;
            }
        }
        $win1 = array('cell_1', 'cell_2', 'cell_3');
        $win2 = array('cell_4', 'cell_5', 'cell_6');
        $win3 = array('cell_7', 'cell_8', 'cell_9');
        $win4 = array('cell_1', 'cell_4', 'cell_7');
        $win5 = array('cell_2', 'cell_5', 'cell_8');
        $win6 = array('cell_3', 'cell_6', 'cell_9');
        $win7 = array('cell_1', 'cell_5', 'cell_9');
        $win8 = array('cell_3', 'cell_5', 'cell_7');
        $arrayWin = array($win1, $win2, $win3, $win4, $win5, $win6, $win7, $win8);
        foreach ($arrayWin as $value)
        {
            if ($value == array_intersect($value, $first))
            {
                $win = $firstPlayer;
            }
            if ($value == array_intersect($value, $second))
            {
                $win = $secondPlayer;
            }
        }
        if (count($allstep) == 9 && $win == '')
        {
            $win = 'Ничья';
        }
        return $win;
    }

    public function findRoom ($player) // поиск названия комнаты по нику игрока
    {
        $result = $this->db->query('SELECT room_name FROM ' . $this->table . " WHERE first_player = '{$player}' OR second_player = '{$player}'");
        return $result;
    }

    public function findPlayers($room) // поиск игроков по названию комнаты
    {
        $result = $this->db->query('SELECT first_player, second_player FROM ' . $this->table . " WHERE room_name = '{$room}'");
        return $result;
    }

    public function findFirstPlayer($room) // нахождение первого игрока в комнате
    {
        $result = $this->db->query('SELECT first_player FROM ' . $this->table . " WHERE room_name = '{$room}'");
        return $result;
    }
    public function findSecondPlayer($room) // нахождение второго игрока в комнате
    {
        $result = $this->db->query('SELECT second_player FROM ' . $this->table . " WHERE room_name = '{$room}'");
        return $result;
    }

    public function passivePlayer($room, $player) // запись чей ход
    {
        $query = "UPDATE " . $this->table . " SET `passive` = '{$player}' WHERE room_name = '{$room}'";
        return $this->db->execute($query);
    }

    public function whoIsPassive($room) // нахождение чей ход
    {
        $result = $this->db->query("SELECT passive FROM " . $this->table . " WHERE room_name = '{$room}'");
        return $result;
    }

    public function playersInGame($firstPlayer, $secondPlayers) // проверка пользователей в игре
    {
        $first = $this->db->query("SELECT in_game FROM users WHERE login = '{$firstPlayer}'");
        $second = $this->db->query("SELECT in_game FROM users WHERE login = '{$secondPlayers}'");
        if ($first[0]['in_game'] && $second[0]['in_game'])
        {
            $result = true;
        } else
        {
            $result = false;
        }
        return $result;
    }
    public function actualField($room) // проверка текущей растановки на поле
    {
        $steps = $this->db->query('SELECT cell_1, cell_2, cell_3, cell_4, cell_5, cell_6, cell_7, cell_8, cell_9 FROM ' . $this->table . " WHERE `room_name` = '{$room}'");
        $result = array(1);
        foreach ($steps[0] as $key => $value)
        {
            $result[] = $value;
        }
        unset($result[0]);
        return $result;
    }

    public function whoIsThisPlayer($login, $room) // отправка необходимой картинки для игрового поля
    {
        $firstPlayer = $this->findFirstPlayer($room);
        if ($firstPlayer[0]['first_player'] == $login)
        {
            $result = 'http:'. DS . DS . 'allaboutwindowsphone.com' . DS .'images' . DS . 'appicons' .DS .'212662.png';
        } else
        {
            $result = 'http:'. DS . DS . 'magazintaobao.com' . DS .'wp-content' . DS .'uploads' . DS .'2015' . DS .'04' . DS .'red-number-01.jpg';
        }
        return $result;
    }
}