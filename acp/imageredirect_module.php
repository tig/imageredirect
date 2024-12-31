<?php
/**
*
* @package imageredirect
* @copyright (c) 2017 - 2018 v12Mike
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace v12mike\imageredirect\acp;

class imageredirect_module
{
	/** @var string */
	var $u_action;

	/**
	 * @param string $id
	 * @param string $mode
	 */
	function main($id, $mode)
	{
		global $user, $template, $request;
		global $config, $phpbb_root_path, $phpEx;

		$user->add_lang('acp/common');
		$user->add_lang_ext('v12mike/imageredirect', 'info_acp_imageredirect');
		$this->tpl_name = 'acp_imageredirect';
		$this->page_title = $user->lang['IR_ACP'];
		add_form_key('acp_imageredirect');

		switch ($mode)
		{
			case 'local_image_store':
				{
					if ($request->is_set_post('submit'))
					{
						if (!check_form_key('acp_imageredirect'))
						{
							trigger_error('FORM_INVALID');
						}

						$config->set('imageredirect_localimagesmode', $request->variable('imageredirect_localimagesmode', 0));
						$config->set('imageredirect_localimagespath', $request->variable('imageredirect_localimagespath', "", true));
						$config->set('imageredirect_images_base_url', $request->variable('imageredirect_images_base_url', "", true));
					}

				// fill-in the template
				$template->assign_vars(array(
					'MODE'				=> 1,
					'LOCAL_IMAGES_MODE'	=> $config['imageredirect_localimagesmode'],
					'LOCAL_IMAGES_PATH'	=> (!empty($config['imageredirect_localimagespath'])) ? $config['imageredirect_localimagespath'] : "",
					'IMAGES_BASE_URL'	=> (!empty($config['imageredirect_images_base_url'])) ? $config['imageredirect_images_base_url'] : "",
					'IR_VERSION'		=> $config['imageredirect_version'],
					'U_ACTION'			=> $this->u_action,
				));
				break;
				}

		case 'image_proxy':
			{
				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('acp_imageredirect'))
					{
						trigger_error('FORM_INVALID');
					}
					if (!function_exists('validate_data'))
					{
						include($phpbb_root_path . 'includes/functions_user.' . $phpEx);
					}

					$config->set('imageredirect_proxymode', 	$request->variable('imageredirect_proxymode', 0));
					$config->set('imageredirect_simplemode',	$request->variable('imageredirect_simplemode', 0));
					$config->set('imageredirect_proxyaddress',	$request->variable('imageredirect_proxyaddress', "", true));
					$config->set('imageredirect_proxyapikey',	$request->variable('imageredirect_proxyapikey', "", true));
				}

			// fill-in the template
			$template->assign_vars(array(
				'MODE'				=> 2,
				'PROXY_MODE'		=> $config['imageredirect_proxymode'],
				'SIMPLE_MODE'		=> (!empty($config['imageredirect_simplemode'])) ? true : false,
				'PROXY_ADDRESS'		=> (!empty($config['imageredirect_proxyaddress'])) ? $config['imageredirect_proxyaddress'] : "",
				'PROXY_API_KEY'		=> (!empty($config['imageredirect_proxyapikey'])) ? $config['imageredirect_proxyapikey'] : "",
				'IR_VERSION'		=> $config['imageredirect_version'],
				'IR_ERROR'  		=> isset($error) ? ((sizeof($error)) ? implode('<br />', $error) : '') : '',
				'U_ACTION'			=> $this->u_action,
			));
			break;
			}
		}
	}
}

