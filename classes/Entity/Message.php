<?php

namespace Entity;

class Message {

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
                self::$instances[$id] = new Message($dbal, $id);
                return self::$instances[$id];
            }
        } catch (\Exception\NotFoundException $unfe) {
            return null;
        }
    }

    private function loadData() {
        $qb = $this->dbal->createQueryBuilder();
        $qb->select("id, subject, text, fromuser, touser");
        $qb->from("message", "m");
        $qb->where("id = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
    }

    public static function getMessageById($dbal, $toUser) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("id, subject, text, fromuser, touser");
        $qb->from("message", "m");
        // $qb->where("fromuser = :fromuser")->setParameter("fromuser", $from);
        $qb->where("touser = :touser")->setParameter("touser", $toUser);

        $result = $qb->execute()->fetchAll();

        $Messages[] = null;
        foreach ($result as $res) {
            $Messages[] = Message::load($dbal, $res['id']);
        }

        return $Messages;

    }

    public static function sendMessage($dbal, $sendto, $sendfrom, $subject, $message) {

        $qb = $dbal->createQueryBuilder()
                    ->insert('message')
                    ->values(
                        array(
                            'subject' => '?',
                            'text' => '?'
                        )
                    )
                    ->setParameter(0, $subject)
                    ->setParameter(1, $message);


        if ($qb->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public function getID() {
        return $this->data['id'];
    }

    public function getSubject() {
        return $this->data['subject'];
    }

    public function getText() {
        return $this->data['text'];
    }

    public function getPasswordHash() {
        return $this->data['fromuser'];
    }

    public function getRole() {
        return $this->data['touser'];
    }


}