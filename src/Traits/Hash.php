<?php

/**
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HiFebriansyah\LaravelAPIManager\Traits;

/**
 * Trait responsible to manage hash functionality.
 *
 * @author Muhammad Febriansyah <hifebriansyah@gmail.com>
 *
 * @since Trait available since Release 1.0.0
 */
trait Hash
{
    /*
    |--------------------------------------------------------------------------
    | METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Convert string to hash.
     *
     * @param string $string
     * @param int    $random (optional)
     *
     * @return string
     *
     * @todo add salt
     *
     * @since Property available since Release 1.0.0
     */
    public static function toHash($string, $random = null)
    {
        $random = ($random) ? $random : rand(10, 30);
        $string = md5($string);
        $start = md5(substr($string, 0, $random));
        $end = md5(substr($string, $random, 99));
        $hash = $random.$start.$end;

        return $hash;
    }

    /**
     * Compare string to hash.
     *
     * @param string $string
     * @param string $toCompare
     *
     * @return bool
     *
     * @since Property available since Release 1.0.0
     */
    public static function compareHash($string, $toCompare)
    {
        $random = substr($toCompare, 0, 2);
        $hash = self::toHash($string, $random);

        return $hash == $toCompare;
    }
}
