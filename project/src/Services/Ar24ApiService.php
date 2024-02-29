<?php

/**
 * This file defines the Ar24 Api Service
 *
 * @category App
 * @package Component
 * @author Montassar Bouali <montassar.bouali@softeam.fr>
 * @copyright 2023-2024 Softteam
 */
namespace App\Services;

use App\Component\Ar24CryptoSSL;
use GuzzleHttp\Client;

/**
 * Class Ar24ApiService
 * @package App\Services
 */
class Ar24ApiService
{
    /**
     * GuzzleHttp client
     */
    private $client;

    /**
     * @var $token
     */
    private $token;

    /**
     * @var $decryptionKey
     */
    private $decryptionKey;

    /**
     * @var Ar24CryptoSSL
     */
    private $ar24CryptoSSL;

    /**
     * Ar24ApiService constructor.
     *
     * @param $config
     * @param string $token
     * @param string $decryptionKey
     * @param Ar24CryptoSSL $ar24CryptoSSL
     */
    public function __construct($config, string $token, string $decryptionKey,
                                Ar24CryptoSSL $ar24CryptoSSL)
    {
        $this->client = new  Client($config);
        $this->token = $token;
        $this->decryptionKey = $decryptionKey;
        $this->ar24CryptoSSL = $ar24CryptoSSL;
    }

    /**
     * Get a user info
     *
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserInfo(array $data)
    {
        try {
            $signature = $this->ar24CryptoSSL->generateSignature($data['date'], $this->decryptionKey);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // default params
        $params = array(
            'token' => $this->token
        );

        // headers
        $headers = array(
            'signature' => $signature
        );

        $request = $this->client->request('GET', "user", [
            'query' => array_merge($data, $params),
            'headers' => $headers
        ]);

        $statusCode = $request->getStatusCode();
        $content = $request->getBody()->getContents();
        $decodeContent = (array)json_decode($content);
        if (isset($decodeContent['status']) && $decodeContent['status'] === 'ERROR') {
            return array(
                'success' => false,
                'message' => $decodeContent['message'],
                'error' => isset($decodeContent['slug']) ? $decodeContent['slug'] : ""
            );
        }

        $response = json_decode($this->ar24CryptoSSL->decryptResponse($content, $data['date'], $this->decryptionKey));
        return array('success' => true, 'status' => $statusCode, 'data' => $response);
    }

    /**
     * Create a new user
     *
     * @param array $userData
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createUser(array $userData)
    {
        try {
            $signature = $this->ar24CryptoSSL->generateSignature($userData['date'], $this->decryptionKey);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // default params
        $params = array(
            'token' => $this->token
        );

        // headers
        $headers = array(
            'signature' => $signature
        );

        $request = $this->client->request('POST', "user", [
            'form_params' => array_merge($userData, $params),
            'headers' => $headers
        ]);

        $statusCode = $request->getStatusCode();
        $content = $request->getBody()->getContents();
        $decodeContent = (array)json_decode($content);
        if (isset($decodeContent['status']) && $decodeContent['status'] === 'ERROR') {
            return array(
                'success' => false,
                'message' => $decodeContent['message'],
                'error' => isset($decodeContent['slug']) ? $decodeContent['slug'] : ""
            );
        }

        $response = json_decode($this->ar24CryptoSSL->decryptResponse($content, $userData['date'], $this->decryptionKey));
        return array('success' => true, 'status' => $statusCode, 'data' => $response);
    }

    /**
     * Send a email to user
     *
     * @param array $data
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMail(array $data)
    {
        try {
            $signature = $this->ar24CryptoSSL->generateSignature($data['date'], $this->decryptionKey);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // default params
        $params = array(
            'token' => $this->token
        );

        // headers
        $headers = array(
            'signature' => $signature
        );

        $request = $this->client->request('POST', "mail", [
            'form_params' => array_merge($data, $params),
            'headers' => $headers
        ]);

        $statusCode = $request->getStatusCode();
        $content = $request->getBody()->getContents();
        $decodeContent = (array)json_decode($content);
        if (isset($decodeContent['status']) && $decodeContent['status'] === 'ERROR') {
            return array(
                'success' => false,
                'message' => $decodeContent['message'],
                'error' => isset($decodeContent['slug']) ? $decodeContent['slug'] : ""
            );
        }

        $response = json_decode($this->ar24CryptoSSL->decryptResponse($content, $data['date'], $this->decryptionKey));
        return array('success' => true, 'status' => $statusCode, 'data' => $response);
    }

    /**
     * Upload a attachment
     *
     * @param array $data
     * @param $file
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadAttachment(array $data,  $file)
    {
        try {
            $signature = $this->ar24CryptoSSL->generateSignature($data['date'], $this->decryptionKey);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // default params
        $params = [
            [
                'name' => 'token',
                'contents' =>  $this->token,
            ],
            [
                'name' => 'date',
                'contents' => $data['date'],
            ],
            [
                'name' => 'id_user',
                'contents' => 63161,
            ],
            [
                'name' => 'file',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ],
        ];

        // headers
        $headers = array(
            'signature' => $signature,
        );

        $request = $this->client->request('POST', "attachment/", [
            'multipart' => $params,
            'headers' => $headers
        ]);

        $statusCode = $request->getStatusCode();
        $content = $request->getBody()->getContents();
        $decodeContent = (array)json_decode($content);
        if (isset($decodeContent['status']) && $decodeContent['status'] === 'ERROR') {
            return array(
                'success' => false,
                'message' => $decodeContent['message'],
                'error' => isset($decodeContent['slug']) ? $decodeContent['slug'] : ""
            );
        }

        $response = json_decode($this->ar24CryptoSSL->decryptResponse($content, $data['date'], $this->decryptionKey));
        return array('success' => true, 'status' => $statusCode, 'data' => $response);
    }

    /**
     * Get a mail info
     *
     * @param array $data
     * @return array|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMailInfo(array $data)
    {
        try {
            $signature = $this->ar24CryptoSSL->generateSignature($data['date'], $this->decryptionKey);
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        // default params
        $params = array(
            'token' => $this->token
        );

        // headers
        $headers = array(
            'signature' => $signature
        );

        $request = $this->client->request('GET', "mail", [
            'query' => array_merge($data, $params),
            'headers' => $headers
        ]);

        $statusCode = $request->getStatusCode();
        $content = $request->getBody()->getContents();
        $decodeContent = (array)json_decode($content);
        if (isset($decodeContent['status']) && $decodeContent['status'] === 'ERROR') {
            return array(
                'success' => false,
                'message' => $decodeContent['message'],
                'error' => isset($decodeContent['slug']) ? $decodeContent['slug'] : ""
            );
        }

        $response = json_decode($this->ar24CryptoSSL->decryptResponse($content, $data['date'], $this->decryptionKey));
        return array('success' => true, 'status' => $statusCode, 'data' => $response);
    }
}