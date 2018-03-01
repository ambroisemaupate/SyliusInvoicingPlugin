<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Resolver;

use BitBag\SyliusInvoicingPlugin\Entity\CompanyDataInterface;
use BitBag\SyliusInvoicingPlugin\Repository\CompanyDataRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class CompanyDataResolver implements CompanyDataResolverInterface
{
    /**
     * @var CompanyDataRepositoryInterface
     */
    private $companyDataRepository;

    /**
     * @param CompanyDataRepositoryInterface $companyDataRepository
     */
    public function __construct(CompanyDataRepositoryInterface $companyDataRepository)
    {
        $this->companyDataRepository = $companyDataRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveCompanyData(?ChannelInterface $channel = null): ?CompanyDataInterface
    {
        if (null !== $channel) {
            return $this->companyDataRepository->findCompanyDataByChannel($channel);
        }

        return $this->companyDataRepository->findCompanyData();
    }
}
