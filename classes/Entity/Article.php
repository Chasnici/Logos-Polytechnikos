<?php

namespace Entity;

class Article {

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
                self::$instances[$id] = new Article($dbal, $id);
                return self::$instances[$id];
            }
        } catch (\Exception\NotFoundException $unfe) {
            return null;
        }
    }

    private function loadData() {
        $qb = $this->dbal->createQueryBuilder();
        $qb->select("ID, Title, Full_text, ID_autor, Agreed, Date");
        $qb->from("Contribution", "C");
        $qb->where("ID = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
        $ID_autor = $this->data['ID_autor'];
        $this->data['ID_autor'] = \Entity\Login::load($this->dbal, $ID_autor)->getLogin();

    }

    public function getAllArticles($dbal) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Title, Full_text, ID_autor, Agreed, Date");
        $qb->from("Contribution", "C");

        $result = $qb->execute()->fetchall();

        $Articles[] = null;
        foreach ($result as $res) {
            $Articles[] = Article::load($dbal, $res['ID']);
        }

        return $Articles;
    }

    public function getAllPublicArticles($dbal) {
        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Title, Full_text, ID_autor, Agreed, Date");
        $qb->from("Contribution", "C");
        $qb->where("Agreed = 1");

        $result = $qb->execute()->fetchall();

        $Articles[] = null;
        foreach ($result as $res) {
            $Articles[] = Article::load($dbal, $res['ID']);
        }

        return $Articles;
    }

    public function SelectReviewer($dbal, $reviewerID, $articleID) {
        
        $qb = $dbal->createQueryBuilder();
        $qb->update("Contribution")
            ->set("ID_reviewer", ":IDReviewer")->setParameter("IDReviewer", $reviewerID)
            ->where("ID = :ID")->setParameter("ID", $articleID);

        return $qb->execute();

    }

    public function getAllArticlesByReviewer($dbal, $reviewerID) {
        
        $qb = $dbal->createQueryBuilder();
        $qb->select("ID, Title, Full_text, ID_autor, Agreed, Date");
        $qb->from("Contribution", "C");
        $qb->where("ID_reviewer = :reviewerID")->setParameter(":reviewerID", $reviewerID);;

        $result = $qb->execute()->fetchall();

        $Articles[] = null;
        foreach ($result as $res) {
            $Articles[] = Article::load($dbal, $res['ID']);
        }

        return $Articles;

    }

    public function MakeArticlePublic($dbal, $articleID) {
        
        $qb = $dbal->createQueryBuilder();
        $qb->update("Contribution")
            ->set("Agreed", ":Agreed")->setParameter("Agreed", 1)
            ->where("ID = :ID")->setParameter("ID", (int)$articleID);

        return $qb->execute();

    }

    public function MakeArticleNotPublic($dbal, $articleID) {
        
        $qb = $dbal->createQueryBuilder();
        $qb->update("Contribution")
            ->set("Agreed", ":Agreed")->setParameter("Agreed", 3)
            ->where("ID = :ID")->setParameter("ID", (int)$articleID);

        return $qb->execute();

    }

    public function getID() {
        return $this->data['ID'];
    }

    public function getArticleTitle() {
        return $this->data['Title'];
    }

    public function getArticle() {
        return $this->data['Full_text'];
    }

    public function getAutorID() {
        return $this->data['ID_autor'];
    }

    public function getDate() {
        return $this->data['Date'];
    }

    public function getRedaktorDecision() {
        return $this->data['Agreed'];
    }

}