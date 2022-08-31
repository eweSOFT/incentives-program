<?php

namespace App\Tests\unit;

use App\BalanceProcessing\BalanceProcessing;
use App\DTO\IncentivesProgram;
use App\Tests\ServiceTestCase;

class BalanceProcessingTest extends ServiceTestCase
{
    protected object $incentivesProgram;
    protected function setUp(): void
    {
        $this->incentivesProgram = new IncentivesProgram();
    }

    /** @test */
    public function test_that_initialized_points_are_applied_correctly(): void
    {

        $this->incentivesProgram->setActionPoints(10);
        $this->incentivesProgram->setBonusPoints(4);
        $this->incentivesProgram->setTotalBalance(14);

        //When
        $total = $this->incentivesProgram->getActionPoints()+$this->incentivesProgram->getBonusPoints();
        $this->assertSame($total,$this->incentivesProgram->getTotalBalance());
    }

    /** @test */
    public function a_type_error_is_thrown_when_trying_to_add_a_non_int_value()
    {
                $this->expectException(\TypeError::class);
                $this->expectExceptionMessage("must be of type ?int");

                $this->incentivesProgram->setTotalBalance('r');

                $this->incentivesProgram->setActionPoints('r');

                $this->incentivesProgram->setBonusPoints('r');

    }

    /** @test */
    public function a_type_error_is_thrown_when_trying_to_add_a_non_array_value()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage("must be of type ?array");

        $this->incentivesProgram->setActions('r');

        $this->incentivesProgram->setAllPoints('r');

        $this->incentivesProgram->setBoosterPoints('r');

        $this->incentivesProgram->setBoosters('r');

        $this->incentivesProgram->setActionTypes('r');

    }

    /** @test */
    public function an_initialization_error_is_thrown_when_trying_to_get_a_value_that_is_not_set()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("must not be accessed before initialization");

        $boosterPoints = $this->incentivesProgram->getBoosterPoints();

        $boosters = $this->incentivesProgram->getBoosters();

        $actions = $this->incentivesProgram->getActions();

        $actionType  = $this->incentivesProgram->getActionTypes();

        $actionPoints  = $this->incentivesProgram->getActionPoints();

        $allPoints  = $this->incentivesProgram->getAllPoints();

        $validityPeriod  = $this->incentivesProgram->getValidityPeriod();

        $totalBalance  = $this->incentivesProgram->getTotalBalance();

        $bonusPoints  = $this->incentivesProgram->getBonusPoints();
    }

    /** @test */
    public function an_error_is_thrown_when_the_number_of_expected_arguments_is_not_complete()
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage("Too few arguments to function");

        $this->incentivesProgram->setValidityPeriod();

        $this->incentivesProgram->setTotalBalance();

        $this->incentivesProgram->setActionPoints();

        $this->incentivesProgram->setBonusPoints();

    }

}