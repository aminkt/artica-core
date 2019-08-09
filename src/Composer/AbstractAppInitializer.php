<?php


namespace Artica\Composer;


abstract class AbstractAppInitializer
{
    protected $vendorPath;
    protected $servicePath;

    public function __construct()
    {
        $this->initApplication();
    }

    public function getVendorPath(): string
    {
        if ($this->vendorPath) {
            return $this->vendorPath;
        }

        if (preg_match('vendor', $this->servicePath)) {
            $this->vendorPath = dirname(dirname($this->servicePath));
        } else {
            $this->vendorPath = $this->getProjectPath() . DIRECTORY_SEPARATOR . 'vendor';
        }

        return $this->vendorPath;
    }

    public abstract function getProjectPath();

    public abstract function initApplication();

    public function relativePath(string $file): string
    {
        return $this->getProjectPath() . DIRECTORY_SEPARATOR . $file;
    }

    public static function changeFrameworkConfig(string $file, string $key, string $val): void
    {
        $content = preg_replace('/([\'"]' . $key . '[\'"]\s*=>\s*)([\'"].*[\'|"])/', "\\1'$val'", file_get_contents($file));
        file_put_contents($file, $content);
    }
}