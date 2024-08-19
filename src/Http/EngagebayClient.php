<?php

namespace Aika\Engagebay\Http;

use Aika\Engagebay\Transactions\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class EngagebayClient
{
    const BASE_URI = 'https://app.engagebay.com/';

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_ALLOWED = [
        self::METHOD_GET,
        self::METHOD_POST,
        self::METHOD_PUT,
        self::METHOD_DELETE,
    ];

    const MIME_JSON = 'application/json';

    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function get(string $uri, ClientOption $options): Result
    {
        return self::request(self::METHOD_GET, $uri, $options);
    }

    public function post(string $uri, ClientOption $options): Result
    {
        return self::request(self::METHOD_POST, $uri, $options);
    }

    public function put(string $uri, ClientOption $options): Result
    {
        return self::request(self::METHOD_PUT, $uri, $options);
    }

    public function delete(string $uri, ClientOption $options): Result
    {
        return self::request(self::METHOD_DELETE, $uri, $options);
    }

    public function request(string $method, string $uri, ClientOption $options): Result
    {
        $method = strtoupper($method);

        if (!in_array($method, self::METHOD_ALLOWED)) {

            return new Result(
                Result::REFUSED,
                sprintf('Method `%s` not allowed!', $method)
            );
        }

        $options
            ->addHeader('Authorization', $this->apiKey)
            ->addHeader('Accept', self::MIME_JSON);

        try {
            var_dump(['uri' => $uri, 'options' => $options->getOptions()]); 

            $response = $this->execute($method, $uri, $options->getOptions());
            
            if ($response->getStatusCode() != 200) {

                return new Result(
                    Result::FAILED,
                    sprintf(
                        '[Failed] - Unable to perform `%s` request on `%s`: %s',
                        $method,
                        $uri,
                        $response->getReasonPhrase() .' [' . $response->getBody()->getContents() . ']'
                    )
                );
            }

            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            return new Result(
                Result::DONE,
                sprintf('`%s` request on `%s` successful', $method, $uri),
                $data
            );

        } catch(ClientException $ex) {
            
            return new Result(
                Result::WARNING,
                sprintf('[Client Exception] - %s', $ex->getMessage()),
                $ex->getResponse()
            );
        } catch (BadResponseException $ex) {
            
            return new Result(
                Result::WARNING,
                sprintf('[Bad Response Exception] - %s', $ex->getMessage()),
                $ex->getResponse()
            );
        } catch (GuzzleException $ex) {
            
            return new Result(
                Result::WARNING,
                sprintf('[Guzzle Exception] - %s', $ex->getMessage()),
                $ex->getTrace()
            );
        } catch (Throwable $ex) {
            
            return new Result(
                Result::ERROR,
                sprintf('[Error] - %s', $ex->getMessage()),
                $ex->getTrace()
            );
        }
    }

    // PRIVATE METHODS
    private function execute(string $method, string $uri, array $options): ResponseInterface
    {
        $client = new Client();
        var_dump($options);

        if (self::METHOD_POST == $method) return $client->post($uri, $options);

        if (self::METHOD_PUT == $method) return $client->put($uri, $options);

        if (self::METHOD_DELETE == $method) return $client->delete($uri, $options);

        return $client->get($uri, $options);
    }
}