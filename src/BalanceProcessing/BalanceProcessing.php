<?php

namespace App\BalanceProcessing;

class BalanceProcessing
{
    public object $incentivesProgram;
    public function __construct(?object $incentivesProgram){
        $this->incentivesProgram = $incentivesProgram;
    }

public function initializePoints( ):void
    {
        $allPoints = $this->incentivesProgram->getAllPoints();
        $this->incentivesProgram->setActionPoints($allPoints['action_points']);
        $this->incentivesProgram->setBonusPoints($allPoints['bonus_points']);
        $this->incentivesProgram->setTotalBalance($allPoints['total_points']);
    }

    public function RemoveExpiredBoosterPoints( ):void
    {
        $newBoosterPoints = [];
        foreach($this->incentivesProgram->getBoosterPoints() as $key => $value ) {
            $today = date('Y-m-d'); //get today's date
            if($value['expiry_date'] >= $today){ // if booster has not expired
                $newBoosterPoints[] = [ // write a copy to new array
                    "points"=>$value['points'],
                    "expiry_date"=>$value['expiry_date']
                ];
            }
            else{// if booster has expired
                $total = $this->incentivesProgram->getTotalBalance();
                if($total > 0) { // to be sure the bonus has not been cashed out
                    if($total >= $value['points']){// to be sure the total balance is still more than the bonus
                        $total -= $value['points'];
                        $this->incentivesProgram->setTotalBalance($total);
                    }
                    else $this->incentivesProgram->setTotalBalance(0);// set total to zero
                }

                $bonus = $this->incentivesProgram->getBonusPoints();
                if($bonus > 0) {// to be sure the bonus has not been cashed out
                    if($bonus >= $value['points']){// to be sure the total balance is still more than the bonus
                        $bonus -= $value['points'];
                        $this->incentivesProgram->setBonusPoints($bonus);
                    }
                    else $this->incentivesProgram->setBonusPoints(0);// set total to zero
                }
            }
        }
        $this->incentivesProgram->setBoosterPoints($newBoosterPoints);
    }

    public function calculateActionPoints( ):void
    {
        $sum = 0;
        foreach($this->incentivesProgram->getActionTypes() as $typeKey=>$typeValue ){
            foreach($this->incentivesProgram->getActions() as $actionKey=>$actionValue){
                if($typeKey == $actionKey){
                    $sum += $actionValue['number'] * $typeValue;
                    break; //since each action is unique
                }
            }
        }
        $totalBalance = $sum;
        $sum += $this->incentivesProgram->getActionPoints();
        $this->incentivesProgram->setActionPoints($sum);

        $totalBalance += $this->incentivesProgram->getTotalBalance();
        $this->incentivesProgram->setTotalBalance($totalBalance);
    }

    public function calculateBoosterPoints( ):void
    {
        $sum = 0;
        foreach($this->incentivesProgram->getActions() as $actionKey=>$actionValue ){
            foreach($this->incentivesProgram->getBoosters() as $boosterKey=>$boosterValue) {
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
        $this::updateBonus($this->incentivesProgram, $sum);
        $totalBalance = $sum;
        $sum += $this->incentivesProgram->getBonusPoints();
        $this->incentivesProgram->setBonusPoints($sum);

        $totalBalance += $this->incentivesProgram->getTotalBalance();
        $this->incentivesProgram->setTotalBalance($totalBalance);
    }

    public function updateAllPoints( ):void
    {
        $this->incentivesProgram->setAllPoints([
            "action_points"=>$this->incentivesProgram->getActionPoints(),
            "bonus_points"=> $this->incentivesProgram->getBonusPoints(),
            "total_points"=>$this->incentivesProgram->getTotalBalance()
        ]);
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