<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Exception\Domain;

use Psr\Http\Message\ResponseInterface;
use Shapin\CustomerIO\Exception\DomainException;

class BadRequestException extends \Exception implements DomainException
{
    protected $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $content = json_decode($response->getBody()->__toString(), true);

        parent::__construct(implode(' / ', $content['meta']['errors']));
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
