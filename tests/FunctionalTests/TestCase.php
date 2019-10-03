<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Tests\FunctionalTests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Shapin\CustomerIO\CustomerIOClient;
use Symfony\Component\HttpClient\HttpClient;

abstract class TestCase extends BaseTestCase
{
    const SITE_ID = '3d7ceea5abfa75ec0890';
    const API_KEY = 'c6af8922d5063c1cad2d';

    public function getCustomerIOClient()
    {
        $behavioralTrackingClient = HttpClient::create([
            'base_uri' => 'https://track.customer.io/api/v1/',
            'auth_basic' => [self::SITE_ID, self::API_KEY],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        $apiClient = HttpClient::create([
            'base_uri' => 'https://api.customer.io/v1/api/',
            'auth_basic' => [self::SITE_ID, self::API_KEY],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);

        return new CustomerIOClient($behavioralTrackingClient, $apiClient);
    }
}
