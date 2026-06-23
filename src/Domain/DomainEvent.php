<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

/**
 * Records that something of significance happened in the domain.
 *
 * This is a marker interface and the minimal foundation for the domain events
 * of this project: implement it on an immutable event object such as a
 * SeatReserved and record it from within an aggregate using the
 * RecordsDomainEvents trait.
 */
interface DomainEvent
{
}
