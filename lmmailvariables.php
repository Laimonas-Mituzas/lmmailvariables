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

        parent::__construct();

        $this->displayName = $this->l('Mail variables');
        $this->description = $this->l('Fix Bank Wire module mail variables from backoffice orders. Add additional varbialbles to show selected terminals by buyer for Omniva and LP Shipping');
    }

    public function install()
    {
        if (!parent::install() || !$this->registerHook('sendMailAlterTemplateVars'))
            return false;
        return true;
    }

    public function hookSendMailAlterTemplateVars($params)
    {
        if ($params['template'] == 'bankwire') {
            $params['template_vars']['{bankwire_owner}'] = Configuration::get('BANK_WIRE_OWNER');
            $params['template_vars']['{bankwire_details}'] = nl2br(Configuration::get('BANK_WIRE_DETAILS'));
            $params['template_vars']['{bankwire_address}'] = nl2br(Configuration::get('BANK_WIRE_ADDRESS'));
        }
    }

    protected function displayForm()
    {
    $token = Tools::getAdminToken($this->name);
    $fields_form = [
        'form' => [
            'legend' => [
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
            ],
            'input' => [
                [
                  'type' => 'text',
                  'lang' => true,
                  'label' => $this->l('Text input Demo'),
                  'name' => 'moduleexample_text',
                  'desc' => 'Description',
                ],
                [
                  'type' => 'switch',
                  'label' => $this->l('Checkbox'),
                  'name' => 'moduleexample_check',
                  'desc' => $this->l('Checkbox Demo'),
                  'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled'),
                        ],
                  ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ],
        ],
    ];
    $helper = new HelperForm();
    $helper->show_toolbar = false;
    $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
    $helper->default_form_language = $lang->id;
   
    $helper->submit_action = 'submitModuleExample';
    $helper->token = Tools::getAdminTokenLite('AdminModules');
   
    $fields = [];
    $fields['moduleexample_check'] = Configuration::get('moduleexample_check');
    $languages = Language::getLanguages(false);
    foreach ($languages as $language) {
        $id_lang = $language['id_lang'];
        $fields['moduleexample_text'][$id_lang] = Configuration::get('moduleexample_text_' . $language['id_lang']);
    }
    $helper->tpl_vars = [
        'fields_value' => $fields,
        'languages' => $this->context->controller->getLanguages(),
    ];

    return $helper->generateForm([$fields_form]);
}

protected function postProcessRuleSave()
    {
        $output = '';
        if (Tools::isSubmit('submitModuleExample')) {
            Configuration::updateValue('moduleexample_check', Tools::getValue('moduleexample_check'));
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $id_lang = $language['id_lang'];
                $var1 = Tools::getValue('moduleexample_text_' . $id_lang);
                Configuration::updateValue('moduleexample_text_' . $id_lang, $var1);
            }
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output;
    }
public function getContent()
{
   $contentsave = $this->postProcessRuleSave();
   return $contentsave.$this->displayForm();
}
}

