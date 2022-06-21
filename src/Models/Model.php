<?php

namespace Models;

use PDO;

class Model
{
    private string $login;
    private string $password;
    private string $salt = '4p#YkEwrA9xt~]hm-u}+7?3,FdLK№@gXYkEwrA9xt}+7?3,Fd';
    private string $saltL = 'ЭтоСольДляЛогина';
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=localhost; dbname=Auth', 'root', 'password');
    }

    public function getUserInfo($login)
    {
        $sql = "select * from users where login='$login';";
        $result = $this->pdo->prepare($sql);
        $result->execute();
        return $result;
    }

    public function getAllUsers()
    {
        $sql = "select * from users;";
        $result = $this->pdo->prepare($sql);
        $result->execute();
        return $result;
    }

    public function searchByLoginPassword()
    {
        $stmt = $this->pdo->prepare("SELECT * from users where login='$this->login' AND password='$this->password';");
        $stmt->execute();
        $result = $stmt->fetchAll();
        if (count($result) != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function setLP(string $login, string $password)
    {
        $this->login = $login;
        $this->password = md5($password . $this->salt);
    }

    public function getLoginHash($login)
    {
        return(md5($login.$this->saltL));
    }

    public function newUser()
    {
        if (count($this->getUserInfo($this->login)->fetchAll()) != 0) {
            return false;
        } else {
            $stmt = $this->pdo->prepare("insert into users values ('$this->login', '$this->password');");
            $stmt->execute();
            return true;
        }
    }

    public function getLogin()
    {
        return $this->login;
    }

}