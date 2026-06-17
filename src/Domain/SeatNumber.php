<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

use function preg_match;

/**
 * Identifies a single seat within an event's seating plan, for example "A12".
 *
 * A seat number consists of a row, denoted by a single uppercase letter, and a
 * position within that row, denoted by a positive integer.
 *
 * This value object is fully implemented on purpose: it is the reference for
 * the style in which the value objects, aggregates, and tests of this project
 * are written.
 */
final readonly class SeatNumber
{
    public static function fromString(string $value): self
    {
        if (preg_match('/^([A-Z])([1-9]\d*)$/', $value, $matches) !== 1) {
            throw InvalidSeatNumberException::from($value);
        }

        return new self($matches[1], (int) $matches[2]);
    }

    private function __construct(
        private string $row,
        private int $position,
    ) {
    }

    public function row(): string
    {
        return $this->row;
    }

    public function position(): int
    {
        return $this->position;
    }

    public function asString(): string
    {
        return $this->row . $this->position;
    }

    public function equals(self $other): bool
    {
        return $this->row === $other->row && $this->position === $other->position;
    }
}
