<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

/**
 * Port through which the payment for a ticket is collected.
 *
 * This is an outbound port of the Domain: the Domain expresses that a certain
 * amount needs to be collected, while the concrete integration with a payment
 * provider lives as an adapter in the Infrastructure layer. The Domain therefore
 * depends on this abstraction, never on a concrete payment provider.
 */
interface PaymentGateway
{
    public function pay(Money $money): PaymentResult;
}
