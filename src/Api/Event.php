<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model;
use Symfony\Component\Config\Definition\Processor;

final class Event extends HttpApi
{
    /**
     * @throws Exception
     */
    public function trackCustomerEvent(string $id, string $name, array $data = [], string $type = null)
    {
        $params = [
            'name' => $name,
            'data' => $data,
        ];

        if (null !== $type) {
            $params['type'] = $type;
        }

        $response = $this->httpPost("/api/v1/customers/$id/events", $params);

        if (!$this->hydrator) {
            return $response;
        }

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Event\CustomerEventCreated::class);
    }

    /**
     * @throws Exception
     */
    public function trackAnonymousEvent(string $name, array $data = [])
    {
        $response = $this->httpPost("/api/v1/events", [
            'name' => $name,
            'data' => $data,
        ]);

        if (!$this->hydrator) {
            return $response;
        }

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Event\AnonymousEventCreated::class);
    }
}
