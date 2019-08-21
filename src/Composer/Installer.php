<?php

namespace Artica\Composer;

use yii\composer\Installer as YiiInstaller;

/**
 * Class Composer
 * Composer commands.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Lip
 *
 */
class Installer extends YiiInstaller
{
    public static $vendorDirectory;

    /**
     * Special method to run tasks defined in `[extra][yii\composer\Installer::postCreateProject]` key in `composer.json`
     *
     * @param Event $event
     */
    public static function postCreateProject($event)
    {
        static::runCommands($event, __METHOD__);
    }

    /**
     * Special method to run tasks defined in `[extra][yii\composer\Installer::postInstall]` key in `composer.json`
     *
     * @param Event $event
     * @since 2.0.5
     */
    public static function postInstall($event)
    {
        static::runCommands($event, __METHOD__);
    }

    /**
     * Special method to run tasks defined in `[extra][$extraKey]` key in `composer.json`
     *
     * @param Event $event
     * @param string $extraKey
     * @since 2.0.5
     */
    protected static function runCommands($event, $extraKey)
    {
        Installer::$vendorDirectory = $event->getComposer()->getConfig()->get('vendor-dir');
        $params = $event->getComposer()->getPackage()->getExtra();
        if (isset($params[$extraKey]) && is_array($params[$extraKey])) {
            foreach ($params[$extraKey] as $method => $args) {
                call_user_func_array([__CLASS__, $method], (array) $args);
            }
        }
    }

    public static function initApplication(): void
    {
        $configs = func_get_args();
        $configs = $configs[0];
        $serviceClassNames = $configs['service_initializers'] ?? [];
        foreach ($serviceClassNames as $serviceClassName) {
            echo ">> Run {$serviceClassName}\n";
            echo "------------------------------------------------------\n";
            new $serviceClassName();
        }
    }
}