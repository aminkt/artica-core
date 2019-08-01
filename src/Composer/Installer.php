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
    static $vendorDirectory;
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

        $dotenv = \Dotenv\Dotenv::create(dirname(Installer::$vendorDirectory));
        $dotenv->load();

        Installer::initApp($configs['app_config_file']);
        Installer::initDb($configs['db_config_file']);
        Installer::initTestDb($configs['db_test_config_file']);
        Installer::initRedis();
        Installer::initElasticSearch();
    }

    private static function initApp(string $configFile): void
    {
        Installer::changeFrameworkConfig($configFile, 'language', getenv('APP_LANGUAGE'));
        echo "   Application configuration finished.\n";
    }

    private static function initDb(string $configFile): void
    {
        $dsn = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE');
        $content = "
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => '" . $dsn . "',
    'username' => '" . getenv('DB_USERNAME') . "',
    'password' => '" . getenv('DB_PASSWORD') . "',
    'tablePrefix' => '" . getenv('DB_TABLE_PREFIX') . "',
    'charset' => '" . getenv('DB_CHARSET') . "',

    // Schema cache options (for production environment)
    'enableSchemaCache' => " . getenv('DB_ENABLE_SCHEMA_CACHE') . ",
    'schemaCacheDuration' => " . getenv('DB_SCHEMA_CACHE_DURATION') . ",
    'schemaCache' => '" . getenv('DB_SCHEMA_CACHE') . "',
];

";
        file_put_contents($configFile, $content);
        echo "   Database configuration finished.\n";
    }

    private static function initTestDb(string $configFile): void
    {
        $dsn = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_TEST_DATABASE');
        $content = "
<?php
\$db = require dirname(__DIR__) . '/db.php';
// test database! Important not to run tests on production or development databases
\$db['dsn'] = '".$dsn."';

return \$db;
";
        file_put_contents($configFile, $content);
        echo "   Database test configuration finished.\n";
    }

    private static function initRedis()
    {
        echo ("Redis configuration implemented");
    }

    private static function initElasticSearch()
    {
        echo ("Es configuration Not implemented");
    }

    private static function changeFrameworkConfig(string $file, string $key, string $val): void
    {
        $content = preg_replace('/([\'"]' . $key . '[\'"]\s*=>\s*)([\'"].*[\'|"])/', "\\1'$val'", file_get_contents($file));
        file_put_contents($file, $content);
    }
}