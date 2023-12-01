<?php

namespace ConventionalChangelog\Helper;

class ShellCommand
{
    /**
     * Run shell command on working dir.
     */
    public static function exec(string $string): string
    {
        $value = shell_exec($string);

        return Formatter::clean((string)$value);
    }

    /**
     * Check if command exists.
     *
     * @return bool
     */
    public static function exists(string $command)
    {
        $whereIsCommand = (PHP_OS === 'WINNT') ? 'where' : 'which';
        $checkCommand = sprintf($whereIsCommand . ' %s', escapeshellarg($command));
        $return = self::exec($checkCommand);

        return !empty($return);
    }

    /**
     * Check if can execute shell commands.
     */
    public static function isEnabled(): bool
    {
        return function_exists('shell_exec') &&
            is_callable('shell_exec') &&
            stripos(ini_get('disable_functions'), 'shell_exec') === false;
    }
}
