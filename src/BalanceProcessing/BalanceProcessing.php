<?php

namespace App\BalanceProcessing;

use App\BalanceProcessing\BalanceProcessingInterface;

class BalanceProcessing implements BalanceProcessingInterface
{
    public function initializePoints( $incentivesProgram )
    {
        $allPoints = $incentivesProgram->getAllPoints();
        $incentivesProgram->setActionPoints($allPoints['action_points']);
        $incentivesProgram->setBonusPoints($allPoints['bonus_points']);
        $incentivesProgram->setTotalBalance($allPoints['total_points']);
    }

    public function RemoveExpiredBoosterPoints( $incentivesProgram )
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
                if($total > 0) { // to be sure the bonus has not been cashed out
                    if($total >= $value['points']){// to be sure the total balance is still more than the bonus
                        $total -= $value['points'];
                        $incentivesProgram->setTotalBalance($total);
                    }
                    else $incentivesProgram->setTotalBalance(0);// set total to zero
                }

                $bonus = $incentivesProgram->getBonusPoints();
                if($bonus > 0) {// to be sure the bonus has not been cashed out
                    if($bonus >= $value['points']){// to be sure the total balance is still more than the bonus
                        $bonus -= $value['points'];
                        $incentivesProgram->setBonusPoints($bonus);
                    }
                    else $incentivesProgram->setBonusPoints(0);// set total to zero
                }
            }
        }
        $incentivesProgram->setBoosterPoints($newBoosterPoints);
    }

    public function calculateActionPoints( $incentivesProgram )
    {
        $sum = 0;
        foreach($incentivesProgram->getActionTypes() as $typeKey=>$typeValue ){
            foreach($incentivesProgram->getActions() as $actionKey=>$actionValue){
                if($typeKey == $actionKey){
                   $sum += $actionValue['number'] * $typeValue;
                    break; //since each action is unique
                }
            }
        }
        $totalBalance = $sum;
        $sum += $incentivesProgram->getActionPoints();
        $incentivesProgram->setActionPoints($sum);

        $totalBalance += $incentivesProgram->getTotalBalance();
        $incentivesProgram->setTotalBalance($totalBalance);
    }

    public function calculateBoosterPoints( $incentivesProgram )
    {
        $sum = 0;
        foreach($incentivesProgram->getActions() as $actionKey=>$actionValue ){
            foreach($incentivesProgram->getBoosters() as $boosterKey=>$boosterValue) {
                $today = date('Y-m-d');// get today's date
                $dateDiff = $boosterValue['expiry_date'] >= $today;// check if booster has not expired

                /** Assumptions:
                I assumed that, take for instance, if a user can earn a booster of 5points
                when an action is carried out within 5hours, then, the user can earn the same 5points
                if the action is carried out in less than 5hours.
                 */
                if ($actionKey == $boosterKey && $dateDiff) {
                    if ($actionValue['number'] >= $boosterValue['number'] //check if qualified for bonus
                        && $actionValue['duration'] <= $boosterValue['duration'] //check if done within permitted duration
                    ) {
                        $duration = ceil( //round off to the lowest possible integer
                            $actionValue['duration'] / $boosterValue['duration']
                        );
                        $sum +=  $duration * $boosterValue['points'];
                    }
                    break;
                }
            }
        }
        $this::updateBonus($incentivesProgram, $sum);
        $totalBalance = $sum;
        $sum += $incentivesProgram->getBonusPoints();
        $incentivesProgram->setBonusPoints($sum);

        $totalBalance += $incentivesProgram->getTotalBalance();
        $incentivesProgram->setTotalBalance($totalBalance);
    }

    public function updateAllPoints( $incentivesProgram )
    {
        $incentivesProgram->setAllPoints([
            "action_points"=>$incentivesProgram->getActionPoints(),
            "bonus_points"=> $incentivesProgram->getBonusPoints(),
            "total_points"=>$incentivesProgram->getTotalBalance()
        ]);
    }

    private static function updateBonus($incentivesProgram, $bonusPoints)
    {
        //Set expiry date as today plus validity period
        $expiryDate = date("Y-m-d", strtotime(
                date('Y-m-d').$incentivesProgram->getValidityPeriod()
            )
        );
        $incentivesProgram->setBoosterPoints(
            array(//update the response by removing expired bonuses
                ["points"=>$bonusPoints, "expiry_date"=>$expiryDate],
                ...$incentivesProgram->getBoosterPoints()
            )
        );
    }
}