<?php
/**
 * RyzomAPI lite.
 *
 * Includes render window, item icon, guild icon, time, guilds list, guild and character functions.
 *
 * @copyright (c) 2013 Winch Gate Property Limited
 * @license LGPLv3
 */

if (!defined('RYAPI_URL')) {
	define('RYAPI_URL', 'http://api.ryzom.com');
}

if (!defined('RYAPI_ERROR_INVALID_KEY')) {
	define('RYAPI_ERROR_EXPIRED_KEY', 403);
	define('RYAPI_ERROR_INVALID_KEY', 404);
	define('RYAPI_ERROR_CACHE_NOT_FOUND', 503);
}

/**
 * Return URL for item icons (.sitem and .sbrick sheets)
 *
 * .sitem is 40x40 image, eg, m0625ccfld01.sitem
 * .sbrick is 24x24 image, eg bczaea03.sbrick
 *
 * .sbrick parameters are a bit different
 * $q - true - show sbrick level
 * $s - custom text on bottom-left corner (5 alpha-numeric chars)
 *
 * @param string $sheetid item sheet name
 * @param int $c icon color index (armor)
 * @param int $q quality
 * @param int $s stack size
 * @param int $sap sap count, 0=only show sap icon, 1=sap count is 0, 2=sap count is 1, etc
 * @param bool $destroyed default false
 * @param bool $label show or hide item text, default true
 * @param bool $locked default false
 *
 * @return string final url
 *
 * @api
 */
function ryzom_item_icon_url($sheetid, $c = -1, $q = 0, $s = 0, $sap = -1, $destroyed = false, $label = true, $locked = false) {
	$params = array(
		'sheetid' => $sheetid,
		'c' => $c,
		'q' => $q,
		's' => $s,
		'sap' => $sap,
		'destroyed' => $destroyed,
		'label' => $label,
		'locked' => $locked,
	);
	return RYAPI_URL.'/item_icon.php?'.http_build_query($params);
}

/**
 * Return URL for guild icon
 *
 * @param string $icon guild icon key
 * @param string $size 'b' for 64x64 image, 's' for 32x32 image
 *
 * @return string final url
 *
 * @api
 */
function ryzom_guild_icon_url($icon, $size) {
	$params = array(
		'icon' => $icon,
		'size' => $size
	);
	return RYAPI_URL.'/guild_icon.php?'.http_build_query($params);
}

/**
 * Retrieve current ingame time and date information.
 *
 * @return SimpleXMLElement|bool
 *
 * @api
 */
function ryzom_time_api() {
	// possible formats are 'raw', 'txt', 'xml'
	$url = RYAPI_URL.'/time.php?format=xml';
	$data = ryzom_download_file($url);
	if ($data === false) {
		return false;
	}
	return simplexml_load_string($data);
}

/**
 * Retrieve list of all guilds on shard
 *
 * @return SimpleXMLElement|bool
 *
 * @api
 */
function ryzom_guildlist_api() {
	$url = RYAPI_URL.'/guilds.php';
	$data = ryzom_download_file($url);
	if ($data === false) {
		return false;
	}
	return simplexml_load_string($data);
}

/**
 * Retrieve character info from API server using apikey(s)
 *
 * Return associative array of SimpleXMLElements using apikeys as array index
 *
 * @param string|array $apikey
 *
 * @return SimpleXMLElement[]|bool
 *
 * @api
 */
function ryzom_character_api($apikey) {
	$url = RYAPI_URL.'/character.php?'.http_build_query(array('apikey' => $apikey));
	$data = ryzom_download_file($url);
	if ($data === false) {
		return false;
	}

	$xml = simplexml_load_string($data);
	if ($xml === false) {
		return false;
	}

	$result = array();
	foreach ($xml->character as $char) {
		$index = (string)$char['apikey'];
		$result[$index] = $char;
	}

	return $result;
}

/**
 * Retrieve guild info from API server using apikey(s)
 *
 * Return associative array of SimpleXMLElements using apikeys as array index
 *
 * @param string|array $apikey
 *
 * @return SimpleXMLElement
 *
 * @api
 */
function ryzom_guild_api($apikey) {
	$url = RYAPI_URL.'/guild.php?'.http_build_query(array('apikey' => $apikey));
	$data = ryzom_download_file($url);
	if ($data === false) {
		return false;
	}

	$xml = simplexml_load_string($data);
	if ($xml === false) {
		return false;
	}

	$result = array();
	foreach ($xml->guild as $guild) {
		$index = (string)$guild['apikey'];
		$result[$index] = $guild;
	}

	return $result;
}

/**
 * Renders ryzom ui style panel.
 *
 * Requires stylesheet from
 * http://api.ryzom.com/data/css/ryzom_ui.css
 *
 * @param string $title
 * @param string $content
 * @param array $links
 *
 * @return string
 */
function ryzom_render_window($title, $content, array $links = array()) {
	if (!empty($links)) {
		$titleLinks = '<span style="float:right;margin-right:12px;">';
		foreach ($links as $link) {
			$titleLinks .= "<a href=\"{$link['href']}\" class=\"ryzom-ui-text-button\">{$link['text']}</a>";
		}
		$titleLinks .= '</span>';
		$title = $titleLinks.$title;
	}

	$tpl = '
	<div class="ryzom-ui ryzom-ui-header">
		<div class="ryzom-ui-tl">
			<div class="ryzom-ui-tr">
				<div class="ryzom-ui-t">{title}</div>
			</div>
		</div>

		<div class="ryzom-ui-l">
			<div class="ryzom-ui-r">
				<div class="ryzom-ui-m">
					<div class="ryzom-ui-body">{content}</div>
				</div>
			</div>
		</div>

		<div class="ryzom-ui-bl">
			<div class="ryzom-ui-br">
				<div class="ryzom-ui-b"></div>
			</div>
		</div>
		<p class="ryzom-ui-notice">powered by <a class="ryzom-ui-notice" href="https://api.ryzom.com/">ryzom-api</a></p>
	</div>
	';
	return strtr($tpl, array(
		'{title}' => $title,
		'{content}' => $content,
	));
}

/**
 * Authenticate user coming from AppZone.
 *
 * PHP session should be started before calling this function.
 * User info is kept in $_SESSION['app.user'] variable.
 *
 * Constants that should be defined:
 * RYAPI_AUTH_KEY secret key from AppZone
 *                if empty, then user info is not verified
 *
 * RYAPI_APP_URL  app url from AppZone
 *                if empty, then automatic best guess url is tried
 *                if false, then app url is not verified
 *
 * RYAPI_APP_MAXAGE max age in seconds for AppZone url to be valid
 *                if 0, then timestamp is not verified
 *
 * @param array $user on success, $user array is filled with info from AppZone
 *                    on failure, $user array is set to error string
 *
 * @return bool on success, return boolean true
 */
function ryzom_app_authenticate(&$user) {
	$sess_key = 'app.user';

	// AppZone uses GET variables 'user' and 'checksum'
	if (!empty($_GET['user'])) {
		$data = $_GET['user'];

		// verify checksum
		if (defined('RYAPI_APP_KEY') && RYAPI_APP_KEY != '') {
			if (empty($_GET['checksum']) ||
				$_GET['checksum'] !== hash_hmac('sha1', $data, RYAPI_APP_KEY)
			) {
				$user = 'checksum failed';
				return false;
			}
		}

		// if checksum is not verified, then unserialize is potentially unsafe
		// https://www.owasp.org/index.php/PHP_Object_Injection
		$data = unserialize(base64_decode($data));

		// verify app url
		if (defined('RYAPI_APP_URL')) {
			$appurl = RYAPI_APP_URL;
			if ($appurl == '') {
				// this will hopefully reconstruct app url that's in AppZone
				parse_str($_SERVER['QUERY_STRING'], $params);
				// parameters added by ingame browser or AppZone
				$keys = array('cid', 'authkey', 'shardid', 'name', 'lang', 'datasetid', 'ig', 'checksum', 'user');
				foreach ($keys as $k) {
					unset($params[$k]);
				}
				$query = http_build_query($params);

				$appurl = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://').$_SERVER['HTTP_HOST'];
				$appurl .= substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - strlen($_SERVER['QUERY_STRING']) - 1);
				$appurl .= !empty($query) ? '?'.$query : '';
			}

			if (empty($data['app_url']) || $data['app_url'] !== $appurl) {
				$user = 'app_url failed';
				return false;
			}
		}

		// verify request time
		if (defined('RYAPI_APP_MAXAGE') && RYAPI_APP_MAXAGE > 0) {
			if (empty($data['timestamp'])) {
				$user = 'invalid timestamp';
				return false;
			}

			$ts = $data['timestamp'];
			if (strstr($ts, ' ')) {
				list($usec, $sec) = explode(' ', $ts);
				$ts = (float)$usec + (float)$sec;
			}
			$age = microtime(true) - $ts;
			if ($age > RYAPI_APP_MAXAGE) {
				$user = 'timestamp failed';
				return false;
			}
		}

		unset($data['timestamp']);
		unset($data['app_url']);

		$_SESSION[$sess_key] = $data;
	}

	// try to restore user from session
	if (isset($_SESSION[$sess_key])) {
		$user = $_SESSION[$sess_key];
		return true;
	}

	return false;
}

/**
 * Fetch content from url using curl
 *
 * @param string $url
 *
 * @return bool|string
 *
 * @internal
 */
function ryzom_download_file($url) {
	if (!function_exists('curl_init')) {
		$result = file_get_contents($url);
	} else {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		//curl_setopt($ch, CURLOPT_MUTE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($ch, CURLOPT_TIMEOUT, 8);
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		if (empty($result) || $info['http_code'] != 200) {
			return false;
		}
	}
	return $result;
}
