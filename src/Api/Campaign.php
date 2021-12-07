<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception;
use Shapin\CustomerIO\Model\Campaign\CampaignTrigerred;

final class Campaign extends HttpApi
{
    /**
     * @throws Exception
     *
     * @param array<int|string, mixed> $params
     */
    public function trigger(int $id, array $params = []): CampaignTrigerred
    {
        $response = $this->apiPost("campaigns/$id/triggers", $params);

        if (200 !== $response->getStatusCode()) {
            $this->handleErrors($response);
        }

        return $this->hydrate($response, CampaignTrigerred::class);
    }
}
