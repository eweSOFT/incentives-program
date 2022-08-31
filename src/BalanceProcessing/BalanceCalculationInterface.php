<?php

namespace App\BalanceProcessing;

interface BalanceCalculationInterface
{
    public function calculateActionPoints( ?object $incentivesProgram  ):void;

    public function calculateBoosterPoints( ?object $incentivesProgram  ):void;

}