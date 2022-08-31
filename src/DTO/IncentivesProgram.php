<?php

namespace App\DTO;

use App\DTO\IncentivesProgramInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

class IncentivesProgram implements IncentivesProgramInterface
{
    private ?int $totalBalance;

    private ?int $actionPoints;

    private ?int $bonusPoints;

    private ?array $allPoints;

    private ?array $boosterPoints;

    private ?array $actionTypes;

    private ?array $actions;

    private ?array $boosters;

    private ?string $validityPeriod;

    /**
     * @return int|null
     */
    public function getActionPoints(): ?int
    {
        return $this->actionPoints;
    }

    /**
     * @param int|null $actionPoints
     */
    public function setActionPoints(?int $actionPoints): void
    {
        $this->actionPoints = $actionPoints;
    }

    /**
     * @return int|null
     */
    public function getBonusPoints(): ?int
    {
        return $this->bonusPoints;
    }

    /**
     * @param int|null $bonusPoints
     */
    public function setBonusPoints(?int $bonusPoints): void
    {
        $this->bonusPoints = $bonusPoints;
    }

    /**
     * @return int|null
     */
    public function getTotalBalance(): ?int
    {
        return $this->totalBalance;
    }

    /**
     * @param int|null $totalBalance
     */
    public function setTotalBalance(?int $totalBalance): void
    {
        $this->totalBalance = $totalBalance;
    }

    /**
     * @return array|null
     */
    public function getBoosterPoints(): ?array
    {
        return $this->boosterPoints;
    }

    /**
     * @param array|null $boosterPoints
     */
    public function setBoosterPoints(?array $boosterPoints): void
    {
        $this->boosterPoints = $boosterPoints;
    }

    /**
     * @return array|null
     */
    public function getBoosters(): ?array
    {
        return $this->boosters;
    }

    /**
     * @param array|null $boosters
     */
    public function setBoosters(?array $boosters): void
    {
        $this->boosters = $boosters;
    }

    /**
     * @return array|null
     */
    public function getAllPoints(): ?array
    {
        return $this->allPoints;
    }

    /**
     * @param array|null $allPoints
     */
    public function setAllPoints(?array $allPoints): void
    {
        $this->allPoints = $allPoints;
    }

    /**
     * @return array|null
     */
    public function getActions(): ?array
    {
        return $this->actions;
    }

    /**
     * @param array|null $actions
     */
    public function setActions(?array $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @return array|null
     */
    public function getActionTypes(): ?array
    {
        return $this->actionTypes;
    }

    /**
     * @param array|null $actionTypes
     */
    public function setActionTypes(?array $actionTypes): void
    {
        $this->actionTypes = $actionTypes;
    }

    /**
     * @return string|null
     */
    public function getValidityPeriod(): ?string
    {
        return $this->validityPeriod;
    }

    /**
     * @param string|null $validityPeriod
     */
    public function setValidityPeriod(?string $validityPeriod): void
    {
        $this->validityPeriod = $validityPeriod;
    }
}