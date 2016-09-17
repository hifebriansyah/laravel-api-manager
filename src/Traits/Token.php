<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiFebriansyah\LaravelAPIManager\Traits;

/**
 * Trait responsible to manage token functionality.
 *
 * @author Muhammad Febriansyah <hifebriansyah@gmail.com>
 *
 * @since Trait available since Release 1.0.0
 */
trait Token
{
    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Generate unique token.
     *
     * @param string $key (optional)
     *
     * @return bool
     *
     * @since Property available since Release 1.0.0
     */
    public static function create($key = TOKEN_KEY)
    {
        $encryptedText = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, NOW, MCRYPT_MODE_ECB)); // encrypt with AES 128

        return $encryptedText;
    }

    /**
     * Check token validity.
     *
     * @param string $encryptedText
     * @param string $key           (optional)
     *
     * @return bool
     *
     * @since Property available since Release 1.0.0
     */
    public static function check($encryptedText, $key = TOKEN_KEY)
    {
        $access = false;

        if ($encryptedText == DEBUG_TOKEN_KEY) {
            $access = true;
        } else {
            $clientTimeStamp = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($encryptedText), MCRYPT_MODE_ECB); // decrypt with AES 128
            $timestampDiff = NOW - $clientTimeStamp;

            if ($timestampDiff <= TOKEN_TIME) {
                $access = true;
            }
        }

        return $access;
    }
}
