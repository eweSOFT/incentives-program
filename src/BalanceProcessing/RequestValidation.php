<?php

namespace App\BalanceProcessing;

class RequestValidation
{
    public function validateBoosterPoints(?object $incentivesProgram): array
    {
        $in = $incentivesProgram; // shorten variable

        //validate booster points
        $sum = 0; $boosterResponse = false; $bonusResponse = false;
        foreach($in->getBoosterPoints() as $key => $value ) {
            $sum += $value['points'];
        }

        $boosterResponse = ($in->getBonusPoints() - $sum) >= 0; //return true if expression is true

        //validate previous points
        $allPoints = $in->getAllPoints();
        $total = $allPoints['total_points'];
        $bonusResponse = ($allPoints['bonus_points'] + $allPoints['action_points']) == $total;

        if(!$boosterResponse  || !$bonusResponse)
            $response = false;
        else $response = true;

        return ["response"=>$response, "message"=>'{"error":"The previous balances are either incorrect or the previous unclaimed booster points is higher than bonus balance"}'];
    }
}