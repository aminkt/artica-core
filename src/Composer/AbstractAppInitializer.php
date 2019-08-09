<?php


namespace Artica\Composer;

/**
 * Class AbstractAppInitializer
 * Base class for project initializers.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package Artica\Composer
 */
abstract class AbstractAppInitializer
{
    protected $vendorPath;
    protected $projectPath;

    public function __construct()
    {
        $this->initApplication();
    }

    /**
     * Return project vendor paths.
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function getVendorPath(): string
    {
        if ($this->vendorPath) {
            return $this->vendorPath;
        }

        if (preg_match('/vendor/i', $this->getProjectPath())) {
            $this->vendorPath = dirname(dirname($this->getProjectPath()));
        } else {
            $this->vendorPath = $this->getProjectPath() . DIRECTORY_SEPARATOR . 'vendor';
        }

        return $this->vendorPath;
    }

    /**
     * Return project path.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public abstract function getProjectPath();

    /**
     * Init application.
     *
     * @return mixed
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public abstract function initApplication();

    /**
     * Get relative path of files in project and return full path based on project location.
     *
     * @param string $file
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function relativePath(string $file): string
    {
        return $this->getProjectPath() . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * Used to change framework configurations by giving path.
     *
     * @param string $file
     * @param string $key
     * @param string $val
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function changeFrameworkConfig(string $file, string $key, string $val): void
    {
        $content = preg_replace('/([\'"]' . $key . '[\'"]\s*=>\s*)([\'"].*[\'|"])/', "\\1'$val'", file_get_contents($file));
        file_put_contents($file, $content);
    }
}