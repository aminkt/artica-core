<?php
declare(strict_types=1);

namespace Artica\Commands;

use yii\console\Controller;

/**
 * Class BaseCommand
 * Base command class for Artica commands.
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 * @package HangApp\AccountService\Commands
 *
 */
class BaseCommand extends Controller
{
    /**
     * Stdout message in new line.
     *
     * @param string $message
     *
     * @return bool|int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @see    \Artica\Commands\BaseCommand::stdout()
     *
     */
    public function stdoutLn($message)
    {
        $return = parent::stdout($message);
        echo PHP_EOL;
        return $return;
    }

    /**
     * Stdout error message in new line.
     *
     * @param $string
     *
     * @return bool|int
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @see    \Artica\Commands\BaseCommand::stdout()
     */
    public function stderrLn($string)
    {
        $return = parent::stderr($string);
        echo PHP_EOL;
        return $return;
    }
}
