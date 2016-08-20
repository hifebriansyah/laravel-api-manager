<?php

namespace MFebriansyah\LaravelAPIManager\Traits;

trait Hash
{
    /**
     * Hash string.
     *
     * @param string $string
     * @param int    $random
     *
     * @return string
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
     * Hash string.
     *
     * @param string $string
     * @param string $toCompare
     *
     * @return bool
     */
    public static function compareHash($string, $toCompare)
    {
        $random = substr($toCompare, 0, 2);
        $hash = self::toHash($string, $random);

        return $hash == $toCompare;
    }
}
