<?php
/**
 * Block back order: module for PrestaShop.
 *
 * @author    Maksim T. <zapalm@yandex.com>
 * @copyright 2011 Maksim T.
 * @link      https://prestashop.modulez.ru/en/express-order-checkout/40-pre-order-form.html The module's homepage
 * @license   https://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Module BlockBackOrder.
 *
 * @author Maksim T. <zapalm@yandex.com>
 */
class BlockBackOrder extends Module
{
    /** The product ID of the module on its homepage. */
    const HOMEPAGE_PRODUCT_ID = 40;

    /**
     * @inheritDoc
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function __construct()
    {
        $this->name          = 'blockbackorder';
        $this->tab           = 'Payment';
        $this->version       = '1.1.0';
        $this->author        = 'zapalm';
        $this->need_instance = 0;
        $this->bootstrap     = false;

        parent::__construct();

        $this->displayName = $this->l('Block Back-order');
        $this->description = $this->l('Allow to back-ordering when a product out of stock');
    }

    /**
     * @inheritDoc
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function install()
    {
        $result = parent::install();

        if ($result) {
            $result = (bool)$this->registerHook('productfooter');
        }

        $this->registerModuleOnQualityService('installation');

        return $result;
    }

    /**
     * @inheritDoc
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function uninstall()
    {
        $result = (bool)parent::uninstall();

        $this->registerModuleOnQualityService('uninstallation');

        return $result;
    }

    /**
     * Hook ExtraRight.
     *
     * @param array $params
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function hookExtraRight($params)
    {
        $params['product'] = new Product((int)Tools::getValue('id_product'));

        return $this->hookProductFooter($params);
    }

    /**
     * Hook ExtraLeft.
     *
     * @param array $params
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function hookExtraLeft($params)
    {
        $params['product'] = new Product((int)Tools::getValue('id_product'));

        return $this->hookProductFooter($params);
    }

    /**
     * Hook ProductFooter.
     *
     * @param array $params
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function hookProductFooter($params)
    {
        global $smarty, $cookie;

        $product = $params['product'];

        if ($product->quantity > 0) {
            return '';
        }

        $productOutOfStock = (int)$product->out_of_stock;
        if (0 === $productOutOfStock) {
            return '';
        }

        $stockManagement = (bool)Configuration::get('PS_STOCK_MANAGEMENT');
        $allowOutOfStock = false;
        if (false === $stockManagement || 1 === $productOutOfStock || (2 === $productOutOfStock && true === (bool)Configuration::get('PS_ORDER_OUT_OF_STOCK'))) {
            $allowOutOfStock = true;
        }

        if (false === $allowOutOfStock) {
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
                    [
                        '{firstname}'   => Tools::getValue('firstname'),
                        '{surname}'     => Tools::getValue('surname'),
                        '{email}'       => Tools::getValue('email'),
                        '{city}'        => Tools::getValue('city'),
                        '{phone}'       => Tools::getValue('phone'),
                        '{comment}'     => Tools::getValue('comment'),
                        '{product}'     => $product->name,
                    ],
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

        $smarty->assign([
            'message'    => $message,
            'hasError'   => $hasError,
        ]);

        return $this->display(__FILE__, 'blockbackorder.tpl');
    }

    /**
     * Get module's settings page content.
     *
     * @return string
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    public function getContent()
    {
        $output = (version_compare(_PS_VERSION_, '1.6', '>=') ? '' : '<h2>' . $this->displayName . '</h2>');

        // The block about the module (version: 2021-08-19)
        $modulezUrl    = 'https://prestashop.modulez.ru' . (Language::getIsoById(false === empty($GLOBALS['cookie']->id_lang) ? $GLOBALS['cookie']->id_lang : Context::getContext()->language->id) === 'ru' ? '/ru/' : '/en/');
        $modulePage    = $modulezUrl . self::HOMEPAGE_PRODUCT_ID . '-' . $this->name . '.html';
        $licenseTitle  = 'Academic Free License (AFL 3.0)';
        $output       .=
            (version_compare(_PS_VERSION_, '1.6', '<') ? '<br class="clear" />' : '') . '
            <div class="panel">
                <div class="panel-heading">
                    <img src="' . $this->_path . 'logo.png" width="16" height="16" alt=""/>
                    ' . $this->l('Module info') . '
                </div>
                <div class="form-wrapper">
                    <div class="row">               
                        <div class="form-group col-lg-4" style="display: block; clear: none !important; float: left; width: 33.3%;">
                            <span><b>' . $this->l('Version') . ':</b> ' . $this->version . '</span><br/>
                            <span><b>' . $this->l('License') . ':</b> ' . $licenseTitle . '</span><br/>
                            <span><b>' . $this->l('Website') . ':</b> <a class="link" href="' . $modulePage . '" target="_blank">prestashop.modulez.ru</a></span><br/>
                            <span><b>' . $this->l('Author') . ':</b> ' . $this->author . '</span><br/><br/>
                        </div>
                        <div class="form-group col-lg-2" style="display: block; clear: none !important; float: left; width: 16.6%;">
                            <img width="250" alt="' . $this->l('Website') . '" src="https://prestashop.modulez.ru/img/marketplace-logo.png" />
                        </div>
                    </div>
                </div>
            </div> ' .
            (version_compare(_PS_VERSION_, '1.6', '<') ? '<br class="clear" />' : '') . '
        ';

        return $output;
    }

    /**
     * Registers current module installation/uninstallation in the quality service.
     *
     * This method is needed for a developer to quickly find out about a problem with installing or uninstalling a module.
     *
     * @param string $operation The operation. Possible values: installation, uninstallation.
     *
     * @author Maksim T. <zapalm@yandex.com>
     */
    private function registerModuleOnQualityService($operation)
    {
        @file_get_contents('https://prestashop.modulez.ru/scripts/quality-service/index.php?' . http_build_query([
            'data' => json_encode([
                'productId'           => self::HOMEPAGE_PRODUCT_ID,
                'productSymbolicName' => $this->name,
                'productVersion'      => $this->version,
                'operation'           => $operation,
                'status'              => (empty($this->_errors) ? 'success' : 'error'),
                'message'             => (false === empty($this->_errors) ? strip_tags(stripslashes(implode(' ', (array)$this->_errors))) : ''),
                'prestashopVersion'   => _PS_VERSION_,
                'thirtybeesVersion'   => (defined('_TB_VERSION_') ? _TB_VERSION_ : ''),
                'shopDomain'          => (method_exists('Tools', 'getShopDomain') && Tools::getShopDomain() ? Tools::getShopDomain() : (Configuration::get('PS_SHOP_DOMAIN') ? Configuration::get('PS_SHOP_DOMAIN') : Tools::getHttpHost())),
                'shopEmail'           => Configuration::get('PS_SHOP_EMAIL'), // This public e-mail from a shop's contacts can be used by a developer to send only an urgent information about security issue of a module!
                'phpVersion'          => PHP_VERSION,
                'ioncubeVersion'      => (function_exists('ioncube_loader_iversion') ? ioncube_loader_iversion() : ''),
                'languageIsoCode'     => Language::getIsoById(false === empty($GLOBALS['cookie']->id_lang) ? $GLOBALS['cookie']->id_lang : Context::getContext()->language->id),
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]));
    }
}