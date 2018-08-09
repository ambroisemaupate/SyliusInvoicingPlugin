<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class CompanyDataType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.company_name',
            ])
            ->add('street', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.street',
            ])
            ->add('city', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.city',
            ])
            ->add('postcode', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.postcode',
            ])
            ->add('countryCode', CountryType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.country',
            ])
            ->add('vatNumber', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.vat_number',
            ])
            ->add('registrationNumber', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.registration_number',
                'required' => false,
            ])
            ->add('activityCode', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.activity_code',
                'required' => false,
            ])
            ->add('seller', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.seller',
            ])
            ->add('channel', ChannelChoiceType::class, [
                'multiple' => false,
                'expanded' => true,
                'required' => false,
                'label' => 'bitbag_sylius_invoicing_plugin.ui.channel',
            ])
            ->add('invoiceNumberTemplate', TextType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.invoice_number_template',
            ])
            ->add('validateCustomerVatNumber', CheckboxType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.validate_customer_vat_number',
            ])
            ->add('generateInvoiceAfterPaymentSuccess', CheckboxType::class, [
                'label' => 'bitbag_sylius_invoicing_plugin.ui.generate_invoice_after_payment_success',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'bitbag_sylius_invoicing_plugin_company_data';
    }
}
