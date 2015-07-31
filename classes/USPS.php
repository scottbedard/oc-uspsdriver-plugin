<?php namespace Bedard\USPS\Classes;

use Bedard\Shipping\Usps as Shipment;
use Bedard\Shop\Classes\ShippingBase;
use Bedard\Shop\Interfaces\ShippingInterface;

class USPS extends ShippingBase implements ShippingInterface {

    /**
     * Validation
     */
    public $rules = [
        'usps_id'   => 'required',
        'origin'    => 'required|digits:5',
        'is_live'   => 'boolean',
    ];

    public $customMessages = [
        'usps_id.required'  => 'Please enter your USPS Web Tools ID.',
        'origin.digits'     => 'That does not appear to be a valid zip code.',
    ];

    /**
     * Register configuration fields
     *
     * @return  array
     */
    public function registerFields()
    {
        return [
            'usps_id' => [
                'label'     => 'USPS Web Tools ID',
                'comment'   => 'If you do not have one, please visit https://registration.shippingapis.com',
            ],
            'origin' => [
                'label'     => 'Origin Zip Code',
                'span'      => 'left',
            ],
            'server' => [
                'label'     => 'Server',
                'type'      => 'dropdown',
                'options'   => [
                    'production' => 'Production',
                    'sandbox' => 'Sandbox',
                ],
                'default'   => 'production',
                'span'      => 'right',
            ],
            'domestic_rates' => [
                'label'     => 'Domestic rates',
                'type'      => 'checkboxlist',
                'options'   => [
                    '_0'    => 'First-Class Mail Parcel',
                    '_1'    => 'First-Class Mail Large Envelope',
                    '_2'    => 'First-Class Mail Stamped Letter',
                    '1'     => 'Priority Mail',
                    '3'     => 'Priority Mail Express',
                    '4'     => 'Standard Post',
                ],
                'span'      => 'left',
            ],
            'international_rates' => [
                'label'     => 'International rates',
                'type'      => 'checkboxlist',
                'options'   => [
                    '15'    => 'First-Class Package International Service',
                    '2'     => 'Priority Mail International',
                    '1'     => 'Priority Mail Express International',
                    '4'     => 'Global Express Guaranteed (GXG)',
                ],
                'span'      => 'right',
            ],
            'use_table' => [
                'label'     => 'Defer to the Shipping Table if no rates are returned',
                'type'      => 'switch',
                'default'   => true,
            ],
        ];
    }
    /**
     * Return shipping rates
     */
    public function getRates()
    {
        $usps = new Shipment($this->getConfig('usps_id'));

        if ($this->getConfig('server') == 'sandbox') {
            $usps->useTestingServer();
        }

        if ($this->cart->shipping_address->country_id == 1) {
            $usps->setDestination($this->cart->shipping_address->postal_code);
        } else {
            $usps->setDestination($this->cart->shipping_address->country->name);
        }

        $packaging = 1;
        foreach ($this->cart->items as $item) {
            if ($item->inventory->product->packaging_id > $packaging) {
                $packaging = $item->inventory->product->packaging_id;
            }
        }

        $codes = $this->getCodes();
        $dimensions = ['length' => 6, 'width' => 6, 'height' => 0.1];
        if ($packaging == 1) {
            $codes = array_diff($codes, ['_0', '_1', '3']);
        } elseif ($packaging == 2) {
            $codes = array_diff($codes, ['_0', '_2']);
            $dimensions = ['length' => 12, 'width' => 10, 'height' => 0.1];
        } elseif ($packaging == 3) {
            $codes = array_diff($codes, ['_1', '_2']);
            $dimensions = ['length' => 12, 'width' => 12, 'height' => 12];
        }

        $usps
            ->setOrigin($this->getConfig('origin'))
            ->setDimensions([
                'length'    => $dimensions['length'],
                'width'     => $dimensions['width'],
                'height'    => $dimensions['height'],
                'pounds'    => 0,
                'ounces'    => $this->cart->getWeight('oz'),
            ])
            ->setValue($this->cart->subtotal);

        return array_filter($usps->calculate(), function($rate) use ($codes) {
            $rate['class'] = 'Bedard\USPS\Classes\USPS';
            return in_array($rate['code'], $codes, true);
        });
    }

    /**
     * Returns an array of accepted USPS shipping codes
     *
     * @return  array
     */
    protected function getCodes()
    {
        $codes = [];
        if ($domestic = $this->getConfig('domestic_rates')) {
            $codes = array_merge($codes, $domestic);
        }

        if ($international = $this->getConfig('international_rates')) {
            $codes = array_merge($codes, $international);
        }

        return array_map(function($code) {
            return str_replace('_', '0', $code);
        }, $codes);
    }
}
