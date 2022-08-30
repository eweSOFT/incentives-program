<?php

namespace App\Controller;

use App\BalanceProcessing\BalanceProcessing;
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
    ): Response
    {
        //deserialize json request body for processing
        $incentivesProgram = $serializer->deserialize(
            $request->getContent(), IncentivesProgram::class, 'json'
        );

        $balanceProcessing = new BalanceProcessing($incentivesProgram);

        //Initialize action, bonus and total balance with previous values
        $balanceProcessing->initializePoints( );

        //check if any bonus has expired and remove from the list and shrink down balances
        $balanceProcessing->RemoveExpiredBoosterPoints(  );

        //calculate new action points then update action and total balances
        $balanceProcessing->calculateActionPoints(  );

        //calculate booster points then update bonus and total balances
        $balanceProcessing->calculateBoosterPoints(  );

        //update all points (action, bonus and total) balances for response
        $balanceProcessing->updateAllPoints(  );

        //serialize json for response
        $responseContent = $serializer->serialize($incentivesProgram, 'json');

        return new Response(
            $responseContent, 200, ['Content-Type'=>'application/json']
        );
    }
}