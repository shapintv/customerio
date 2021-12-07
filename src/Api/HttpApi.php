<?php

declare(strict_types=1);

/*
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Shapin\CustomerIO\Api;

use Shapin\CustomerIO\Exception\Domain as DomainExceptions;
use Shapin\CustomerIO\Exception\DomainException;
use Shapin\CustomerIO\Exception\HydrationException;
use Shapin\CustomerIO\Exception\LogicException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class HttpApi
{
    protected HttpClientInterface $behavioralTrackingClient;
    protected HttpClientInterface $apiClient;

    public function __construct(HttpClientInterface $behavioralTrackingClient, HttpClientInterface $apiClient)
    {
        $this->behavioralTrackingClient = $behavioralTrackingClient;
        $this->apiClient = $apiClient;
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function btPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->btPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function btPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('PUT', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function btDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('DELETE', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function apiGet(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('GET', $path, [
            'query' => $params,
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function apiPost(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiPostRaw($path, $this->createJsonBody($params), $requestHeaders);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function apiPut(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('PUT', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * @param array<int|string, mixed> $params
     * @param array<string, string>    $requestHeaders
     */
    protected function apiDelete(string $path, array $params = [], array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('DELETE', $path, [
            'body' => $this->createJsonBody($params),
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Handle HTTP errors.
     *
     * Call is controlled by the specific API methods.
     *
     * @throws DomainException
     */
    protected function handleErrors(ResponseInterface $response): void
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

    /**
     * Hydrate an instanceof $class with the response content.
     *
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    protected function hydrate(ResponseInterface $response, string $class): mixed
    {
        if (!isset($response->getHeaders()['content-type'])) {
            throw new HydrationException('Cannot use a response without content type');
        }
        if (false === $contentType = reset($response->getHeaders()['content-type'])) {
            throw new HydrationException('Cannot use a response without content type');
        }
        if (0 !== strpos($contentType, 'application/json')) {
            throw new HydrationException("Cannot use a response with Content-Type: $contentType");
        }

        $data = json_decode($response->getContent(), true);
        if (\JSON_ERROR_NONE !== json_last_error()) {
            throw new HydrationException(sprintf('Error (%d) when trying to json_decode response', json_last_error()));
        }

        return new $class($data);
    }

    /**
     * @param array<string, string> $requestHeaders
     */
    private function btPostRaw(string $path, ?string $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->behavioralTrackingClient->request('POST', $path, [
            'body' => $body,
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * @param array<string, string> $requestHeaders
     */
    private function apiPostRaw(string $path, ?string $body, array $requestHeaders = []): ResponseInterface
    {
        return $this->apiClient->request('POST', $path, [
            'body' => $body,
            'headers' => $requestHeaders,
        ]);
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array<int|string, mixed> $params
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
}
