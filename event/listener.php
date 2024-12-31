<?php
/**
*
* @package Image Redirect
* @copyright (c) 2017-2018 v12mike
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace v12mike\imageredirect\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	public function __construct(\phpbb\config\config $config)
	{
		$this->config = $config;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.text_formatter_s9e_render_before' => 'adjust_xml_before_rendering',
			'core.get_avatar_after' => 'adjust_avatar_link',
			);
	}

	private function adjust_img_src_url($url)
	{
		// maybe we want to serve a local copy of the image?
		if ($this->config['imageredirect_localimagesmode'] > 0)
		{
			// for efficiency on pages with many [IMG] links, keep static copies of calculatedstrings that don't change 
			static $board_url = '';
			if ($board_url === '')
			{
				$board_url = generate_board_url();
			}

			static $board_path = '';
			if ($board_path === '')
			{
				$board_path = realpath('./');
			}

			static $images_base_url = '';
			if ($images_base_url === '')
			{
				if (($this->config['imageredirect_proxymode'] === 0) && (strlen($this->config['imageredirect_proxyaddress']) === 0))
				{
					$images_base_url = $board_url . $this->config['imageredirect_localimagespath'] . '/' ;
				} 
				else 
				{
					$images_base_url = $this->config['imageredirect_images_base_url'];
				}
			}

			$file_name = md5("$url");
			// if we have a locally hosted copy of the file, we can find it
			$local_file_name = $this->config['imageredirect_localimagespath'] . md5("$url");
			$file_path = $board_path . '/' . $local_file_name;

            // get the file extension
            $matches = array();
            preg_match('/https?\:\/\/[^\/]+\/+[\w\/\.\+\-\~\%\,]+(\.[\w\/\+\-\~\%\,]+)/',  $url, $matches);	
			if (isset ($matches[1]))
			{
				$file_ext = $matches[1];
				// look first for a file with the extension
				if (file_exists($file_path . $file_ext))
				{
					// we will link to the local file
					$url = $images_base_url . '/' . $file_name . $file_ext;
					return $url;
				}
			   	// fallback to file without extension (for backward compatibility)
				elseif (file_exists($file_path))
				{
					// we will link to the local file
					$url = $images_base_url . '/' . $file_name;
					return $url;
				}
				// drop through to proxy mode
			}
		}

		// skip unless proxy mode enabled & if the url protocol is http:// (not https:// )
		if (($this->config['imageredirect_proxymode'] > 0) && (strpos($url, 'http://') == 0))
		{
			if (strpos($url, 'http://' . $this->config['server_name']) == 0)
			{
				// the image is hosted on this domain, assume that the location is valid and just update the protocol
				$url = preg_replace('#http://#', 'https://', $url);
			}
			elseif ($this->config['imageredirect_proxysimplemode'] > 0)
			{
				// rewite the url for  "simple mode" proxy
				// the substr($url, 7) trims the leading http:// from the url
				$url = 'https://' . $this->config['imageredirect_proxyaddress'] . substr($url, 7) . $this->config['imageredirect_proxyapikey'];
			}
			else
			{
				// rewrite url to use the Camo proxy server
				$digest = hash_hmac('sha1', $url, $this->config['imageredirect_proxyapikey']);
				$url = 'https://' . $this->config['imageredirect_proxyaddress'] . '/' . $digest . '/' . bin2hex($url);
			}
		}
		return $url;
	}

	public function adjust_xml_before_rendering($event)
	{
		$event['xml'] = \s9e\TextFormatter\Utils::replaceAttributes($event['xml'], 'IMG', function (array $attributes)
			{
				if (isset($attributes['src']))
				{
					$attributes['src'] = $this->adjust_img_src_url($attributes['src']);
				}
				return $attributes;
			}
		);
	}

	public function adjust_avatar_link($event)
	{
		$matches = array();
		preg_match('#src="(http(s?)://[^"]+)"#', $event['html'], $matches);
		if (isset($matches[1]))
		{
			$new_url = $this->adjust_img_src_url($matches[1]);
			$event['html'] = str_replace($matches[1], $new_url, $event['html']);
		}
	}
}

