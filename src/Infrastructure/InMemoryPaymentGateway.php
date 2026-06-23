<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Infrastructure;

use Thephpcc\EventFlow\Domain\Money;
use Thephpcc\EventFlow\Domain\PaymentGateway;
use Thephpcc\EventFlow\Domain\PaymentResult;

/**
 * In-memory adapter for the PaymentGateway port.
 *
 * It does not talk to a real payment provider: it records the amounts it was
 * asked to collect and returns a preconfigured result. This makes both the
 * happy path and the failure path of a purchase reproducible in local
 * development, demos, and tests without any external dependency.
 */
final class InMemoryPaymentGateway implements PaymentGateway
{
    /**
     * @var list<Money>
     */
    private array $collectedPayments = [];

    public function __construct(
        private readonly PaymentResult $paymentResult = PaymentResult::Approved,
    ) {
    }

    public function pay(Money $money): PaymentResult
    {
        $this->collectedPayments[] = $money;

        return $this->paymentResult;
    }

    /**
     * @return list<Money>
     */
    public function collectedPayments(): array
    {
        return $this->collectedPayments;
    }
}
