<?php

namespace BitBag\SyliusInvoicingPlugin\Resolver\State;

use BitBag\SyliusInvoicingPlugin\Entity\Invoice;
use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;
use BitBag\SyliusInvoicingPlugin\Repository\CompanyDataRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceFileResolverInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceNumberResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class InvoiceResolver implements InvoiceResolverInterface
{
    /**
     * @var SenderInterface
     */
    private $sender;
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
     * @param SenderInterface $sender
     */
    public function __construct(
        CompanyDataRepositoryInterface $companyDataRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        EntityManagerInterface $invoiceEntityManager,
        InvoiceFileResolverInterface $invoiceFileResolver,
        InvoiceNumberResolverInterface $invoiceNumberResolver,
        SenderInterface $sender
    ) {
        $this->companyDataRepository = $companyDataRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceEntityManager = $invoiceEntityManager;
        $this->invoiceFileResolver = $invoiceFileResolver;
        $this->invoiceNumberResolver = $invoiceNumberResolver;
        $this->sender = $sender;
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

        $invoiceFilePath = $this->invoiceFileResolver->resolveInvoicePath($invoice);

        $this->sendInvoiceEmail($invoice, $invoiceFilePath);
    }

    /**
     * @param InvoiceInterface $invoice
     * @param string $invoiceFilePath
     */
    private function sendInvoiceEmail(InvoiceInterface $invoice, string $invoiceFilePath)
    {
        $emails = [];
        if (null !== $invoice->getOrder()->getCustomer()) {
            $emails[] = $invoice->getOrder()->getCustomer()->getEmailCanonical();
        }
        if (null !== $invoice->getOrder()->getUser()) {
            $emails[] = $invoice->getOrder()->getUser()->getEmailCanonical();
        }

        $this->sender->send('invoice', $emails, [
            'order' => $invoice->getOrder(),
            'invoice' => $invoice
        ], [
            $invoiceFilePath
        ]);
    }
}
