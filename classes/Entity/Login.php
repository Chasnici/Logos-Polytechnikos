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
        $qb->select("ID, Login_name, Login_password, Role");
        $qb->from("Person", "P");
        $qb->where("id = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
    }


    public function Login($dbal, $login, $password) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Login_name, Login_password, Role");
        $qb->from("Person", "P");
        $qb->where("Login_password = :password")->setParameter("password", (md5($password)));
        $qb->andwhere("Login_name = :login")->setParameter("login", $login);

        return $qb->execute()->fetch();
    }

    public function SignUp($dbal, $login, $password, $role) {
        $qb = $dbal->createQueryBuilder();
        
        $qb->insert('Person')->values(
                array(
                    'Login_name' => '?',
                    'Login_password' => '?',
                    'Role' => '?'
                ))

        ->setParameter(0, $login)
        ->setParameter(1, (md5($password)))
        ->setParameter(2, $role);

        return $qb->execute();
    }

    public function getID() {
        return $this->data['ID'];
    }

    public function getLogin() {
        return $this->data['Login_name'];
    }

    public function getPasswordHash() {
        return $this->data['Login_password'];
    }

    public function getRole() {
        return $this->data['Role'];
    }

}