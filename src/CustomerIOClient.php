<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class CustomerIOClient
{
    private HttpClientInterface $behavioralTrackingClient;
    private HttpClientInterface $apiClient;

    /**
     * The constructor accepts already configured HTTP clients.
     * Use the configure method to pass a configuration to the Client and create an HTTP Client.
     */
    public function __construct(HttpClientInterface $behavioralTrackingClient, HttpClientInterface $apiClient)
    {
        $this->behavioralTrackingClient = $behavioralTrackingClient;
        $this->apiClient = $apiClient;
    }

    public function campaigns(): Api\Campaign
    {
        return new Api\Campaign($this->behavioralTrackingClient, $this->apiClient);
    }

    public function customers(): Api\Customer
    {
        return new Api\Customer($this->behavioralTrackingClient, $this->apiClient);
    }

    public function events(): Api\Event
    {
        return new Api\Event($this->behavioralTrackingClient, $this->apiClient);
    }
}
