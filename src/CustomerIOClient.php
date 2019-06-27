<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO;

use Shapin\CustomerIO\Hydrator\ModelHydrator;
use Shapin\CustomerIO\Hydrator\Hydrator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CustomerIOClient
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Hydrator
     */
    private $hydrator;

    /**
     * The constructor accepts already configured HTTP clients.
     * Use the configure method to pass a configuration to the Client and create an HTTP Client.
     */
    public function __construct(HttpClientInterface $customerioClient, Hydrator $hydrator = null)
    {
        $this->httpClient = $customerioClient;
        $this->hydrator = $hydrator ?: new ModelHydrator();
    }

    public function customers(): Api\Customer
    {
        return new Api\Customer($this->httpClient, $this->hydrator);
    }

    public function events(): Api\Event
    {
        return new Api\Event($this->httpClient, $this->hydrator);
    }
}
