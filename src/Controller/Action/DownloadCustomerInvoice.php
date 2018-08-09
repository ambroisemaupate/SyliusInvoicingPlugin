<?php

namespace BitBag\SyliusInvoicingPlugin\Controller\Action;


use Sylius\Behat\Context\Ui\Shop\AccountContext;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceFileResolverInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class DownloadCustomerInvoice
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private $invoiceRepository;

    /**
     * @var InvoiceFileResolverInterface
     */
    private $invoiceFileResolver;

    /**
     * @var CustomerContextInterface
     */
    private $customerContext;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param InvoiceFileResolverInterface $invoiceFileResolver
     * @param CustomerContextInterface $customerContext
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        InvoiceFileResolverInterface $invoiceFileResolver,
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceFileResolver = $invoiceFileResolver;
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param int $orderId
     *
     * @return BinaryFileResponse
     */
    public function __invoke(string $orderNumber): BinaryFileResponse
    {
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new InsufficientAuthenticationException();
        }
        /** @var Order $order */
        $order = $this->orderRepository->findOneByNumberAndCustomer($orderNumber, $customer);
        if (null === $order) {
            throw new NotFoundHttpException('Order does not exist');
        }

        $invoice = $this->invoiceRepository->findByOrderId($order->getId());
        $response = new BinaryFileResponse($this->invoiceFileResolver->resolveInvoicePath($invoice));

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
