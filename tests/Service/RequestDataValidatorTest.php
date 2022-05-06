<?php

namespace App\Tests\Service;

use App\Service\RequestDataValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RequestDataValidatorTest extends TestCase
{
    /**
     * @dataProvider getValidateRequestData 
     */
    public function testValidateRequest(int $testNumber, string $endpoint, bool $validData, string $typeOfInvalidData = ""): void
    {
        $requestDataValidator = new RequestDataValidator();

        $bodyData = [];

        if( ! $validData){
            $exceptionMessage = "";
            switch ($typeOfInvalidData){
                case 'notValidBody':
                    $bodyData = false;
                    $exceptionMessage = "Invalid request body";
                    break;
                case 'notValidParameters':
                    $bodyData = ["episode"];
                    $exceptionMessage = "Episode GUID and Customer GUID are mandatory";
                    break;
                case 'notValidEndpoint':
                    $bodyData = ["episode"];
                    $exceptionMessage = "Endpoint is not valid";
                    break;        
            }

            $this->expectException(BadRequestException::class);

            $this->expectExceptionMessage($exceptionMessage);

            $requestDataValidator->validateRequest($bodyData, $endpoint);
        }
        else{
            $bodyData = [
                "episode" => "episode_guid",
                "customer" => "customer_guid"
            ];

            $this->expectNotToPerformAssertions();

            $requestDataValidator->validateRequest($bodyData, $endpoint);
            
            
        }
        
    }

    private function getValidateRequestData(): array
    {
        return [
            [1, "viewing", false, "notValidBody"], // Not valid data body for endpoint viewing
            [2, "viewing", false, "notValidParameters"], // Not valid parameters for endpoint viewing
            [3, "", false, "notValidEndpoint"], // No valid endpoint
            [4, "viewing", true], // Valid data
        ];
    }
}
