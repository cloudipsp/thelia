<?php

namespace Fondy\Form;

use Fondy\Config\GatewayCurrency;
use Fondy\Fondy;
use Fondy\Config\ConfigKeys;
use Fondy\Config\GatewayFondyType;
use Thelia\Form\BaseForm;

/**
 * Module configuration form.
 */
class ConfigForm extends BaseForm
{
    public function getName()
    {
        return 'fondy_config';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                ConfigKeys::MERCHANT_ID,
                'text',
                [
                    'label' => $this->translator->trans('Merchant ID'),
                    'data' => Fondy::getConfigValue(ConfigKeys::MERCHANT_ID),
                ]
            )
            ->add(
                ConfigKeys::SECRET_KEY,
                'text',
                [
                    'label' => $this->translator->trans('Secret key'),
                    'data' => Fondy::getConfigValue(ConfigKeys::SECRET_KEY),
                ]
            )
            ->add(
                ConfigKeys::GATEWAY_URL,
                'text',
                [
                    'label' => $this->translator->trans('Payment gateway URL'),
                    'data' => Fondy::getConfigValue(ConfigKeys::GATEWAY_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::CALLBACK_URL,
                'text',
                [
                    'label' => $this->translator->trans('Gateway callback URL (recommended set empty to generate automatically)'),
                    'data' => Fondy::getConfigValue(ConfigKeys::CALLBACK_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::RESPONSE_URL,
                'text',
                [
                    'label' => $this->translator->trans('Gateway response URL (recommended set empty to generate automatically)'),
                    'data' => Fondy::getConfigValue(ConfigKeys::RESPONSE_URL),
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::GATEWAY_TYPE,
                'choice',
                [
                    'label' => $this->translator->trans('Gateway type'),
                    'data' => Fondy::getConfigValue(ConfigKeys::GATEWAY_TYPE),
                    'choices' => [
                        GatewayFondyType::RELAY_RESPONSE => $this->translator->trans(
                            'Redirect to payment page'
                        ),
                    ],
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::CURRENCY,
                'choice',
                [
                    'label' => $this->translator->trans('Currency'),
                    'data' => Fondy::getConfigValue(ConfigKeys::CURRENCY),
                    'choices' => [
                         ''=> $this->translator->trans(
                            'choose from shop'
                        ),
                        GatewayCurrency::USD => $this->translator->trans(
                            'USD'
                        ),
                        GatewayCurrency::UAH => $this->translator->trans(
                            'UAH'
                        ),
                        GatewayCurrency::RUB => $this->translator->trans(
                            'RUB'
                        ),
                        GatewayCurrency::EUR => $this->translator->trans(
                            'EUR'
                        ),
                        GatewayCurrency::GBP => $this->translator->trans(
                            'GBP'
                        ),
                        GatewayCurrency::CZK => $this->translator->trans(
                            'CZK'
                        ),
                    ],
                    'required' => false,
                ]
            )
            ->add(
                ConfigKeys::PREAUTH,
                'checkbox',
                [
                    'label' => $this->translator->trans('Enable preauth mode'),
                    'data' => Fondy::getConfigValue(ConfigKeys::PREAUTH) == 1,
                    'required' => false,
                ]
            );
    }
}
