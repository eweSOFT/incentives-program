<?php

namespace App\Controller;

use App\BalanceProcessing\BalanceCalculationInterface;
use App\BalanceProcessing\IncentivesProgramSetupInterface;
use App\BalanceProcessing\RequestValidation;
use App\Data\JsonData;
use App\DTO\IncentivesProgram;
use App\Service\Serializer\DTOSerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IncentivesProgramController extends AbstractController
{
    #[Route('/calculate-balance', name: 'calculate-balance', methods: 'GET')]
    //#[Route('/calculate-balance', name: 'calculate-balance', methods: 'POST')]
    public function calculateBalance(
        JsonData $request,//This can be commented to send post request from POSTMAN, just uncomment the Request below and change GET to POST in the route above
        //Request $request,
        DTOSerializer $serializer, //DTO - Data Transfer Object
        RequestValidation $validate,
        IncentivesProgramSetupInterface $setup,
        BalanceCalculationInterface $balanceCalculation,
    ): Response
    {
        /**  if ($request->headers->has('force_fail')) {
            return new JsonResponse(
                ['error' => 'Incentives Program failure message'],
                $request->headers->get('force_fail')
            );
        }*/

        //deserialize json request body for processing and set/initialize
        // allpoints, actions, boosters, validity, boosterpoints and actiontypes
        $incentivesProgram = $serializer->deserialize(
            $request->getContent(), IncentivesProgram::class, 'json'
        );

        //Initialize action, bonus and total balances with previous values from all points
        $setup->initializePoints($incentivesProgram);

        $validatePoints = $validate->validateBoosterPoints($incentivesProgram);

        if(!$validatePoints['response']) {
            return new Response(
                $validatePoints['message'], 400, ['Content-Type' => 'application/json']
            );
        }

        //check if any bonus has expired and remove from the list and shrink down balances
        $setup->RemoveExpiredBoosterPoints( $incentivesProgram );

        //calculate new action points then update action and total balances
        $balanceCalculation->calculateActionPoints( $incentivesProgram );

        //calculate booster points then update bonus and total balances
        $balanceCalculation->calculateBoosterPoints( $incentivesProgram );

        //update all points (action, bonus and total) balances for response
        $setup->updateAllPoints( $incentivesProgram );

        //serialize json for response
        $responseContent = $serializer->serialize($incentivesProgram, 'json');

        return new Response(
            $responseContent, 200, ['Content-Type'=>'application/json']
        );
    }
}