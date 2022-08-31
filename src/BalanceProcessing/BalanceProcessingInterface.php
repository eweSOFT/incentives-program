<?php

namespace App\BalanceProcessing;

interface BalanceProcessingInterface
{
    public function initializePoints( ):void;

    public function RemoveExpiredBoosterPoints( ):void;

    public function calculateActionPoints( ):void;

    public function calculateBoosterPoints( ):void;

    public function updateAllPoints( ):void;
}