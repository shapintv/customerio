<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception\Domain as DomainExceptions;
use Shapin\CustomerIO\Exception\DomainException;
use Shapin\CustomerIO\Exception\LogicException;
use Shapin\CustomerIO\Hydrator\Hydrator;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class HttpApi
{
    /**
     * @var HttpClient
     */
    protected $behavioralTrackingClient;

    /**
     * @var HttpClient
     */
    protected $apiClient;

    /**
     * @var Hydrator
     */
    protected $hydrator;

    public function __construct(HttpClientInterface $behavioralTrackingClient, HttpClientInterface $apiClient, Hydrator $hydrator)
    {
        $this->behavioralTrackingClient = $behavioralTrackingClient;
        $this->apiClient = $apiClient;
        $this->hydrator = $hydrator;
    }

    protected function btPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->btPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    protected function btPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('POST', $path, [
            'body' => $body,
            'headers' => $requestHeaders,
        ]);
    }

    protected function btPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('PUT', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    protected function btDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('DELETE', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    protected function apiGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('GET', $path, [
            'query' => $params,
            'headers' => $requestHeaders,
        ]);
    }

    protected function apiPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    protected function apiPostRaw(string $path, $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('POST', $path, [
            'body' => $body,
            'headers' => $requestHeaders,
        ]);
    }

    protected function apiPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('PUT', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    protected function apiDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('DELETE', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @throws LogicException
     */
    private function createJsonBody(array $params): ?string
    {
        if (0 === \count($params)) {
            return null;
        }

        $body = json_encode($params, \JSON_FORCE_OBJECT);

        if (!\is_string($body)) {
            throw new LogicException('An error occured when encoding body: '.json_last_error_msg());
        }

        return $body;
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws DomainException
     */
    protected function handleErrors(ResponseInterface $response)
    {
        switch ($response->getStatusCode()) {
            case 400:
                throw new DomainExceptions\BadRequestException($response);
            case 401:
                throw new DomainExceptions\UnauthorizedException();
            case 404:
                throw new DomainExceptions\NotFoundException();
            case 429:
                throw new DomainExceptions\TooManyRequestsException();
            default:
                throw new DomainExceptions\UnknownErrorException();
        }
    }
}
