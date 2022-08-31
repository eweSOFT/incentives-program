<?php

namespace App\BalanceProcessing;

class BalanceProcessing implements BalanceCalculationInterface
{
    public function calculateActionPoints( ?object $incentivesProgram ):void
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

    public function calculateBoosterPoints( ?object $incentivesProgram  ):void
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

    private static function updateBonus(?object $incentivesProgram, ?int $bonusPoints):void
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