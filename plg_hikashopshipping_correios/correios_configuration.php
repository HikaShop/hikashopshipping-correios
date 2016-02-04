<?php
/**
 * @package	Shipping Correios HikaShop 2.2 / Joomla 3.x
 * @version	1.1.0
 * @author	jobadoo.com.br
 * @copyright	(C) 2006-2013 Jobadoo Webdesign. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_shop_post_code]">
							<?php echo 'Shop Postcode'; ?>
						</label>
					</td>
					<td>
						<input type="text" name="data[shipping][shipping_params][correios_shop_post_code]" value="<?php echo @$this->element->shipping_params->correios_shop_post_code; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_servico]">
							<?php echo 'Service to be Used'; ?>
						</label>
					</td>
					<td>
						<select  name="data[shipping][shipping_params][correios_servico]">
							<option <?php if(@$this->element->shipping_params->correios_servico=='40010')  echo "selected=\"selected\""; ?> value="40010">40010 SEDEX sem contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40045')  echo "selected=\"selected\""; ?> value="40045">40045 SEDEX a Cobrar, sem contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40126')  echo "selected=\"selected\""; ?> value="40126">40126 SEDEX a Cobrar, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40215')  echo "selected=\"selected\""; ?> value="40215">40215 SEDEX 10, sem contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40290')  echo "selected=\"selected\""; ?> value="40290">40290 SEDEX Hoje, sem contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40096')  echo "selected=\"selected\""; ?> value="40096">40096 SEDEX com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40436')  echo "selected=\"selected\""; ?> value="40436">40436 SEDEX com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40444')  echo "selected=\"selected\""; ?> value="40444">40444 SEDEX com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40568')  echo "selected=\"selected\""; ?> value="40568">40568 SEDEX com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='40606')  echo "selected=\"selected\""; ?> value="40606">40606 SEDEX com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='41106')  echo "selected=\"selected\""; ?> value="41106">41106 PAC sem contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='41068')  echo "selected=\"selected\""; ?> value="41068">41068 PAC com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81019')  echo "selected=\"selected\""; ?> value="81019">81019 e-SEDEX, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81027')  echo "selected=\"selected\""; ?> value="81027">81027 e-SEDEX Prioritário, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81035')  echo "selected=\"selected\""; ?> value="81035">81035 e-SEDEX Express, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81868')  echo "selected=\"selected\""; ?> value="81868">81868 (Grupo 1) e-SEDEX, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81833')  echo "selected=\"selected\""; ?> value="81833">81833 (Grupo 2) e-SEDEX, com contrato</option>
							<option <?php if(@$this->element->shipping_params->correios_servico=='81850')  echo "selected=\"selected\""; ?> value="81850">81850 (Grupo 3) e-SEDEX, com contrato</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_servico_empresa]">
							<?php echo 'Company Code(optional)'; ?>
						</label>
					</td>
					<td>
						<input type="text" name="data[shipping][shipping_params][correios_servico_empresa]" value="<?php echo @$this->element->shipping_params->correios_servico_empresa; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_servico_senha]">
							<?php echo 'Correios Password(optional)'; ?>
						</label>
					</td>
					<td>
						<input type="text" name="data[shipping][shipping_params][correios_servico_senha]" value="<?php echo @$this->element->shipping_params->correios_servico_senha; ?>" />
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_declara_valor]">
							<?php echo 'Declare Value (recommended)'; ?>
						</label>
					</td>
					<td>
						<?php echo JHTML::_('hikaselect.booleanlist', "data[shipping][shipping_params][correios_declara_valor]" , '',@$this->element->shipping_params->correios_declara_valor ); ?>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_mao_propria]">
							<?php echo 'Own Hand'; ?>
						</label>
					</td>
					<td>
						<select  name="data[shipping][shipping_params][correios_mao_propria]">
							<option <?php if(@$this->element->shipping_params->correios_mao_propria=='n')  echo "selected=\"selected\""; ?> value="n">No</option>
							<option <?php if(@$this->element->shipping_params->correios_mao_propria=='s')  echo "selected=\"selected\""; ?> value="s">Yes</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="key">
						<label for="data[shipping][shipping_params][correios_aviso_recebimento]">
							<?php echo 'Notice of Receipt'; ?>
						</label>
					</td>
					<td>
						<select  name="data[shipping][shipping_params][correios_aviso_recebimento]">
							<option <?php if(@$this->element->shipping_params->correios_aviso_recebimento=='n')  echo "selected=\"selected\""; ?> value="n">No</option>
							<option <?php if(@$this->element->shipping_params->correios_aviso_recebimento=='s')  echo "selected=\"selected\""; ?> value="s">Yes</option>
						</select>
					</td>
				</tr>