<?php

namespace App\BalanceProcessing;

//use App\DTO\IncentivesProgram;

interface BalanceProcessingInterface
{
    public function calculateActionPoints( ?object $incentivesProgram ):void;

    public function calculateBoosterPoints( ?object $incentivesProgram ):void;

    public function removeExpiredBoosterPoints( ?object $incentivesProgram ):void;

    public function initializePoints( ?object $incentivesProgram ):void;

    public function updateAllPoints( ?object $incentivesProgram ):void;
}