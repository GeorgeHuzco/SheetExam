<?php
namespace AppBundle\Model;

class Tier {
    private $id;
    private $price;
    private $minUnits;
    private $maxUnits;
    private $inRange = 0;

    function __construct($id, $price, $minUnits, $maxUnits) {
        $this->id = $id;
        $this->price = $price;
        $this->minUnits = $minUnits;
        $this->maxUnits = $maxUnits;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPrice() {
        return $this->price;
    }

    public function getPriceFormated() {
        return money_format('$%i', $this->price);
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getMinUnits() {
        return $this->minUnits;
    }

    /**
     * @param mixed $minUnits
     */
    public function setMinUnits($minUnits) {
        $this->minUnits = $minUnits;
    }

    /**
     * @return mixed
     */
    public function getMaxUnits() {
        return $this->maxUnits;
    }

    /**
     * @param mixed $maxUnits
     */
    public function setMaxUnits($maxUnits) {
        $this->maxUnits = $maxUnits;
    }

    /**
     * @return mixed
     */
    public function getInRange() {
        return $this->inRange;
    }

    /**
     * @param mixed $inRange
     */
    public function setInRange($inRange) {
        $this->inRange = $inRange;
    }
}