<?php
/**
 * Created by PhpStorm.
 * User: vaidas
 * Date: 15/10/2016
 * Time: 01:29
 */

namespace Twbot\Factory;


use Twbot\Entity\Account;

class ImageFactory
{
    /**
     * @param Account $account
     * @return string
     */
    public static function getRandomImage($account)
    {
        // check if folder exists

        $images = glob(MEDIA_DIR . $account->getMediaDir() . '/*.{jpg,gif,png}', GLOB_BRACE);

        return $images[array_rand($images)];
    }
}