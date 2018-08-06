<?php


namespace BitBag\SyliusInvoicingPlugin\Command;

use BitBag\SyliusInvoicingPlugin\Entity\Invoice;
use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepository;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceFileResolverInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\State\InvoiceResolver;
use BitBag\SyliusInvoicingPlugin\Resolver\State\InvoiceResolverInterface;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use BitBag\SyliusInvoicingPlugin\FileGenerator\FileGeneratorInterface;

class GenerateInvoiceCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('bitbag:invoice:generate')
            ->setDescription('Force invoice generation for an order')
            ->addArgument('order', InputArgument::REQUIRED, 'Order id');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FileGeneratorInterface $invoiceGenerator */
        $invoiceGenerator = $this->getContainer()->get('bitbag_sylius_invoicing_plugin.file_generator.invoice_file');
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('doctrine')->getManager()->getRepository(Invoice::class);
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->getContainer()->get('sylius.repository.order');
        /** @var Order|null $order */
        $order = $orderRepository->findOneById($input->getArgument('order'));

        if (null == $order) {
            throw new RuntimeException('Order <info>#'.$input->getArgument('order').'</info> does not exist.');
        }
        $invoice = $invoiceRepository->findByOrderId($order->getId());

        if (null !== $invoice) {
            $output->writeln('Invoice <info>#'.$invoice->getNumber().'</info> already exists for Order <info>#'.$order->getId().'</info>');
        } else {
            $output->writeln('No invoice found for Order <info>#'.$order->getId().'</info>. Generating one…');
            /** @var InvoiceResolverInterface $invoiceResolver */
            $invoiceResolver = $this->getContainer()->get('bitbag_sylius_invoicing_plugin.state_resolver.invoice');
            $invoice = $invoiceResolver->getInvoiceFromOrder($order);
            $this->getContainer()->get('doctrine')->getManager()->flush();
        }

        $invoiceGenerator->generateFile($invoice);

        /** @var InvoiceFileResolverInterfaceResolver $invoiceFileResolver */
        $invoiceFileResolver = $this->getContainer()->get('bitbag_sylius_invoicing_plugin.resolver.invoice_file');
        $output->writeln(sprintf(
            'Invoice file is located at: <info>%s</info>',
            realpath($invoiceFileResolver->resolveInvoicePath($invoice))
        ));
    }
}
