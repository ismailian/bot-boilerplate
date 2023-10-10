<?php

namespace PhpApi\Core\Functions;

use Exception;
use PhpApi\Core\Config;

class Logger
{

    /**
     * log exception
     *
     * @param Exception $exception
     * @param array $extra
     * @return void
     */
    public static function exception(Exception $exception, array $extra = []): void
    {
        $logPath = (new Config())->path()->logs;
        $filename = $logPath . date('Y-m-d_H-i-s') . '.json';
        file_put_contents($filename, json_encode([
            'type' => 'error',
            'date' => date('Y-m-d H:i:s A'),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            ...$extra
        ], JSON_UNESCAPED_SLASHES));
    }

    /**
     * log data
     *
     * @param array $data
     * @param bool $addDate
     * @return void
     */
    public static function log(array $data, bool $addDate = true): void
    {
        $logPath = (new Config())->path()->logs;
        $filename = $logPath . date('Y-m-d_H-i-s') . '.json';

        if ($addDate) {
            $data['date'] = date('Y-m-d H:i:s A');
        }

        file_put_contents($filename, json_encode($data, JSON_UNESCAPED_SLASHES));
    }

}