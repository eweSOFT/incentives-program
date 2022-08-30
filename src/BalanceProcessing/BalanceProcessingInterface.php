<?php

namespace App\BalanceProcessing;

use App\DTO\IncentivesProgram;

interface BalanceProcessingInterface
{
    public function calculateActionPoints( $incentivesProgram );

    public function calculateBoosterPoints( $incentivesProgram );

    public function removeExpiredBoosterPoints( $incentivesProgram );

    public function initializePoints( $incentivesProgram );

    public function updateAllPoints( $incentivesProgram );
}