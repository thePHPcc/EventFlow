<?php declare(strict_types=1);
namespace Thephpcc\EventFlow\Domain;

/**
 * The outcome of an attempt to collect payment through a PaymentGateway.
 */
enum PaymentResult
{
    case Approved;

    case Declined;
}
