<?php

namespace Artica\Lip\Composer;

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
    public static function initApplication(): void
    {
        $configs = func_get_args();

        $dotenv = \Dotenv\Dotenv::create(dirname(__DIR__));
        $dotenv->load();

        Composer::initApp($configs['app_config_file']);
        Composer::initDb($configs['db_config_file']);
        Composer::initRedis();
        Composer::initElasticSearch();
    }

    private static function initApp(string $configFile): void
    {
        $file = __DIR__ . '/api/config/main.php';
        Composer::changeFrameworkConfig($file, 'language', getenv('APP_LANGUAGE'));
        echo "   Application configuration finished.\n";
    }

    private static function initDb(string $configFile): void
    {
        $file = dirname(__FILE__) . '/common/config/conf.d/db.php';
        $dsn = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE');
        changeFrameworkConfig($file, 'dsn', $dsn);
        changeFrameworkConfig($file, 'username', getenv('DB_USERNAME'));
        changeFrameworkConfig($file, 'password', getenv('DB_PASSWORD'));
        changeFrameworkConfig($file, 'tablePrefix', getenv('DB_TABLE_PREFIX'));
        echo "   Database configuration finished.\n";
    }

    private static function initRedis()
    {
        printError("Redis configuration implemented");
    }

    private static function initElasticSearch()
    {
        printError("Es configuration Not implemented");
    }

    private static function changeFrameworkConfig(string $file, string $key, string $val): void
    {
        $content = preg_replace('/([\'"]' . $key . '[\'"]\s*=>\s*)([\'"].*[\'|"])/', "\\1'$val'", file_get_contents($file));
        file_put_contents($file, $content);
    }
}