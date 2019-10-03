<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model;

final class Campaign extends HttpApi
{
    /**
     * @throws Exception
     */
    public function trigger(int $id, array $params = [])
    {
        $response = $this->apiPost("campaigns/$id/triggers", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrator->hydrate($response, Model\Campaign\CampaignTrigerred::class);
    }
}
