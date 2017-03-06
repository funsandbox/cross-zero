<?php

namespace Model;

class LoginModel extends BaseModel
{
    protected $table = 'user_rating';

    public function addRating($id)
    {
        $actualRating = $this->db->query('SELECT `rating` FROM ' . $this->table . " WHERE id='{$id}'");
        $actualRating += 1;
        $query = 'UPDATE' . $this->table . " SET rating = '{$actualRating}' WHERE id = '{$id}'";
        return $this->db->execute($query);
    }

    public function cutRating($id)
    {
        $actualRating = $this->db->query('SELECT `rating` FROM ' . $this->table . " WHERE id='{$id}'");
        $actualRating -= 1;
        $query = 'UPDATE' . $this->table . " SET rating = '{$actualRating}' WHERE id = '{$id}'";
        return $this->db->execute($query);
    }
}