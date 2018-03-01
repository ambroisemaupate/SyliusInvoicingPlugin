<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver\State;

use BitBag\SyliusInvoicingPlugin\Entity\Invoice;
use BitBag\SyliusInvoicingPlugin\Repository\CompanyDataRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceFileResolverInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceNumberResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class InvoiceResolver implements InvoiceResolverInterface
{
    /**
     * @var CompanyDataRepositoryInterface
     */
    private $companyDataRepository;

    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var EntityManagerInterface
     */
    private $invoiceEntityManager;

    /**
     * @var InvoiceFileResolverInterface
     */
    private $invoiceFileResolver;

    /**
     * @var InvoiceNumberResolverInterface
     */
    private $invoiceNumberResolver;

    /**
     * @param CompanyDataRepositoryInterface $companyDataRepository
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param EntityManagerInterface $invoiceEntityManager
     * @param InvoiceFileResolverInterface $invoiceFileResolver
     * @param InvoiceNumberResolverInterface $invoiceNumberResolver
     */
    public function __construct(
        CompanyDataRepositoryInterface $companyDataRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        EntityManagerInterface $invoiceEntityManager,
        InvoiceFileResolverInterface $invoiceFileResolver,
        InvoiceNumberResolverInterface $invoiceNumberResolver
    ) {
        $this->companyDataRepository = $companyDataRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceEntityManager = $invoiceEntityManager;
        $this->invoiceFileResolver = $invoiceFileResolver;
        $this->invoiceNumberResolver = $invoiceNumberResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function generateInvoice(OrderInterface $order): void
    {
        $companyData = $this->companyDataRepository->findCompanyDataByChannel($order->getChannel());

        if (null === $companyData || false === $companyData->getGenerateInvoiceAfterPaymentSuccess()) {
            return;
        }

        $invoice = $this->invoiceRepository->findByOrderId($order->getId()) ?: new Invoice();

        $invoice->setOrder($order);
        $invoice->setNumber($this->invoiceNumberResolver->generateInvoiceNumber($invoice));
        $this->invoiceEntityManager->persist($invoice);
        $this->invoiceFileResolver->resolveInvoicePath($invoice);

        //TODO: Use service to send customer email with invoice attached
    }
}
