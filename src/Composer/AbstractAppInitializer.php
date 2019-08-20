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

    private $_foregroundColors = [
        'black' => '0;30',
        'blue' => '0;34',
        'green' => '0;32',
        'red' => '0;31',
        'yellow' => '1;33',
        'white' => '1;37',
    ];
    private $_backgroundColors = [
        'black' => '40',
        'red' => '41',
        'green' => '42',
        'yellow' => '43',
    ];

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

    /**
     * Print error message.
     *
     * @param string $message
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function printError(string $message): void
    {
        $this->printMessage($message, 'red', 'black');
    }

    /**
     * Print success messages.
     *
     * @param string $message
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function printSuccess(string $message): void
    {
        $this->printMessage($message, 'green', 'black');
    }

    /**
     * Print colored message.
     *
     * @param string      $message
     * @param string|null $foregroundColor
     * @param string|null $backgroundColor
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function printMessage(string $message, ?string $foregroundColor = null, ?string $backgroundColor = null): void
    {
        $colored_string = "";

        // Check if given foreground color found
        if (isset($this->foreground_colors[$foregroundColor])) {
            $colored_string .= "\033[" . $this->_foregroundColors[$foregroundColor] . "m";
        }
        // Check if given background color found
        if (isset($this->background_colors[$backgroundColor])) {
            $colored_string .= "\033[" . $this->_backgroundColors[$backgroundColor] . "m";
        }

        // Add string and end coloring
        $colored_string .=  $message . "\033[0m";

        echo $colored_string;
    }
}