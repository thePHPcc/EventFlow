<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Infrastructure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Thephpcc\EventFlow\Domain\Currency;
use Thephpcc\EventFlow\Domain\Money;
use Thephpcc\EventFlow\Domain\PaymentResult;

#[CoversClass(InMemoryPaymentGateway::class)]
#[CoversClass(Money::class)]
#[CoversClass(Currency::class)]
#[CoversClass(PaymentResult::class)]
#[TestDox('InMemoryPaymentGateway')]
final class InMemoryPaymentGatewayTest extends TestCase
{
    public function testApprovesPaymentsByDefault(): void
    {
        $inMemoryPaymentGateway = new InMemoryPaymentGateway;

        $this->assertSame(PaymentResult::Approved, $inMemoryPaymentGateway->pay($this->money(1000)));
    }

    public function testCanBeConfiguredToDeclinePayments(): void
    {
        $inMemoryPaymentGateway = new InMemoryPaymentGateway(PaymentResult::Declined);

        $this->assertSame(PaymentResult::Declined, $inMemoryPaymentGateway->pay($this->money(1000)));
    }

    public function testRecordsTheAmountsItWasAskedToCollectInOrder(): void
    {
        $inMemoryPaymentGateway = new InMemoryPaymentGateway;
        $money                  = $this->money(1000);
        $second                 = $this->money(2000);

        $inMemoryPaymentGateway->pay($money);
        $inMemoryPaymentGateway->pay($second);

        $this->assertSame([$money, $second], $inMemoryPaymentGateway->collectedPayments());
    }

    public function testHasNotCollectedAnyPaymentsInitially(): void
    {
        $this->assertSame([], (new InMemoryPaymentGateway)->collectedPayments());
    }

    private function money(int $amount): Money
    {
        return Money::from($amount, 'EUR');
    }
}
