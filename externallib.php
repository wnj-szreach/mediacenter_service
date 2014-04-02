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
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_mediacenter_service_external extends external_api {

/**=================Begin local_mediacenter_mod_url_access_user==================**/
	
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function mod_url_access_user_parameters() {
        return new external_function_parameters(
                array(
                		'username' => new external_value(PARAM_TEXT, 'The username'),
                		'url' => new external_value(PARAM_TEXT, 'The full mod-url source url'),
                		'capability' => new external_value(PARAM_TEXT, 'The capability. By default it is "mod/url:view"', VALUE_DEFAULT, 'mod/url:view')
                )
        );
    }

    /**
     * Returns welcome message
     * @return string welcome message
     */
	public static function mod_url_access_user($username, $url, $capability = 'mod/url:view') {
		global $DB;
		//$DB->set_debug(true);

        // 参数获取和校验
        $params = self::validate_parameters(self::mod_url_access_user_parameters(),
            array(
        		'username' => trim($username),
        		'url' => trim($url),
        		'capability' => trim($capability)//'moodle/user:viewdetails'
            )
        );
        
        if ($params['username'] == '') {
        	return self::getResult(0, 'Empty username');
//            throw new invalid_parameter_exception('Invalid username');
        }
        
        if ($params['url'] == '') {
        	return self::getResult(0, 'Empty url');
        }
        
        if ($params['capability'] == '') {
        	return self::getResult(0, 'Empty capability');
        }
                
        // 根据用户名获取用户ID
        $userId = $DB->get_field('user', 'id', array('username'=>$params['username']), IGNORE_MISSING);
        if(empty($userId)){
        	return self::getResult(0, 'No exists username');
        }
        
        // 检测系统'URL'模块是否存在 
        $dbman = $DB->get_manager();
        if(!$dbman->table_exists('url')) {
        	return self::getResult(0, 'Moodle had not install url module');
        }
        
        //{mdl_user}--{mdl_user_enrolments}--{mdl_enrol}--{mdl_course}--{mdl_url}
        //course(访客可访问性，暂时忽略)
        
        // 检查用户的课程里是否含有该URL（用户+资源）
        $sql = 'SELECT 1 FROM {url} a
                LEFT JOIN {enrol} b ON a.course = b.courseid
                LEFT JOIN {user_enrolments} c ON b.id = c.enrolid
                LEFT JOIN {user} d ON c.userid = d.id
                WHERE a.externalurl = :url
        		AND d.id = :userid';
        $sql_ps = array(
        			'url' 		=> $params['url'],
        			'userid' 	=> $userId
        		);
        if (!$DB->record_exists_sql($sql, $sql_ps)){
        	return self::getResult(0, 'Deny in url');
        }

        // 检查用户是否有查看URL的权限（权限）
        $context = get_context_instance(CONTEXT_USER, $userId);//2 is admin
        self::validate_context($context);
        if (!has_capability($params['capability'], $context)) {
        	return self::getResult(0, 'Deny in capability');
        }        		
        		
        return self::getResult(1);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function mod_url_access_user_returns() {
        return new external_single_structure(
				array(
				    'code' => new external_value(PARAM_INT, 'Has permissions return 1 othewise return 0'),
				    'desc' => new external_value(PARAM_TEXT, 'Description about result'),
					)
				);
    }
    
    /**
     * getResult
     */
    private static function getResult($code, $desc = '') {
    	return array(
    			'code' => $code,
    			'desc' => $desc
    		);
    }    
    
/**=================End local_mediacenter_mod_url_access_user==================**/
    
/**=================Begin local_mediacenter_connect_test==================**/    
    
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function connect_test_parameters() {
        return new external_function_parameters(
                array(
                )
        );
    }
    
    /**
     * Returns welcome message
     * @return string welcome message
     */
	public static function connect_test() {
		return 1;
	}
	
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function connect_test_returns() {
        return new external_value(PARAM_INT, 'Connect success then return 1 othewise return 0');
    }	
    
/**=================End local_mediacenter_connect_test==================**/
}
