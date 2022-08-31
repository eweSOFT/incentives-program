<?php

namespace App\BalanceProcessing;

interface BalanceCalculationInterface
{
    /** Interface implemented by BalanceProcessing */
    public function calculateActionPoints( ?object $incentivesProgram  ):void;

    public function calculateBoosterPoints( ?object $incentivesProgram  ):void;

}