<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model\Event\AnonymousEventCreated;
use Shapin\CustomerIO\Model\Event\CustomerEventCreated;

final class Event extends HttpApi
{
    /**
     * @throws Exception
     *
     * @param array<int|string, mixed> $data
     */
    public function trackCustomerEvent(string $id, string $name, array $data = [], string $type = null): CustomerEventCreated
    {
        $params = [
            'name' => $name,
            'data' => $data,
        ];

        if (null !== $type) {
            $params['type'] = $type;
        }

        $response = $this->btPost("customers/$id/events", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, CustomerEventCreated::class);
    }

    /**
     * @throws Exception
     *
     * @param array<int|string, mixed> $data
     */
    public function trackAnonymousEvent(string $name, array $data = []): AnonymousEventCreated
    {
        $response = $this->btPost('events', [
            'name' => $name,
            'data' => $data,
        ]);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, AnonymousEventCreated::class);
    }
}
