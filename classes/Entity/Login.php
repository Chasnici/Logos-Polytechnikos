<?php

namespace Entity;

class Login {

    protected $dbal;

    private $id;

    private $data;

    private static $instances = array();

    private function __construct($dbal, $id) {
        $this->dbal = $dbal;
        $this->id = $id;
        $this->loadData();
    }

    public static function load($dbal, $id) {
        try {
            if(isset(self::$instances[$id])) {
                return self::$instances[$id];
            } else {
                self::$instances[$id] = new Login($dbal, $id);
                return self::$instances[$id];
            }
        } catch (\Exception\NotFoundException $unfe) {
            return null;
        }
    }

    private function loadData() {
        $qb = $this->dbal->createQueryBuilder();
        $qb->select("id, login, password, role");
        $qb->from("user", "u");
        $qb->where("id = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
    }


    public function Login($dbal, $login, $password) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("id, login, password, role");
        $qb->from("user", "u");
        $qb->where("password = :password")->setParameter("password", (md5($password)));
        $qb->andwhere("login = :login")->setParameter("login", $login);

        return $qb->execute()->fetch();
    }

    public function SignUp($dbal, $login, $password, $role) {
        $qb = $dbal->createQueryBuilder();
        
        $qb->insert('user')->values(
                array(
                    'login' => '?',
                    'password' => '?',
                    'role' => '?'
                ))

        ->setParameter(0, $login)
        ->setParameter(1, (md5($password)))
        ->setParameter(2, $role);

        return $qb->execute();
    }

    public function getID() {
        return $this->data['id'];
    }

    public function getLogin() {
        return $this->data['login'];
    }

    public function getPasswordHash() {
        return $this->data['password'];
    }

    public function getRole() {
        return $this->data['role'];
    }

}