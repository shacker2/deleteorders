<?php
/**
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * No redistribute in other sites, or copy.
 *
 * @author    RSI <rsi_2004@hotmail.com>
 * @copyright 2007-2021 RSI
 * @license   http://catalogo-onlinersi.net
 */

class DeleteOrders extends Module
{
	private $_html = '';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'deleteorders';
		if (_PS_VERSION_ < '1.4.0.0')
			$this->tab = 'Tools';
		if (_PS_VERSION_ > '1.4.0.0')
		{
			$this->tab           = 'administration';
			$this->author        = 'RSI';
			$this->need_instance = 1;
		}
		if (_PS_VERSION_ > '1.6.0.0')
			$this->bootstrap = true;

		$this->version = '2.0.0';

		parent::__construct(); // The parent construct is required for translations

		$this->page        = basename(__FILE__, '.php');
		$this->displayName = $this->l('Delete orders');
		$this->description = $this->l('Enable delete button in order page - www.catalogo-onlinersi.net');
		if (_PS_VERSION_ < '1.5')
			require(_PS_MODULE_DIR_.$this->name.'/backward_compatibility/backward.php');
	}

	public function install()
	{
		if (!parent::install())
			return false;
		if (!Configuration::updateValue('DELETEORDERS_DELETE', 0))
			return false;
		return true;
	}

	public function getContent()
	{
		$deleteord = Tools::getValue('deleteord');
		Configuration::updateValue('DELETEORDERS_DELETE', $deleteord);
		$errors = '';
		if (_PS_VERSION_ < '1.6.0.0') {
			$output = '<h2>'.$this->displayName.'</h2>';
			if (Tools::isSubmit('submitall')) {
			Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'orders`');
				if (_PS_VERSION_ < '1.3.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_customization_return`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_detail`');
				if (_PS_VERSION_ < '1.5.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_discount`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_history`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_message`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_message_lang`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_detail`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_state`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_state_lang`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_slip`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_slip_detail`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'message`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'cart`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'cart_product`');
				if (_PS_VERSION_ > '1.4.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'message_readed`');
				if (_PS_VERSION_ > '1.5.0.0')
				{
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_carrier`');
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_detail_tax`');
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_invoice`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_invoice_payment`');
				}
				$output .= $this->displayConfirmation($this->l('Orders deleted'));
				return $output.$this->displayForm();
			}
			if (Tools::isSubmit('submitedeleteo'))
			{
				if (Tools::getValue('deleteord') == 1)
				{

					if ($errors != null)
						$output .= $this->displayError(implode('<br />', $errors));

					else

						if (_PS_VERSION_ < '1.5.0.0')
						{
							chmod('tabs/AdminOrders.php', 0777);
							if (ini_get('allow_url_fopen') == '0')
								ini_set('allow_url_fopen', '1');
							$str = '';
							if ($fh = fopen('tabs/AdminOrders.php', 'r'))
							{
								while (!feof($fh))
									$str .= fgets($fh);
								$str = str_replace('$this->colorOnBackground = true;', '$this->colorOnBackground = true;$this->delete = true;', $str);
								fclose($fh);
								chmod('tabs/AdminOrders.php', 0644);
							}
							else
								die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

							$x42 = fopen('tabs/AdminOrders.php', 'w');
							fwrite($x42, $str);
							$output .= $this->displayConfirmation($this->l('Delete orders enabled'));
						}
						else
						{
							chmod('../controllers/admin/AdminOrdersController.php', 0777);
							if (ini_get('allow_url_fopen') == '0')
								ini_set('allow_url_fopen', '1');
							$str = '';
							if ($fh = fopen('../controllers/admin/AdminOrdersController.php', 'r'))
							{
								while (!feof($fh))
									$str .= fgets($fh);
								$str = str_replace('$this->addRowAction(\'view\');', '$this->addRowAction(\'view\');$this->addRowAction(\'delete\');', $str);
								fclose($fh);
								chmod('../controllers/admin/AdminOrdersController.php', 0644);
							}
							else
								die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

							$x42 = fopen('../controllers/admin/AdminOrdersController.php', 'w');
							fwrite($x42, $str);
							$output .= $this->displayConfirmation($this->l('Delete orders enabled'));
						}

				}
				elseif (Tools::getValue('deleteord') == 0)
				{


					if (_PS_VERSION_ < '1.5.0.0')
					{
						chmod('tabs/AdminOrders.php', 0777);
						if (ini_get('allow_url_fopen') == '0')
							ini_set('allow_url_fopen', '1');
						$str = '';

						if ($fh = fopen('tabs/AdminOrders.php', 'r'))
						{
							while (!feof($fh))
								$str .= fgets($fh);
							$str = str_replace('$this->colorOnBackground = true;$this->delete = true;', '$this->colorOnBackground = true;', $str);
							fclose($fh);
							chmod('tabs/AdminOrders.php', 0644);
						}
						else
							die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

						$x42 = fopen('tabs/AdminOrders.php', 'w');
						fwrite($x42, $str);
						$output .= $this->displayConfirmation($this->l('Delete orders disabled'));
					}
					else
					{
						chmod('../controllers/admin/AdminOrdersController.php', 0777);
						if (ini_get('allow_url_fopen') == '0')
							ini_set('allow_url_fopen', '1');
						$str = '';

						if ($fh = fopen('../controllers/admin/AdminOrdersController.php', 'r'))
						{
							while (!feof($fh))
								$str .= fgets($fh);
							$str = str_replace('$this->addRowAction(\'view\');$this->addRowAction(\'delete\');', '$this->addRowAction(\'view\');', $str);
							fclose($fh);
							chmod('../controllers/admin/AdminOrdersController.php', 0644);
						}
						else
							die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

						$x42 = fopen('../controllers/admin/AdminOrdersController.php', 'w');
						fwrite($x42, $str);
						$output .= $this->displayConfirmation($this->l('Delete orders disabled'));
					}
				}
				return $output.$this->displayForm();
			}
							return $output.$this->displayForm();

		}
		else

			return $this->postProcess().$this->_displayInfo().$this->renderForm().$this->renderForm2().$this->_displayAdds();
	}

	public function _deleteorders()
	{
		chmod('tabs/AdminOrders.php', 0777);
		if (ini_get('allow_url_fopen') == '0')
			ini_set('allow_url_fopen', '1');
		$str = '';

		if ($fh = fopen('AdminOrders.php', 'r'))
		{
			while (!feof($fh))
				$str .= fgets($fh);
			$str = str_replace('true;$this->delete = true;', '', $str);
			fclose($fh);
		}
		else
			die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

		$x42 = fopen('AdminOrders.php', 'w');
		fwrite($x42, $str);

	}

	public function postProcess()
	{
		$errors = '';
		$output = '';
		if (Tools::isSubmit('submitall'))
		{
			Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'orders`');
				if (_PS_VERSION_ < '1.3.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_customization_return`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_detail`');
				if (_PS_VERSION_ < '1.5.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_discount`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_history`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_message`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_message_lang`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_detail`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_state`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_return_state_lang`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_slip`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_slip_detail`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'message`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'cart`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'cart_product`');
				
				
				if (_PS_VERSION_ > '1.4.0.0')
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'message_readed`');
				if (_PS_VERSION_ > '1.5.0.0')
				{
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_carrier`');
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_detail_tax`');
					Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_invoice`');
				Db::getInstance()->Execute('TRUNCATE `'._DB_PREFIX_.'order_invoice_payment`');
				}
			$output .= $this->displayConfirmation($this->l('Orders deleted'));
			return $output;
		}

		if (Tools::isSubmit('submitdeleteo'))
		{
			if ($deleteord = Tools::getValue('deleteord'))
				Configuration::updateValue('DELETEORDERS_DELETE', $deleteord);
			elseif (Shop::getContext() == Shop::CONTEXT_SHOP || Shop::getContext() == Shop::CONTEXT_GROUP)
				Configuration::deleteFromContext('DELETEORDERS_DELETE');


			if (Tools::getValue('deleteord') == 1)
			{

				if ($errors != null)
					$output .= $this->displayError(implode('<br />', $errors));

				else

					if (_PS_VERSION_ < '1.5.0.0')
					{
						chmod('tabs/AdminOrders.php', 0777);
						if (ini_get('allow_url_fopen') == '0')
							ini_set('allow_url_fopen', '1');
						$str = '';
						if ($fh = fopen('tabs/AdminOrders.php', 'r'))
						{
							while (!feof($fh))
								$str .= fgets($fh);
							$str = str_replace('$this->colorOnBackground = true;', '$this->colorOnBackground = true;$this->delete = true;', $str);
							fclose($fh);
							chmod('tabs/AdminOrders.php', 0644);
						}
						else
							die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

						$x42 = fopen('tabs/AdminOrders.php', 'w');
						fwrite($x42, $str);
						$output .= $this->displayConfirmation($this->l('Delete orders enabled'));
					}
					else
					{
						chmod('../controllers/admin/AdminOrdersController.php', 0777);
						if (ini_get('allow_url_fopen') == '0')
							ini_set('allow_url_fopen', '1');
						$str = '';
						if ($fh = fopen('../controllers/admin/AdminOrdersController.php', 'r'))
						{
							while (!feof($fh))
								$str .= fgets($fh);
							$str = str_replace('$this->addRowAction(\'view\');', '$this->addRowAction(\'view\');$this->addRowAction(\'delete\');', $str);
							$str = str_replace('$this->addRowAction(\'view\');$this->addRowAction(\'delete\');$this->addRowAction(\'delete\');$this->addRowAction(\'delete\');', '$this->addRowAction(\'view\');$this->addRowAction(\'delete\');', $str);
							fclose($fh);
							chmod('../controllers/admin/AdminOrdersController.php', 0644);
						}
						else
							die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

						$x42 = fopen('../controllers/admin/AdminOrdersController.php', 'w');
						fwrite($x42, $str);
						$output .= $this->displayConfirmation($this->l('Delete orders enabled'));
					}

			}
			elseif (Tools::getValue('deleteord') == 0)
			{


				if (_PS_VERSION_ < '1.5.0.0')
				{
					chmod('tabs/AdminOrders.php', 0777);
					if (ini_get('allow_url_fopen') == '0')
						ini_set('allow_url_fopen', '1');
					$str = '';

					if ($fh = fopen('tabs/AdminOrders.php', 'r'))
					{
						while (!feof($fh))
							$str .= fgets($fh);
						$str = str_replace('$this->colorOnBackground = true;$this->delete = true;', '$this->colorOnBackground = true;', $str);
						fclose($fh);
						chmod('tabs/AdminOrders.php', 0644);
					}
					else
						die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

					$x42 = fopen('tabs/AdminOrders.php', 'w');
					fwrite($x42, $str);
					$output .= $this->displayConfirmation($this->l('Delete orders disabled'));
				}
				else
				{
					chmod('../controllers/admin/AdminOrdersController.php', 0777);
					if (ini_get('allow_url_fopen') == '0')
						ini_set('allow_url_fopen', '1');
					$str = '';

					if ($fh = fopen('../controllers/admin/AdminOrdersController.php', 'r'))
					{
						while (!feof($fh))
							$str .= fgets($fh);
						$str = str_replace('$this->addRowAction(\'view\');$this->addRowAction(\'delete\');', '$this->addRowAction(\'view\');', $str);
						fclose($fh);
						chmod('../controllers/admin/AdminOrdersController.php', 0644);
					}
					else
						die ('Error opening file in '.__FILE__.' on line '.__LINE.'.');

					$x42 = fopen('../controllers/admin/AdminOrdersController.php', 'w');
					fwrite($x42, $str);
					$output .= $this->displayConfirmation($this->l('Delete orders disabled'));
				}
			}

			//if (!$errors)
			return $output;
		}

	}

	private function _displayInfo()
	{
		return $this->display(__FILE__, 'views/templates/hook/infos.tpl');
	}

	private function _displayAdds()
	{
		return $this->display(__FILE__, 'views/templates/hook/adds.tpl');
	}

	public function getConfigFieldsValues()
	{
		$fields_values = array(
			'deleteord' => Tools::getValue('deleteord', Configuration::get('DELETEORDERS_DELETE')),
		);
		return $fields_values;

	}

	public function renderForm()
	{
		$this->postProcess();
		$options2                         = array(
			array(
				'id_option' => 0,       // The value of the 'value' attribute of the <option> tag.
				'name'      =>$this->l('Disable delete orders'),   // The value of the text content of the  <option> tag.
			),
			array(
				'id_option' => 1,
				'name'      => $this->l('Enable delete orders'),
			),

		);
		$fields_form                      = array(
			'form' => array(
				'legend'      => array(
					'title' => $this->l('Configuration'),
					'icon'  => 'icon-cogs'
				),
				'description' => $this->l('').preg_replace('@{link}(.*){/link}@', '<a href="../modules/deleteorders/moduleinstall.pdf">$1</a>', $this->l('{link}Readme{/link}')).preg_replace('@{link2}(.*){/link2}@', ' - <a href="../modules/deleteorders/termsandconditions.pdf">$1</a>', $this->l('{link2}Terms{/link2}')),
				'input'       => array(
					array(
						'type'    => 'select',
						'label'   => $this->l('Enable delete button in order page'),
						'name'    => 'deleteord',
						'desc'    => $this->l('When you enable delete orders, you can delete orders in ORDER TAB with the trash icon'),
						'options' => array(
							'query' => $options2,
							'id'    => 'id_option',
							'name'  => 'name'
						)
					),
				),
				'submit'      => array(
					'title' => $this->l('Save'),
				)
			),
		);
		$helper                           = new HelperForm();
		$helper->show_toolbar             = true;
		$helper->table                    = $this->table;
		$lang                             = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language    = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form                = array();
		$helper->identifier               = $this->identifier;
		$helper->submit_action            = 'submitdeleteo';
		$helper->currentIndex             = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

		$helper->token    = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages'    => $this->context->controller->getLanguages(),
			'id_language'  => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}

	public function renderForm2()
	{
		$this->postProcess();

		$fields_form                      = array(
			'form' => array(
				'legend'      => array(
					'title' => $this->l('Delete all'),
					'icon'  => 'icon-cogs'
				),
				'description' => $this->l('This delete all orders and carts, so be carefull'),
				'submit'      => array(
					'title' => $this->l('Delete all'),
				)
			),
		);
		$helper                           = new HelperForm();
		$helper->show_toolbar             = true;
		$helper->table                    = $this->table;
		$lang                             = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language    = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form                = array();
		$helper->identifier               = $this->identifier;
		$helper->submit_action            = 'submitall';
		$helper->currentIndex             = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

		$helper->token    = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages'    => $this->context->controller->getLanguages(),
			'id_language'  => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}

	public function displayForm()
	{
		$output = '
		<form method="post" onsubmit="'.$_SERVER['REQUEST_URI'].'">
			<fieldset><legend><img src="'.$this->_path.'img/png.png" alt="" title="" />'.$this->l('Settings').'</legend>
				
		<label>'.$this->l('Enable delete button in order page').'</label>
	<div class="margin-form">
  <select name="deleteord" >
   <option value="1"'.((Configuration::get('DELETEORDERS_DELETE') == '1') ? 'selected="selected"' : '').'>'.$this->l('Enable delete orders').'</option>
  <option value="0"'.((Configuration::get('DELETEORDERS_DELETE') == '0') ? 'selected="selected"' : '').'>'.$this->l('Disable delete orders').'</option>
	
    </select>	
	<p>'.$this->l('When you enable delete orders, you can delete orders in ORDER TAB with the trash icon').'</p>
	</div>
	
				<center><input type="submit" name="submitedeleteo" value="'.$this->l('Save').'" class="button" /></center>
			
			
			</fieldset>						
		</form>
		
		<form method="post" onsubmit="'.$_SERVER['REQUEST_URI'].'">
			<fieldset><legend><img src="'.$this->_path.'img/png.png" alt="" title="" />'.$this->l('Delete').'</legend>
				
		
	
		<p class="clear">'.$this->l('Delete all orders').'</p>
				<center><input type="submit" name="submitall" value="'.$this->l('Delete all').'" class="button" /></center>
				
				<center><span style=":color:red">'.$this->l('WARNING:').'</span>'.$this->l('This delete all orders and carts, so be carefull').'</center>
			<center><a href="../modules/deleteorders/moduleinstall.pdf">README</a></center><br/>
					<center><a href="../modules/deleteorders/termsandconditions.pdf">TERMS</a></center><br/>
					
					<center><object type="text/html" data="http://catalogo-onlinersi.net/modules/productsanywhere/images.php?idproduct=&desc=yes&buy=yes&type=home_default&price=yes&style=false&color=10&color2=40&bg=ffffff&width=800&height=310&lc=000000&speed=5&qty=15&skip=29,14,42,44,45&sort=1" width="800" height="310" style="border:0px #066 solid;"></object></div>
</center>
			</fieldset>						
		</form>
		
		
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="MRASNL38GZZ7Y">
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypal.com/es_XC/i/scr/pixel.gif" width="1" height="1">
</form>';
		return $output;
	}

}
