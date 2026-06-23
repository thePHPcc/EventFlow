<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

/**
 * Gives an aggregate the ability to record the domain events that happen to it,
 * so that they can be released and published once its state has been persisted.
 *
 * An aggregate `use`s this trait and calls recordThat() from within its
 * behaviour; a collaborator calls releaseRecordedEvents() afterwards to obtain
 * the events and hand them on.
 */
trait RecordsDomainEvents
{
    /**
     * @var list<DomainEvent>
     */
    private array $recordedEvents = [];

    /**
     * @return list<DomainEvent>
     */
    public function releaseRecordedEvents(): array
    {
        $recordedEvents = $this->recordedEvents;

        $this->recordedEvents = [];

        return $recordedEvents;
    }

    private function recordThat(DomainEvent $domainEvent): void
    {
        $this->recordedEvents[] = $domainEvent;
    }
}
