<?php
/**
 * Created by PhpStorm.
 * User: vaidas
 * Date: 14/10/2016
 * Time: 20:13
 */

namespace Twbot;


use Abraham\TwitterOAuth\TwitterOAuth;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Twbot\Entity\Account;

class Factory
{
    /**
     * @param Account $account
     * @return TwitterOAuth
     */
    public static function getTwitterOAuth($account)
    {
        return new TwitterOAuth(
            $account->getConsumerKey(),
            $account->getConsumerToken(),
            $account->getAccessToken(),
            $account->getAccessSecret()
        );
    }

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

    /**
     * @return Logger
     */
    public static function getDefaultErrorHandler()
    {
        $logger = self::getLogger('default');

        return new Error($logger);
    }

    /**
     * @param string $logName
     * @return Logger
     */
    public static function getLogger($logName)
    {
        $logger = new Logger($logName);

        $handler = new RotatingFileHandler(LOG_DIR . "$logName.log");
        $handler->setFormatter(new JsonFormatter());

        $logger->pushHandler($handler);

        return $logger;
    }
}