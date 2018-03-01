<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver\State;

use BitBag\SyliusInvoicingPlugin\Repository\CompanyDataRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;

class InvoiceResolver
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CompanyDataRepositoryInterface $companyDataRepository,
        InvoiceRepositoryInterface $invoiceRepository
    ) {
        $this->companyDataRepository = $companyDataRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function generateInvoice(OrderInterface $order): void
    {
        $companyData = $this->companyDataRepository->findCompanyDataByChannel($order->getChannel());

        if (null === $companyData || false === $companyData->getGenerateInvoiceAfterPaymentSuccess()) {
            return;
        }

        // TODO: Create invoice with incremented number

    }
}
