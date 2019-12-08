<?php

namespace Entity;

class Review {

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
                self::$instances[$id] = new Review($dbal, $id);
                return self::$instances[$id];
            }
        } catch (\Exception\NotFoundException $unfe) {
            return null;
        }
    }

    private function loadData() {
        $qb = $this->dbal->createQueryBuilder();
        $qb->select("ID, Recency, Interestingness_and_benefit, Originality, Professional_level, Language_and_stylistic_level, ID_reviewer, ID_contribution");
        $qb->from("Review", "R");
        $qb->where("ID = :id")->setParameter("id", $this->id);

        $this->data = $qb->execute()->fetch();
        $ID_reviewer = $this->data['ID_reviewer'];
        $this->data['ID_reviewer'] = \Entity\Login::load($this->dbal, $ID_reviewer)->getLogin();

    }

    public function MakeReview($dbal, $articleID, $Recency, $Interestingness_and_benefit, $Originality, $Professional_level, $Language_and_stylistic_level, $ID_reviewer, $ID_contribution) {

        $qb = $dbal->createQueryBuilder();
        
        $qb->insert('Review')->values(
                array(
                    'Recency' => '?',
                    'Interestingness_and_benefit' => '?',
                    'Originality' => '?',
                    'Professional_level' => '?',
                    'Language_and_stylistic_level' => '?',
                    'ID_reviewer' => '?',
                    'ID_contribution' => '?',
                ))

        ->setParameter(0, $Recency)
        ->setParameter(1, $Interestingness_and_benefit)
        ->setParameter(2, $Originality)
        ->setParameter(3, $Professional_level)
        ->setParameter(4, $Language_and_stylistic_level)
        ->setParameter(5, $ID_reviewer)
        ->setParameter(6, $ID_contribution);

        $qb->execute();

        $reviewID = $qb->getConnection()->lastInsertId();

        $qb = $dbal->createQueryBuilder();
        $qb->update("Contribution")
            ->set("ID_review", ":ID_review")->setParameter("ID_review", $reviewID)
            ->where("ID = :ID")->setParameter("ID", $articleID);

        return $qb->execute();

    }

    public function getID() {
        return $this->data['ID'];
    }

    public function getRecencyResult() {
        return $this->data['Recency'];
    }

    public function getBenefitResult() {
        return $this->data['Interestingness_and_benefit'];
    }

    public function getOriginalityResult() {
        return $this->data['Originality'];
    }

    public function getProfessionalResult() {
        return $this->data['Professional_level'];
    }

    public function getLanguageResult() {
        return $this->data['Language_and_stylistic_level'];
    }

    public function getReviewer() {
        return $this->data['ID_reviewer'];
    }

    public function getContribution() {
        return $this->data['ID_contribution'];
    }

}