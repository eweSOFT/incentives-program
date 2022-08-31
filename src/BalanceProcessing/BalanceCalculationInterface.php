<?php

namespace App\BalanceProcessing;

interface BalanceCalculationInterface
{
    public function calculateActionPoints( ):void;

    public function calculateBoosterPoints( ):void;

}