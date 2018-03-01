<?php

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Entity;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface CompanyDataInterface extends ResourceInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void;

    /**
     * @return string|null
     */
    public function getVatNumber(): ?string;

    /**
     * @param string|null $vatNumber
     */
    public function setVatNumber(?string $vatNumber): void;

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void;

    /**
     * @return string|null
     */
    public function getPostcode(): ?string;

    /**
     * @param string|null $postcode
     */
    public function setPostcode(?string $postcode): void;

    /**
     * @return string|null
     */
    public function getSeller(): ?string;

    /**
     * @param string|null $seller
     */
    public function setSeller(?string $seller): void;

    /**
     * @return ChannelInterface|null
     */
    public function getChannel(): ?ChannelInterface;

    /**
     * @param ChannelInterface|null $channel
     */
    public function setChannel(?ChannelInterface $channel): void;

    /**
     * @return string|null
     */
    public function getInvoiceNumberTemplate(): ?string;

    /**
     * @param string|null $invoiceNumberTemplate
     */
    public function setInvoiceNumberTemplate(?string $invoiceNumberTemplate): void;

    /**
     * @return bool
     */
    public function getValidateCustomerVatNumber(): bool;

    /**
     * @param bool $validateCustomerVatNumber
     */
    public function setValidateCustomerVatNumber(bool $validateCustomerVatNumber): void;

    /**
     * @return bool
     */
    public function getGenerateInvoiceAfterPaymentSuccess(): bool;

    /**
     * @param bool $generateInvoiceAfterPaymentSuccess
     */
    public function setGenerateInvoiceAfterPaymentSuccess(bool $generateInvoiceAfterPaymentSuccess): void;
}
