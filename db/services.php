<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Web service local plugin template external functions and service definitions.
 *
 * @package    localmediacenter
 * @copyright  2014 szreach
 */

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'Mediacenter service' => array(
                'functions' => array (
                		'local_mediacenter_mod_url_access_user',
                		'local_mediacenter_connect_test'
                		),
                'restrictedusers' => 0,
                'enabled'=>1
        )
);		
		
// We defined the web service functions to install.
$functions = array(
        'local_mediacenter_mod_url_access_user' => array(
                'classname'   => 'local_mediacenter_service_external',
                'methodname'  => 'mod_url_access_user',
                'classpath'   => 'local/mediacenter_service/externallib.php',
                'description' => 'Check whether a user has permissions on url with type mod',
                'type'        => 'read'
        ),
        'local_mediacenter_connect_test' => array(
                'classname'   => 'local_mediacenter_service_external',
                'methodname'  => 'connect_test',
                'classpath'   => 'local/mediacenter_service/externallib.php',
                'description' => 'Check whether moodle could connect by web service',
                'type'        => 'read'
        )        		
);
