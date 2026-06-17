<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

use function sprintf;
use Iterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SeatNumber::class)]
#[CoversClass(InvalidSeatNumberException::class)]
final class SeatNumberTest extends TestCase
{
    /**
     * @return Iterator<string, array{string}>
     */
    public static function invalidValues(): Iterator
    {
        yield 'empty string' => [''];

        yield 'lowercase row' => ['a12'];

        yield 'missing position' => ['A'];

        yield 'missing row' => ['12'];

        yield 'zero position' => ['A0'];

        yield 'leading zero in position' => ['A01'];

        yield 'more than one row letter' => ['AB12'];

        yield 'leading whitespace' => [' A12'];

        yield 'trailing characters' => ['A12X'];
    }

    public function testCanBeCreatedFromItsStringRepresentation(): void
    {
        $seatNumber = SeatNumber::fromString('A12');

        $this->assertSame('A', $seatNumber->row());
        $this->assertSame(12, $seatNumber->position());
    }

    public function testCanBeRepresentedAsString(): void
    {
        $this->assertSame('A12', SeatNumber::fromString('A12')->asString());
    }

    #[DataProvider('invalidValues')]
    public function testCannotBeCreatedFromAnInvalidStringRepresentation(string $value): void
    {
        $this->expectException(InvalidSeatNumberException::class);
        $this->expectExceptionMessageIs(sprintf('"%s" is not a valid seat number', $value));

        SeatNumber::fromString($value);
    }

    public function testIsEqualToAnotherSeatNumberWithTheSameRowAndPosition(): void
    {
        $this->assertTrue(
            SeatNumber::fromString('A12')->equals(SeatNumber::fromString('A12')),
        );
    }

    public function testIsNotEqualToAnotherSeatNumberInADifferentRow(): void
    {
        $this->assertFalse(
            SeatNumber::fromString('A12')->equals(SeatNumber::fromString('B12')),
        );
    }

    public function testIsNotEqualToAnotherSeatNumberAtADifferentPosition(): void
    {
        $this->assertFalse(
            SeatNumber::fromString('A12')->equals(SeatNumber::fromString('A13')),
        );
    }
}
