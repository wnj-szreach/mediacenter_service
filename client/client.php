<?php
// This client for local_mediacenter is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//

/**
 * XMLRPC client for Moodle 2 - local_mediacenter
 *
 * This script does not depend of any Moodle code,
 * and it can be called from a browser.
 *
 * @authorr weinianjie
 */

/// MOODLE ADMINISTRATION SETUP STEPS
// 1- Install the plugin
// 2- Enable web service advance feature (Admin > Advanced features)
// 3- Enable XMLRPC protocol (Admin > Plugins > Web services > Manage protocols)
// 4- Create a token for a specific user and for the service 'My service' (Admin > Plugins > Web services > Manage tokens)
// 5- Run this script directly from your browser: you should see 'Hello, FIRSTNAME'

/// SETUP - NEED TO BE CHANGED
$token = '44f08685b8919a8cd73e37ebe07cd215';
$domainname = 'http://192.168.71.60';

/// FUNCTION NAME
$functionname = 'local_mediacenter_mod_url_access_url';

/// PARAMETERS
$username = 'weinianjie';
$url = 'http://www.baidu.com';
$capability = 'mod/url:view';

///// XML-RPC CALL
header('Content-Type: text/plain');
$serverurl = $domainname . '/moodle2.5/webservice/xmlrpc/server.php'. '?wstoken=' . $token;
require_once('./curl.php');
$curl = new curl;
//the parameters of the external function and their declaration in the description must be the same order
$post = xmlrpc_encode_request($functionname, array(
		$username,
		$url,
		$capability
		//'username' => $username,
		//'url' => $url,
		//'capability' => $capability		
));
$resp = xmlrpc_decode($curl->post($serverurl, $post));
print_r($resp);
