<?php
/**
 * @package	Shipping Correios HikaShop 2.2 / Joomla 3.x
 * @version	1.1.0
 * @author	jobadoo.com.br
 * @copyright	(C) 2006-2014 Jobadoo Webdesign. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class plgHikashopshippingCorreios extends hikashopShippingPlugin
{
	var $services = array('40010' => '40010 SEDEX sem contrato', '40045' => '40045 SEDEX a Cobrar, sem contrato',
		'40126' => '40126 SEDEX a Cobrar, com contrato', '40215' => '40215 SEDEX 10, sem contrato',
		'40290' => '40290 SEDEX Hoje, sem contrato', '40096' => '40096 SEDEX com contrato',
		'40436' => '40436 SEDEX com contrato', '40444' => '40444 SEDEX com contrato',
		'40568' => '40568 SEDEX com contrato', '40606' => '40606 SEDEX com contrato',
		'41106' => '41106 PAC sem contrato', '41068' => '41068 PAC com contrato',
		'81019' => '81019 e-SEDEX, com contrato', '81027' => '81027 e-SEDEX Prioritário, com contrato',
		'81035' => '81035 e-SEDEX Express, com contrato', '81868' => '81868 (Grupo 1) e-SEDEX, com contrato',
		'81833' => '81833 (Grupo 2) e-SEDEX, com contrato', '81850' => '81850 (Grupo 3) e-SEDEX, com contrato'
	);

	var $multiple = true;
	var $name = 'correios';
	var $doc_form = 'correios';

	function onShippingDisplay(&$order, &$dbrates, &$usable_rates, &$messages) {
		$weightClass=hikashop_get('helper.weight');
		$volumeClass=hikashop_get('helper.volume');

		if(!hikashop_loadUser())
			return false;

		$local_usable_rates = array();
		$local_messages = array();
		$currencyClass = hikashop_get('class.currency');
		$ret = parent::onShippingDisplay($order, $dbrates, $local_usable_rates, $local_messages);
		if($ret === false)
			return false;

		$currentShippingZone = null;
		$currentCurrencyId = null;

		foreach($local_usable_rates as $rate) {
			if(!empty($rate->shipping_zone_namekey)){
				$zoneClass=hikashop_get('class.zone');
				$zones = $zoneClass->getOrderZones($order);
				if(!in_array($rate->shipping_zone_namekey,$zones)){
					$messages['no_shipping_to_your_zone'] = JText::_('NO_SHIPPING_TO_YOUR_ZONE');
					continue;
				}
			}

			if(empty($order->shipping_address_full)){
				$cart = hikashop_get('class.cart');
				$app = JFactory::getApplication();
				$address=$app->getUserState( HIKASHOP_COMPONENT.'.shipping_address');
				$cart->loadAddress($order->shipping_address_full,$address,'object','shipping');
			}

			if($order->shipping_address_full->shipping_address->address_country->zone_code_3!='BRA'){
				continue;
			}

			$cepCliente = trim($order->shipping_address->address_post_code);

			$max_altura = 2;
			$max_largura = 11;
			$max_comprimento = 16;
			$orderWeight = 0;

			foreach ($order->products as $product) {
				$product_weight=$weightClass->convert($product->product_weight,$product->product_weight_unit,'kg');

				$orderWeight += $product->cart_product_quantity * $product_weight;

				//Define medidas máximas
				if( $product->product_height > $max_altura){
					$max_altura = $product->product_height;
				}
				if( $product->product_width > $max_largura){
					$max_largura = $product->product_width;
				}
				if( $product->product_length > $max_comprimento){
					$max_comprimento = $product->product_length;
				}
			}

			// =============  Início Obtém o valor do frete do site dos Correios  =============
			//Monta URL para pegar os dados do site dos Correios
			$workstring = 'nCdEmpresa=' . $rate->shipping_params->correios_servico_empresa;
			$workstring .= '&sDsSenha=' . $rate->shipping_params->correios_servico_senha;
			$workstring .= '&sCepOrigem=' . $rate->shipping_params->correios_shop_post_code;
			$workstring .= '&sCepDestino=' . $cepCliente;
			$workstring .= '&nVlPeso=' . $orderWeight;
			$workstring .= '&nCdFormato=1';
			$workstring .= '&nVlAltura=' . number_format($max_altura, 2, ',', '');
			$workstring .= '&nVlLargura=' . number_format($max_largura, 2, ',', '');
			$workstring .= '&nVlComprimento=' . number_format($max_comprimento, 2, ',', '');
			$workstring .= '&sCdMaoPropria=' . $rate->shipping_params->correios_mao_propria;

			if($rate->shipping_params->correios_declara_valor || $rate->shipping_params->correios_servico == 40045 || $rate->shipping_params->correios_servico == 40126){
				$workstring .= '&nVlValorDeclarado=' . number_format(round($order -> total -> prices[0] -> price_value_with_tax, 2), 2, ',', '.');
			}else{
				$workstring .= '&nVlValorDeclarado=0';
			}

			$workstring .= '&sCdAvisoRecebimento=' . $rate->shipping_params->correios_aviso_recebimento;
			$workstring .= '&nCdServico=' . $rate->shipping_params->correios_servico;
			$workstring .= '&nVlDiametro=0';
			$workstring .= '&StrRetorno=xml';


			$url_busca = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx";
			$url_busca .= "?" . $workstring;
			$conteudo = '';
			$conteudo = $this->_transmiteCorreios($url_busca);

			if(preg_match ("/<Erro>99<\/Erro>/i", $conteudo)){
				$url_busca = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPreco";
				$url_busca .= "?" . $workstring;
				$conteudo = '';
				$conteudo = $this->_transmiteCorreios($url_busca);
			}

			if(strpos($conteudo,'HTTP Error 503. The service is unavailable.')!==false){
				$messages[]='HTTP Error 503. The Correios server is unavailable.';
				continue;
			}

			if($conteudo)
				$dadosCorreios = simplexml_load_string($conteudo);

			if(is_object($dadosCorreios->cServico)){
				$Valor = (string)$dadosCorreios->cServico->Valor;
				$Erro = (string)$dadosCorreios->cServico->Erro;
				$MsgErro = (string)$dadosCorreios->cServico->MsgErro;
				if($Erro!=0){
					$messages[]=$MsgErro;
				}
				$Valor = str_replace("," , ".", $Valor);
			}

			if($Valor > 0){
				$rate -> shipping_price = $Valor;
				//$rate -> shipping_price = 10.00;
				$rate -> shipping_id = $rate->shipping_params->correios_servico;

				$usable_rates[] = $rate;
			}
		}

		return true;
	}

	function shippingMethods(&$main){
		$methods = array();
		$selected = array('name' => $this->services[$main->shipping_params->correios_servico], 'key' => $main->shipping_params->correios_servico);
		$methods[$main->shipping_id . '-' . $selected['key']] = $selected['name'];

		return $methods;
	}

	function onShippingConfiguration(&$elements){
		$this->correios = JRequest::getCmd('name','correios');

		parent::onShippingConfiguration($elements);
	}

	function getShippingDefaultValues(&$element){
		$element->shipping_name='Correios';
		$element->shipping_description='';
		$element->group_package=0;
		$element->shipping_images='correios';
		$element->shipping_type=$this->name;
		$element->shipping_params = new stdClass();
	}

	function onShippingConfigurationSave(&$elements){
		parent::onShippingConfigurationSave($element);
	}

	function onAfterOrderConfirm(&$order,&$methods,$method_id){
		return true;
	}

	private function _transmiteCorreios($url_busca) {
		$conteudo = '';
		//Usa cURL para a consulta
		// =======  Verifica se a biblioteca CURL está instalada no servidor  =======
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url_busca);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_HEADER, false);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
			 $conteudo= curl_exec ($ch);
			//Pega erros da biblioteca cURL e processa
			$curl_erro = curl_errno($ch);

			if(curl_errno($ch) != 0){
				echo curl_error($ch);
				$conteudo = '';
				return false;
			}
			//Sempre fecha a sessão para liberar todos os recursos
			curl_close($ch);

		// =======  Se a biblioteca CURL não está instalada no servidor  =======
		} else {
			$app = JFactory::getApplication();
			$app->enqueueMessage('The Correios shipping plugin needs the CURL library installed but it seems that it is not available on your server. Please contact your web hosting to set it up.','error');

			return false;
		}
		return $conteudo;
	}
}
