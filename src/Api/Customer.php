<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model\Customer\CustomerCreatedOrUpdated;
use Shapin\CustomerIO\Model\Customer\CustomerDeleted;
use Shapin\CustomerIO\Model\Customer\CustomerSuppressed;

final class Customer extends HttpApi
{
    /**
     * @throws Exception
     *
     * @param array<int|string, mixed> $params
     */
    public function createOrUpdate(string $id, array $params): CustomerCreatedOrUpdated
    {
        $response = $this->btPut("customers/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, CustomerCreatedOrUpdated::class);
    }

    public function delete(string $id): CustomerDeleted
    {
        $response = $this->btDelete("customers/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, CustomerDeleted::class);
    }

    public function suppress(string $id): CustomerSuppressed
    {
        $response = $this->btPost("customers/$id/suppress");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, CustomerSuppressed::class);
    }
}
