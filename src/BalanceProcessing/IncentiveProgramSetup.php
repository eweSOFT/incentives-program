<?php

namespace App\BalanceProcessing;

class IncentiveProgramSetup implements IncentivesProgramSetupInterface
{
    public function initializePoints(?object $incentivesProgram):void
    {
        $allPoints = $incentivesProgram->getAllPoints();
        $incentivesProgram->setActionPoints($allPoints['action_points']);
        $incentivesProgram->setBonusPoints($allPoints['bonus_points']);
        $incentivesProgram->setTotalBalance($allPoints['total_points']);
    }

    public function RemoveExpiredBoosterPoints(?object $incentivesProgram):void
    {
        $newBoosterPoints = [];
        foreach($incentivesProgram->getBoosterPoints() as $key => $value ) {
            $today = date('Y-m-d'); //get today's date
            if($value['expiry_date'] >= $today){ // if booster has not expired
                $newBoosterPoints[] = [ // write a copy to new array
                    "points"=>$value['points'],
                    "expiry_date"=>$value['expiry_date']
                ];
            }
            else{// if booster has expired
                $total = $incentivesProgram->getTotalBalance();

                $bonus = $incentivesProgram->getBonusPoints();
                if($bonus > 0) {// to be sure the bonus has not been cashed out
                    if ($bonus >= $value['points']) {// to be sure the total balance is still more than the bonus
                        $bonus -= $value['points'];
                        $incentivesProgram->setBonusPoints($bonus);
                    } else $incentivesProgram->setBonusPoints(0);// set total to zero
                    //}

                    if ($total > 0) { // to be sure the bonus has not been cashed out
                        if ($total >= $value['points']) {// to be sure the total balance is still more than the bonus
                            $total -= $value['points'];
                            $incentivesProgram->setTotalBalance($total);
                        } else $incentivesProgram->setTotalBalance(0);// set total to zero
                    }
                }
            }
        }
        $incentivesProgram->setBoosterPoints($newBoosterPoints);
    }

    public function updateAllPoints(?object $incentivesProgram):void
    {
        $incentivesProgram->setAllPoints([
            "action_points"=>$incentivesProgram->getActionPoints(),
            "bonus_points"=> $incentivesProgram->getBonusPoints(),
            "total_points"=>$incentivesProgram->getTotalBalance()
        ]);
    }
}