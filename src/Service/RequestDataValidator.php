<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class RequestDataValidator
{

    public const ENDPOINT_VIEWING = 'viewing';

    public function validateRequest(array|bool $data, string $endpoint = ""): void
    {
        if($data === false || ! is_array($data) || empty($data)){
            throw new BadRequestException("Invalid request body");
        }

        switch ($endpoint){
            case self::ENDPOINT_VIEWING:
                if( ! isset($data['episode']) || ! isset($data['customer']) ){
                    throw new BadRequestException("Episode GUID and Customer GUID are mandatory");
                }
                break;
            default:
                throw new BadRequestException("Endpoint is not valid");
        }
    }
}