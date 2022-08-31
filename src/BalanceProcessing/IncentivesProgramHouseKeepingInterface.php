<?php

namespace App\BalanceProcessing;

interface IncentivesProgramHouseKeepingInterface extends BalanceCalculationInterface
{
    public function initializePoints( ):void;

    public function RemoveExpiredBoosterPoints( ):void;

    public function updateAllPoints( ):void;
}