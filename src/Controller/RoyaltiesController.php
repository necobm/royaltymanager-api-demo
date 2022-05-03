<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
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
}
