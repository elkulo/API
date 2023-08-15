<?php
declare(strict_types=1);

function console(string $message, string|int $level = 1): void
{
    switch ($level) {
        case 'error':
        case 4:
            \ChromePhp::error($message);
            break;
        case 'warning':
        case 'warn':
        case 3:
            \ChromePhp::warn($message);
            break;
        case 'info':
        case 2:
            \ChromePhp::info($message);
            break;
        case 'debug':
        case 'dump':
        case 'log':
        case 1:
        default:
            \ChromePhp::log($message);
            break;
    }
}
