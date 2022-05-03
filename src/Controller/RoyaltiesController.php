<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Payment;
use App\Service\RequestDataValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class RoyaltiesController extends AbstractController
{
    #[Route('/royaltymanager/reset', name: 'royalties_reset', methods:"POST")]
    public function reset(KernelInterface $kernel): Response
    {

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'doctrine:fixtures:load'
        ]);

        $input->setInteractive(false);
        
        $application->run($input);

        return $this->json([]);
    }

    #[Route('/royaltymanager/viewing', name: 'royalties_viewing', methods:"POST")]
    public function viewing(
        Request $request, 
        RequestDataValidator $requestValidator,
        EntityManagerInterface $em
    ): Response
    {
        $requestData = json_decode($request->getContent(), true);

        try{
            $requestValidator->validateRequest($requestData, RequestDataValidator::ENDPOINT_VIEWING);

            // Get Episode from database
            $episodeGuid = $requestData['episode'];

            $episode = $em->getRepository(Episode::class)->findOneByGuid($episodeGuid);

            if( is_null($episode) ){
                return $this->json([
                    'message' => "The given Episode GUID does not exist in database"
                ], 400);    
            }
            
            // Record viewing

            $studio = $episode->getRightsowner();

            $payment = $em->getRepository(Payment::class)->findOneBy([
                'rightsowner' => $studio->getId()
            ]);

            if( is_null($payment) ){
                $payment = new Payment();
                $payment->setRightsowner($studio);
            }

            $payment->incrementViewing();

            $payment->addRoyalty( $studio->getPayment() );

            $em->persist($payment);

            $em->flush();

            return $this->json([]);

        }
        catch(BadRequestException $e){
            return $this->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

}
