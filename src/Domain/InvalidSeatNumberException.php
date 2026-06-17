<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

use function sprintf;
use Exception as PhpException;

final class InvalidSeatNumberException extends PhpException implements Exception
{
    public static function from(string $value): self
    {
        return new self(
            sprintf('"%s" is not a valid seat number', $value),
        );
    }
}
