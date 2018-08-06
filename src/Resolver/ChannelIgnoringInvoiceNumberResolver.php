<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver;

use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

final class ChannelIgnoringInvoiceNumberResolver implements InvoiceNumberResolverInterface
{
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;

    /**
     * @param InvoiceRepositoryInterface $invoiceRepository
     */
    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @param ChannelInterface $channel
     *
     * @return int
     */
    private function getInvoiceCountForCurrentYear()
    {
        $count = $this->invoiceRepository->countByYear(new \DateTime());

        return $count + 1;
    }

    /**
     * @inheritDoc
     */
    public function generateInvoiceNumber(InvoiceInterface $invoice): string
    {
        if (null === $invoice->getOrder()) {
            throw new \RuntimeException('Cannot generate invoice number from inexistant order.');
        }

        $tokens = [
            $invoice->getOrder()->getCheckoutCompletedAt()->format('Y'),
            sprintf('"%09d"', $this->getInvoiceCountForCurrentYear()),
        ];

        return implode('-', $tokens);
    }
}
