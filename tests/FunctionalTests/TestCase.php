<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Tests\FunctionalTests;

use Shapin\CustomerIO\HttpClientConfigurator;
use Shapin\CustomerIO\CustomerIOClient;
use GuzzleHttp\Psr7\Request;
use Http\Client\Exception\NetworkException;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    const SITE_ID = '3d7ceea5abfa75ec0890';
    const API_KEY = 'c6af8922d5063c1cad2d';

    public function getCustomerIOClient()
    {
        $httpClientConfigurator = new HttpClientConfigurator();
        $httpClientConfigurator
            ->setSiteId(self::SITE_ID)
            ->setApiKey(self::API_KEY)
        ;

        $httpClient = $httpClientConfigurator->createConfiguredClient();

        return new CustomerIOClient($httpClient);
    }
}
