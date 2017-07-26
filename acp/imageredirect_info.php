<?php

/**
*
* @package Image Redirect
* @copyright (c) 2016 v12Mike
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace v12mike\imageredirect\acp;

/**
* @package module_install
*/
class imageredirect_info
{
	function module()
	{
		return array(
			'filename'	=> 'v12mike/imageredirect/acp/imageredirect_module',
			'title'		=> 'Image Redirect',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'local_image_store'	=> array(
					'title' => 'IR_LOCAL_STORE_CONFIG',
					'auth' => 'ext_v12mike/imageredirect && acl_a_board',
					'cat'	=> array('IR_EXT')),
				'image_proxy'	=> array(
					'title' => 'IR_PROXY_CONFIG',
					'auth' => 'ext_v12mike/imageredirect && acl_a_board',
					'cat'	=> array('IR_EXT')),
				'image_link_locations'	=> array(
					'title' => 'IR_LOCATIONS_CONFIG',
					'auth' => 'ext_v12mike/imageredirect && acl_a_board',
					'cat'	=> array('IR_EXT')),
			),
		);
	}
}