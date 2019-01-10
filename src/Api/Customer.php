<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Configuration;
use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model;
use Symfony\Component\Config\Definition\Processor;

final class Customer extends HttpApi
{
    /**
     * @throws Exception
     */
    public function createOrUpdate(string $id, array $params)
    {
        $response = $this->httpPut("/api/v1/customers/$id", $params);

        if (!$this->hydrator) {
            return $response;
        }

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Customer\CustomerCreatedOrUpdated::class);
    }
}
