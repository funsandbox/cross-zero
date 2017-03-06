<?php

namespace Model;

class UserModel extends BaseModel
{
    protected $table = 'users';

    public function getTopUserRating()
    {
        $query = "SELECT u.* FROM " . $this->table . " AS u LEFT JOIN user_rating AS ur ON (u.id=ur.id) ORDER BY rating LIMIT 5";
        return $this->db->execute($query);
    }

    public  function whoOnline()
    {
        $result = $this->db->query('SELECT id, login FROM ' . $this->table . " WHERE online=1");
        return $result;
    }

    public function profile($id)
    {
        $result = $this->db->query("SELECT ur.* FROM " . $this->table . " AS u LEFT JOIN user_rating AS ur ON (u.id = ur.id) WHERE u.id = {$id};");
        return $result;
    }

    public function inGame($data)
    {
        if (!empty($data))
        {
            $result = $this->db->query('SELECT in_game FROM ' . $this->table . " WHERE id={$data}");
            return $result;
        }
    }
}