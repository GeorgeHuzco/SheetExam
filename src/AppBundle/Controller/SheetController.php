<?php
// src/AppBundle/Controller/SheetController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Model\Tier;
use AppBundle\Model\Tiers;

class SheetController extends Controller {

    /**
     * @Route("/sheet/calc")
     */
    public function calcAction(Request $request) {
        $session = $request->getSession();

        /*
         * The first time the page loads during a session
         * we load the three default tiers.
         *
         * Otherwise we get the tiers from the session.
         *
         */
        if ($session->has('tiers')) {
            $tiers = $session->get('tiers');
        } else {
            $tiers = new Tiers();
            $tiers->setDefaultTiers();
            $session->set('tiers', $tiers);
        }

        $artifacts = $request->request->get('artifacts', $session->get('artifacts', 0));
        $session->set('artifacts', $artifacts);

        $duplicates = $request->request->get('duplicates', $session->get('duplicates', 0));
        $session->set('duplicates', $duplicates);

        $versions = $request->request->get('versions', $session->get('versions', 0));
        $session->set('versions', $versions);

        $removed = $artifacts * $duplicates / 100;
        $folded = ($artifacts - $removed) * $versions / 100;
        $total = $artifacts - $removed - $folded;

        $tiers->processTiers($total);
        $totalCost = $tiers->calculateTotalCost();
        if ($total > 0) {
            $average = $totalCost / $total;
        } else {
            $average = 0;
        }

        return $this->render(
            'sheet/sheet.html.twig',
            array(
                'artifacts' => $artifacts,
                'duplicates' => $duplicates,
                'versions' => $versions,
                'removed' => $removed,
                'folded' => $folded,
                'total' => $total,
                'totalCost' => money_format('$%i', $totalCost),
                'average' => money_format('$%i', $average),
                'paCost' => money_format('$%i', $totalCost * 12)
            )
        );
    }

    /**
     * @Route("/sheet/addTier")
     *
     * You can only add a tier to the end of the range.
     *
     */
    public function addTierAction(Request $request) {
        $session = $request->getSession();
        $tiers = $session->get('tiers');
        $tier = new Tier(
            $request->request->get('id'),
            $request->request->get('price'),
            $request->request->get('minUnits'),
            $request->request->get('maxUnits')
        );
        if ($tiers->validate($tier)) {
            $tiers->addTier($tier);
        } else {
            echo "Invalid tier data.";
        }
        return $this->calcAction($request);

    }

    /**
     * @Route("/sheet/removeLastTier")
     *
     * You can only remove the last tier in the range.
     */
    public function removeLastTierAction(Request $request) {
        $session = $request->getSession();
        $tiers = $session->get('tiers');
        $tiers->removeLastTier();
        return $this->calcAction($request);
    }
}