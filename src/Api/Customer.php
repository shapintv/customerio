<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model;

final class Customer extends HttpApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->btPut("customers/$id", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Customer\CustomerCreatedOrUpdated::class);
    }

    public function delete(string $id)
    {
        $response = $this->btDelete("customers/$id");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Customer\CustomerDeleted::class);
    }

    public function suppress(string $id)
    {
        $response = $this->btPost("customers/$id/suppress");

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Customer\CustomerSuppressed::class);
    }
}
