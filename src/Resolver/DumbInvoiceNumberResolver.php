<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver;

use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;

final class DumbInvoiceNumberResolver implements InvoiceNumberResolverInterface
{
    /**
     * @inheritDoc
     */
    public function generateInvoiceNumber(InvoiceInterface $invoice): string
    {
        if (null === $invoice->getOrder()) {
            throw new \RuntimeException('Cannot generate invoice number from inexistant order.');
        }
        if (null === $invoice->getOrder()->getCheckoutCompletedAt()) {
            throw new \RuntimeException('Cannot generate invoice if checkout was never completed.');
        }

        $tokens = [
            $invoice->getOrder()->getCheckoutCompletedAt()->format('Y'),
            $invoice->getOrder()->getNumber(),
        ];

        return implode('-', $tokens);
    }
}
