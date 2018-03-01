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

use BitBag\SyliusInvoicingPlugin\Entity\CompanyDataInterface;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\ChannelInterface;

final class CompanyDataRepository extends EntityRepository implements CompanyDataRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createListQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('o');
    }

    /**
     * {@inheritdoc}
     */
    public function findCompanyData(): ?CompanyDataInterface
    {
        return $this->createQueryBuilder('o')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function findCompanyDataByChannel(ChannelInterface $channel): ?CompanyDataInterface
    {
        $queryBuilder = $this->createQueryBuilder('o');

        return $queryBuilder->andWhere($queryBuilder->expr()->eq('o.channel', ':channel'))
            ->setParameter(':channel', $channel)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
