<?php
/**
 * Block Back Order: module for PrestaShop 1.2-1.6
 *
 * @author      zapalm <zapalm@ya.ru>
 * @copyright   (c) 2011-2015, zapalm
 * @link        http://prestashop.modulez.ru/en/ Homepage
 * @license     http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 * @version     0.9
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class BlockBackOrder extends Module
{
    function __construct() {
        $this->name          = 'blockbackorder';
        $this->tab           = 'Payment';
        $this->version       = '0.9';
        $this->author        = 'zapalm';
        $this->need_instance = 0;
        $this->bootstrap     = false;

        parent::__construct();

        $this->displayName = $this->l('Block Back-order');
        $this->description = $this->l('Allow to back-ordering when a product out of stock');
    }

    public function install() {
        return parent::install() && $this->registerHook('productfooter');
    }

    public function uninstall() {
        return parent::uninstall();
    }

    /**
     * Hook ProductFooter.
     *
     * @param array $params
     *
     * @return string
     */
    function hookProductFooter($params) {
        global $smarty, $cookie;

        $product         = $params['product'];
        $allowOutOfStock = 1;

        if ($product->out_of_stock != $allowOutOfStock || $product->quantity > 0) {
            return '';
        }

        $error_code = null;
        if (Tools::getIsset('bo_submit')) {
            if (Tools::getValue('firstname') && Tools::getValue('surname') && Tools::getValue('phone') && Tools::getValue('city') && Tools::getValue('comment') && Tools::getValue('email')) {
                $sent = Mail::Send(
                    intval($cookie->id_lang),
                    'backorder',
                    $this->l('Back Order'),
                    array(
                        '{firstname}' => Tools::getValue('firstname'),
                        '{surname}' => Tools::getValue('surname'),
                        '{email}' => Tools::getValue('email'),
                        '{city}' => Tools::getValue('city'),
                        '{phone}' => Tools::getValue('phone'),
                        '{comment}' => Tools::getValue('comment'),
                        '{product}' => $params['product']->name,
                    ),
                    Configuration::get('PS_SHOP_EMAIL'),
                    null,
                    null,
                    null,
                    null,
                    null,
                    _PS_MODULE_DIR_ . $this->name . '/mails/'
                );

                $error_code = $sent ? 0 : 2;
            } else {
                $error_code = 1;
            }
        }

        if ($error_code == 1) {
            $msg = $this->l('All fields are requred.');
        } elseif ($error_code == 2) {
            $msg = $this->l('Unsuccesseful. Try again later.');
        } elseif ($error_code == 0) {
            $msg = $this->l('Successeful.');
        } else {
            $msg = '';
        }

        $smarty->assign(array(
            'msg'        => $msg,
            'error_code' => $error_code,
        ));

        return $this->display(__FILE__, 'blockbackorder.tpl');
    }
}