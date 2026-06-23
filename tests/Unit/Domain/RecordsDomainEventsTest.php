<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversTrait(RecordsDomainEvents::class)]
#[TestDox('RecordsDomainEvents')]
final class RecordsDomainEventsTest extends TestCase
{
    public function testHasNoRecordedDomainEventsInitially(): void
    {
        $aggregateThatRecordsDomainEvents = new AggregateThatRecordsDomainEvents;

        $this->assertSame([], $aggregateThatRecordsDomainEvents->releaseRecordedEvents());
    }

    public function testRecordsAndReleasesDomainEventsInOrder(): void
    {
        $aggregateThatRecordsDomainEvents = new AggregateThatRecordsDomainEvents;
        $first                            = new SomethingHappened;
        $second                           = new SomethingHappened;

        $aggregateThatRecordsDomainEvents->record($first);
        $aggregateThatRecordsDomainEvents->record($second);

        $this->assertSame([$first, $second], $aggregateThatRecordsDomainEvents->releaseRecordedEvents());
    }

    public function testReleasingRecordedDomainEventsClearsThem(): void
    {
        $aggregateThatRecordsDomainEvents = new AggregateThatRecordsDomainEvents;
        $aggregateThatRecordsDomainEvents->record(new SomethingHappened);

        $aggregateThatRecordsDomainEvents->releaseRecordedEvents();

        $this->assertSame([], $aggregateThatRecordsDomainEvents->releaseRecordedEvents());
    }
}

final class AggregateThatRecordsDomainEvents
{
    use RecordsDomainEvents;

    public function record(DomainEvent $domainEvent): void
    {
        $this->recordThat($domainEvent);
    }
}

final class SomethingHappened implements DomainEvent
{
}
