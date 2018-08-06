<?php


namespace BitBag\SyliusInvoicingPlugin\Command;

use BitBag\SyliusInvoicingPlugin\Entity\Invoice;
use Doctrine\ORM\QueryBuilder;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OrderInvoicesCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('bitbag:invoice:orders')
            ->setDescription('Get orders available for invoice generation')
            ->addOption('with-invoice', 'w', InputOption::VALUE_NONE, 'List only orders with existing invoice.')
            ->addOption('without-invoice', 'o', InputOption::VALUE_NONE, 'List only orders without existing invoice.')
            ->addOption('offset', null, InputOption::VALUE_REQUIRED, 'Offset to paginate results')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('doctrine')->getManager()->getRepository(Invoice::class);
        $table = new Table($output);
        $table->setHeaders(['Id', 'Number', 'State', 'Checkout completed at', 'Total', 'Invoice']);

        $offset = 0;
        if ($input->hasOption('offset') && $input->getOption('offset') > 0) {
            $offset = (int) $input->getOption('offset');
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getOrderQueryBuilder($input->getOption('with-invoice'), $input->getOption('without-invoice'));
        $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults(50)
        ;

        $countQueryBuilder = $this->getOrderQueryBuilder($input->getOption('with-invoice'), $input->getOption('without-invoice'));
        $countQueryBuilder->select($countQueryBuilder->expr()->count('o.id'));
        $output->writeln('<info>'.$countQueryBuilder->getQuery()->getSingleScalarResult().'</info> order(s) found:');

        $orders = $queryBuilder->getQuery()->getResult();
        /** @var OrderInterface $order */
        foreach ($orders as $order) {
            /** @var Invoice|null $invoice */
            $invoice = $invoiceRepository->findOneByOrder($order);
            $data = [
                $order->getId(),
                $order->getNumber(),
                $order->getState(),
                $order->getCheckoutCompletedAt()->format('Y-m-d H:i:s'),
                ($order->getTotal()/100.0) . ' ' . $order->getCurrencyCode()
            ];
            if (null !== $invoice) {
                $data[] = $invoice->getNumber();
            } else {
                $data[] = '<error>No invoice</error>';
            }

            $table->addRow($data);
        }

        $table->render();
    }

    /**
     * @param bool $withInvoice
     * @param bool $withoutInvoice
     *
     * @return QueryBuilder
     */
    protected function getOrderQueryBuilder($withInvoice = false, $withoutInvoice = false)
    {
        /** @var InvoiceRepository $invoiceRepository */
        $invoiceRepository = $this->getContainer()->get('doctrine')->getManager()->getRepository(Invoice::class);
        /** @var OrderRepository $orderRepository */
        $orderRepository = $this->getContainer()->get('sylius.repository.order');
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $orderRepository->createQueryBuilder('o');
        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('o.checkoutCompletedAt'))
            ->addOrderBy('o.checkoutCompletedAt', 'DESC')
        ;

        if ($withInvoice === true || $withoutInvoice === true) {
            /** @var QueryBuilder $subQueryBuilder */
            $subQueryBuilder = $invoiceRepository->createQueryBuilder('i');
            $subQueryBuilder->select('sub_order.id')
                ->innerJoin('i.order', 'sub_order')
                ->andWhere($subQueryBuilder->expr()->isNotNull('sub_order.id'))
            ;

            if ($withInvoice) {
                $queryBuilder->andWhere($queryBuilder->expr()->in('o.id', $subQueryBuilder->getDQL()));
            } elseif ($withoutInvoice) {
                $queryBuilder->andWhere($queryBuilder->expr()->notIn('o.id', $subQueryBuilder->getDQL()));
            }
        }

        return $queryBuilder;
    }
}
