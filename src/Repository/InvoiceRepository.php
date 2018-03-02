<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Repository;

use BitBag\SyliusInvoicingPlugin\Entity\InvoiceInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;

final class InvoiceRepository extends EntityRepository implements InvoiceRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findByOrderId(?int $orderId): ?InvoiceInterface
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.order', 'invoiceOrder')
            ->where('invoiceOrder.id = :orderId')
            ->setParameter('orderId', $orderId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @inheritDoc
     */
    public function countByYear(\DateTime $dateTime): int
    {
        $queryBuilder = $this->createQueryBuilderForYear($dateTime);

        return $queryBuilder->select($queryBuilder->expr()->count('o'))
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @inheritDoc
     */
    public function countAll(): int
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder->select($queryBuilder->expr()->count('o'))
            ->innerJoin('o.order', 'order')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @inheritDoc
     */
    public function countByYearAndChannel(\DateTime $dateTime, ChannelInterface $channel): int
    {
        $queryBuilder = $this->createQueryBuilderForYear($dateTime);

        return $queryBuilder->select($queryBuilder->expr()->count('o'))
            ->andWhere($queryBuilder->expr()->eq('order.channel', ':channel'))
            ->setParameter(':channel', $channel)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @inheritDoc
     */
    public function countByChannel(ChannelInterface $channel): int
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder->select($queryBuilder->expr()->count('o'))
            ->innerJoin('o.order', 'order')
            ->andWhere($queryBuilder->expr()->eq('order.channel', ':channel'))
            ->setParameter(':channel', $channel)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @param \DateTime $dateTime
     *
     * @return QueryBuilder
     */
    private function createQueryBuilderForYear(\DateTime $dateTime): QueryBuilder
    {
        $start = new \DateTime($dateTime->format('Y').'-01-01 00:00:00');
        $end = clone $start;
        $end->add(new \DateInterval('P1Y'));

        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder
            ->innerJoin('o.order', 'order')
            ->where($queryBuilder->expr()->between('order.checkoutCompletedAt', ':start', ':end'))
            ->setParameter(':start', $start)
            ->setParameter(':end', $end)
        ;
    }
}
