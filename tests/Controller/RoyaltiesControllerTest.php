<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoyaltiesControllerTest extends WebTestCase
{
    public function testPayments(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/royaltymanager/payments');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame("json");
        
    }

    /**
     * @dataProvider getRightsOwnerData 
     */
    public function testPaymentsRightsOwner(int $testNumber, string $rightsOwnerGuid): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', "/royaltymanager/payments/$rightsOwnerGuid");

        $this->assertResponseFormatSame("json");

        switch($testNumber){
            case 1:
                $this->assertResponseIsSuccessful();
                break;
            case 2:
                $this->assertResponseStatusCodeSame(404, "The rightsowner GUID given does not exist in database");
                break;    
        }
    }

    /**
     * @dataProvider getViewingData
     */
    public function testViewing(int $testNumber, string $episodeGuid): void
    {
        $client = static::createClient();

        $postData = [
            'episode' => $episodeGuid
        ];

        if($testNumber != 3){
            $postData['customer'] = "customer_guid";
        }

        $crawler = $client->jsonRequest(
            'POST', 
            '/royaltymanager/viewing',
            $postData

        );

        $this->assertResponseFormatSame('json');

        switch($testNumber){
            case 1:
                $this->assertResponseIsSuccessful();
                break;
            case 2:
                $this->assertResponseStatusCodeSame(400, "The given Episode GUID does not exist in database");
                break;
            case 3:
                $this->assertResponseStatusCodeSame(400, "Episode GUID and Customer GUID are mandatory");
                break;    
        }
    }

    private function getRightsOwnerData(): array
    {
        return [
            [1,"665115721c6f44e49be3bd3e26606026"], // Existing rightsowner
            [2,"665115721c6f44e49be3bd3e56606026"], // Not existing rightsowner
        ];
    }

    private function getViewingData(): array
    {
        return [
            [1, "6a1db5d6610a4c048d3df9a6268c68dc"], // Existing episode
            [2, "6a1db5d6610a4c048d3df9a6268c99dc"], // No existing episode
            [3, "cd01aadd88fa4f8ca3290d118d9621a1"], // Existing episode but without customer guid in request
        ];
    }
}
