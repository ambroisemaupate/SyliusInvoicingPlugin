<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver;

use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;

interface InvoiceNumberResolverInterface
{
    /**
     * @param InvoiceInterface $invoice
     *
     * @return string
     */
    public function generateInvoiceNumber(InvoiceInterface $invoice): string;
}