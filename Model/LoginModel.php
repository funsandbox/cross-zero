<?php

namespace Model;

class LoginModel extends BaseModel
{
    protected $table = 'users';

    public function getUser($login,$email)
    {
        $result = $this->db->query('select `id` from ' . $this->table . " where login='{$login}' OR email = '{$email}'");
        return $result;
    }

    public function getUsers($login,$password)
    {
        $login= $this->db->escape($login);
        $password= $this->db->escape($password);
        $result = $this->db->query('select `id` from ' . $this->table . " where login='{$login}' AND password='{$password}'");
        return $result;
    }

    public function save($data)
    {
        $data['login'] = $this->db->escape($data['login']);
        $data['password'] = $this->db->escape($data['password']);
        $data['email'] = $this->db->escape($data['email']);
        $query = "INSERT INTO users SET `login` = '{$data['login']}', `password` = '{$data['password']}', `email` = '{$data['email']}'";
        return $this->db->execute($query);
    }
    public function online($id)
    {
        $query = "UPDATE " . $this->table . " SET `online` = 1 WHERE id = {$id}";
        return $this->db->execute($query);
    }

    public function offline($id)
    {
        $query = "UPDATE " . $this->table . " SET `online` = 0 WHERE id = {$id}";
        return $this->db->execute($query);
    }
}