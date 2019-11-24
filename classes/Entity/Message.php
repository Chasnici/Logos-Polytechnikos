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
        $qb->select("ID, Title_message, Text_message, date, ID_sender, ID_recipient");
        $qb->from("Message", "M");
        $qb->where("id = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
    }

    public static function getMessageById($dbal, $toUser) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Title_message, Text_message, date, ID_sender, ID_recipient");
        $qb->from("Message", "M");
        // $qb->where("fromuser = :fromuser")->setParameter("fromuser", $from);
        $qb->where("ID_recipient = :touser")->setParameter("touser", $toUser);

        $result = $qb->execute()->fetchAll();

        $Messages[] = null;
        foreach ($result as $res) {
            $Messages[] = Message::load($dbal, $res['ID']);
        }

        return $Messages;

    }

    public static function sendMessage($dbal, $recipient, $sender, $subject, $message) {

        $qb = $dbal->createQueryBuilder()
                    ->insert('Message')
                    ->values(
                        array(
                            'Title_message' => '?',
                            'Text_message' => '?',
                            'ID_recipient' => '?',
                            'ID_sender' => '?',
                            'date' => '?'
                        )
                    )
                    ->setParameter(0, $subject)
                    ->setParameter(1, $message)
                    ->setParameter(2, $recipient)
                    ->setParameter(3, $sender)
                    ->setParameter(4, date("Y-m-d H:i:s")
                );
                    

        if ($qb->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public static function sendHelpdeskMessage($dbal, $recipient, $subject, $message) {

        $qb = $dbal->createQueryBuilder()
                    ->insert('Helpdesk')
                    ->values(
                        array(
                            'Title_message' => '?',
                            'Text_message' => '?',
                            'ID_recipient' => '?',
                            'date' => '?'
                        )
                    )
                    ->setParameter(0, $subject)
                    ->setParameter(1, $message)
                    ->setParameter(2, $recipient)
                    ->setParameter(3, date("Y-m-d H:i:s")
                );
                    

        if ($qb->execute()) {
            return true;
        } else {
            return false;
        }

    }

    public static function getAllHelpdeskMessages($dbal) {

        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Title_message, Text_message, date, ID_recipient");
        $qb->from("Helpdesk", "H");

        $result = $qb->execute()->fetchAll();
        return $result;

    }

    public function getID() {
        return $this->data['ID'];
    }

    public function getSubject() {
        return $this->data['Title_message'];
    }

    public function getText() {
        return $this->data['Text_message'];
    }

    public function getDate() {
        return $this->data['date'];
    }

    public function getSender() {
        return $this->data['ID_sender'];
    }

    public function getRecipient() {
        return $this->data['ID_recipient'];
    }


}