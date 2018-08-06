<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver;

use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use Sylius\Component\Channel\Model\ChannelInterface;

final class IncrementalInvoiceNumberResolver implements InvoiceNumberResolverInterface
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
    private function getInvoiceCountForCurrentYearAndChannel(ChannelInterface $channel)
    {
        $count = $this->invoiceRepository->countByYearAndChannel(new \DateTime(), $channel);

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
        if (null === $invoice->getOrder()->getCheckoutCompletedAt()) {
            throw new \RuntimeException('Cannot generate invoice if checkout was never completed.');
        }

        $tokens = [
            $invoice->getOrder()->getCheckoutCompletedAt()->format('Y'),
            sprintf('%09d', $this->getInvoiceCountForCurrentYearAndChannel($invoice->getOrder()->getChannel())),
        ];

        return implode('-', $tokens);
    }
}
