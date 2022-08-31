<?php

namespace App\BalanceProcessing;

interface IncentivesProgramSetupInterface
{
    public function initializePoints( ?object $incentivesProgram ):void;

    public function RemoveExpiredBoosterPoints( ?object $incentivesProgram ):void;

    public function updateAllPoints( ?object $incentivesProgram ):void;
}