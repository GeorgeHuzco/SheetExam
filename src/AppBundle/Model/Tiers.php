<?php
namespace AppBundle\Model;

use AppBundle\Model\Tier;

class Tiers {

    public $tiersArray = array();

    public function setDefaultTiers() {
        $tier1 = new Tier(1, 1.00, 1, 1000, 1000);
        $tier2 = new Tier(2, 0.70, 1001, 5000, 4000);
        $tier3 = new Tier(3, 0.50, 5001, 15000, 10000);
        $this->tiersArray = array($tier1, $tier2, $tier3);
    }

    public function addTier($tier) {
        array_push($this->tiersArray, $tier);
    }

    public function removeLastTier() {
        unset($this->tiersArray[count($this->tiersArray) - 1]);
    }

    public function processTiers($totalUnits) {
        foreach($this->tiersArray as $index => $tier) {
            if ($totalUnits >= $tier->getMaxUnits()) {
                $tier->setInRange($tier->getMaxUnits() - $tier->getMinUnits() + 1);
            } else if ($totalUnits < $tier->getMinUnits()) {
                $tier->setInRange(0);
            } else {
                $tier->setInRange($totalUnits - $tier->getMinUnits() + 1);
            }
        }
    }

    public function calculateTotalCost() {
        $totalCost = 0;
        foreach($this->tiersArray as $tier) {
            $totalCost += $tier->getInRange() * $tier->getPrice();
        }
        return $totalCost;
    }

    public function validate($tier) {
        return $tier->getId() == count($this->tiersArray) + 1;
    }
}



