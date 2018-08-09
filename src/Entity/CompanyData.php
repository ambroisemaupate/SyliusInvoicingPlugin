<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Entity;

use Sylius\Component\Channel\Model\ChannelInterface;

class CompanyData implements CompanyDataInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $vatNumber;

    /**
     * @var string
     */
    protected $registrationNumber;

    /**
     * @var string
     */
    protected $activityCode;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $postcode;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $seller;

    /**
     * @var ChannelInterface|null
     */
    protected $channel;

    /**
     * @var string|null
     */
    protected $invoiceNumberTemplate;

    /**
     * @var bool
     */
    protected $validateCustomerVatNumber = true;

    /**
     * @var bool
     */
    protected $generateInvoiceAfterPaymentSuccess = false;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setVatNumber(?string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setRegistrationNumber(?string $registrationNumber): void
    {
        $this->registrationNumber = $registrationNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityCode(): ?string
    {
        return $this->activityCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivityCode(?string $activityCode): void
    {
        $this->activityCode = $activityCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getSeller(): ?string
    {
        return $this->seller;
    }

    /**
     * {@inheritdoc}
     */
    public function setSeller(?string $seller): void
    {
        $this->seller = $seller;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel(): ?ChannelInterface
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(?ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvoiceNumberTemplate(): ?string
    {
        return $this->invoiceNumberTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function setInvoiceNumberTemplate(?string $invoiceNumberTemplate): void
    {
        $this->invoiceNumberTemplate = $invoiceNumberTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function getValidateCustomerVatNumber(): bool
    {
        return $this->validateCustomerVatNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function setValidateCustomerVatNumber(bool $validateCustomerVatNumber): void
    {
        $this->validateCustomerVatNumber = $validateCustomerVatNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function getGenerateInvoiceAfterPaymentSuccess(): bool
    {
        return $this->generateInvoiceAfterPaymentSuccess;
    }

    /**
     * {@inheritdoc}
     */
    public function setGenerateInvoiceAfterPaymentSuccess(bool $generateInvoiceAfterPaymentSuccess): void
    {
        $this->generateInvoiceAfterPaymentSuccess = $generateInvoiceAfterPaymentSuccess;
    }
}
