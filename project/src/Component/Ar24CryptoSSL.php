<?php

/**
 * This file defines the Ar24 Crypto ssl component
 *
 * @category App
 * @package Component
 * @author Montassar Bouali <montassar.bouali@softeam.fr>
 * @copyright 2023-2024 Softteam
 */

namespace App\Component;

/**
 * Class Ar24CryptoSSL
 * @package App\Component
 */
class Ar24CryptoSSL
{
    /**
     * Generate a signature
     *
     * @param string $currentDate
     * @param string $decryptionKey
     * @return string
     */
    public function generateSignature(string $currentDate, string $decryptionKey)
    {
        $hashedPrivateKey = hash('sha256', $decryptionKey);
        $iv = mb_strcut(hash('sha256', $hashedPrivateKey), 0, 16, 'UTF-8');

        return openssl_encrypt(
            $currentDate,
            'aes-256-cbc',
            $hashedPrivateKey,
            false,
            $iv);
    }

    /**
     * Decrypt a response
     *
     * @param string $encryptedResponse
     * @param string $currentDate
     * @param string $decryptionKey
     * @return string
     */
    public function decryptResponse(string $encryptedResponse, string $currentDate, string $decryptionKey)
    {
        $key = hash('sha256', $currentDate . $decryptionKey);
        $iv = mb_strcut(hash('sha256', hash('sha256', $decryptionKey)),
            0, 16, 'UTF-8');

        return openssl_decrypt(
            $encryptedResponse,
            'aes-256-cbc',
            $key,
            false,
            $iv);
    }
}