<?php

namespace Model;

class RatingModel extends BaseModel
{
    protected $table = 'user_rating';

    public function rating($win, $players)
    {
        $firstPlayer = $players[0]['first_player'];
        $secondPlayers = $players[0]['second_player'];
        $transitoryFirst = $this->findId($firstPlayer);
        $firstPlayerId = $transitoryFirst[0]['id'];
        $transitorySecond = $this->findId($secondPlayers);
        $secondPlayersId = $transitorySecond[0]['id'];
        if ($win == 'nobody')
        {
            $this->addAllGame($firstPlayerId);
            $this->addAllGame($secondPlayersId);
            $this->nobody($firstPlayerId, $secondPlayersId);
        }
        if ($win == $firstPlayer)
        {
            $this->addAllGame($firstPlayerId);
            $this->addAllGame($secondPlayersId);
            $this->addRating($firstPlayerId);
            $this->cutRating($secondPlayersId);
            $this->addWin($firstPlayerId);
            $this->addLose($secondPlayersId);
        }
        if ($win == $secondPlayers)
        {
            $this->addAllGame($firstPlayerId);
            $this->addAllGame($secondPlayersId);
            $this->addRating($secondPlayersId);
            $this->cutRating($firstPlayerId);
            $this->addWin($secondPlayersId);
            $this->addLose($firstPlayerId);
        }
    }

    public function addRating($playerId)
    {
        $query = "UPDATE " . $this->table . " SET `rating` = `rating` + '1' WHERE id = '{$playerId}'";
        return $this->db->execute($query);
    }

    public function cutRating($playerId)
    {
        $query = "UPDATE " . $this->table . " SET `rating` = `rating` - '1' WHERE id = '{$playerId}'";
        return $this->db->execute($query);
    }

    public function findId($player)
    {
        $result = $this->db->query("SELECT id FROM users WHERE login = '{$player}'");
        return $result;
    }

    public function nobody($firstPlayerId, $secondPlayersId)
    {
        $query = "UPDATE " . $this->table . " SET `nobody` = `nobody` + '1' WHERE id = '{$firstPlayerId}' AND id = '{$secondPlayersId}'";
        return $this->db->execute($query);
    }

    public function addAllGame($playerId)
    {
        $query = "UPDATE " . $this->table . " SET `all_games` = `all_games` + '1' WHERE id = '{$playerId}'";
        return $this->db->execute($query);
    }

    public function addWin($playerId)
    {
        $query = "UPDATE " . $this->table . " SET `win` = `win` + '1' WHERE id = '{$playerId}'";
        return $this->db->execute($query);
    }

    public function addLose($playerId)
    {
        $query = "UPDATE " . $this->table . " SET `lose` = `lose` + '1' WHERE id = '{$playerId}'";
        return $this->db->execute($query);
    }
}