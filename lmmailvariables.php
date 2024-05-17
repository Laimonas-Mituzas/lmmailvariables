<?php

if (!defined('_PS_VERSION_'))
    exit;

class FixBankWireMails extends Module
{
    public function __construct()
    {
        $this->name = 'lmmailvariables';
        $this->tab = 'administration';
        $this->version = '1.0';
        $this->author = 'augusupresta.lt';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.6.2',
            'max' => '8.9.9',
        ];
        $this->bootstrap = true;

                $this->displayName = $this->l('Mail variables');
        $this->description = $this->l('Fix Bank Wire module mail variables from backoffice orders. Add additional varbialbles to show selected terminals by buyer for Omniva and LP Shipping');

        $this->confirmUninstall = "Are you sure?";

        parent::__construct();
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('sendMailAlterTemplateVars'))
            return false;
        return true;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookSendMailAlterTemplateVars($params)
    {
        if ($params['template'] == 'bankwire') {
            $params['template_vars']['{bankwire_owner}'] = Configuration::get('BANK_WIRE_OWNER');
            $params['template_vars']['{bankwire_details}'] = nl2br(Configuration::get('BANK_WIRE_DETAILS'));
            $params['template_vars']['{bankwire_address}'] = nl2br(Configuration::get('BANK_WIRE_ADDRESS'));
        }
    }

    public function getContent()
    {
        return 'This is a configuration page!';
    }

    
}
