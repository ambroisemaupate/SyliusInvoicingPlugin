<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver\State;

use Sylius\Component\Core\Model\OrderInterface;

interface InvoiceResolverInterface
{
    /**
     * @param OrderInterface $order
     */
    public function generateInvoice(OrderInterface $order): void;
}