<?php
/**
 * Block Back Order: module for PrestaShop 1.2-1.6
 *
 * @author      zapalm <zapalm@ya.ru>
 * @copyright   (c) 2011-2016, zapalm
 * @link        http://prestashop.modulez.ru/en/frontend-features/40-pre-order-form.html Module's homepage
 * @license     http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * @inheritdoc
 */
class BlockBackOrder extends Module
{
    /**
     * @inheritdoc
     */
    function __construct() {
        $this->name          = 'blockbackorder';
        $this->tab           = 'Payment';
        $this->version       = '1.0.0';
        $this->author        = 'zapalm';
        $this->need_instance = 0;
        $this->bootstrap     = false;

        parent::__construct();

        $this->displayName = $this->l('Block Back-order');
        $this->description = $this->l('Allow to back-ordering when a product out of stock');
    }

    /**
     * @inheritdoc
     */
    public function install() {
        return parent::install() && $this->registerHook('productfooter');
    }

    /**
     * @inheritdoc
     */
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

        $message  = null;
        $hasError = false;

        if (Tools::getIsset('bo_submit')) {
            if (Tools::getValue('firstname') && Tools::getValue('surname') && Tools::getValue('phone') && Tools::getValue('city') && Tools::getValue('comment') && Tools::getValue('email')) {
                $sent = Mail::Send(
                    (int)$cookie->id_lang,
                    'backorder',
                    $this->l('Product pre-order'),
                    array(
                        '{firstname}'   => Tools::getValue('firstname'),
                        '{surname}'     => Tools::getValue('surname'),
                        '{email}'       => Tools::getValue('email'),
                        '{city}'        => Tools::getValue('city'),
                        '{phone}'       => Tools::getValue('phone'),
                        '{comment}'     => Tools::getValue('comment'),
                        '{product}'     => $product->name,
                    ),
                    Configuration::get('PS_SHOP_EMAIL'),
                    null,
                    null,
                    null,
                    null,
                    null,
                    _PS_MODULE_DIR_ . $this->name . '/mails/'
                );

                if ($sent) {
                    $message = $this->l('Successful.');
                } else {
                    $message  = $this->l('Unsuccessful. Try again later.');
                    $hasError = true;
                }
            } else {
                $message  = $this->l('All fields are required.');
                $hasError = true;
            }
        }

        $smarty->assign(array(
            'message'    => $message,
            'hasError'   => $hasError,
        ));

        return $this->display(__FILE__, 'blockbackorder.tpl');
    }
}