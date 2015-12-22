<?php
require_once ('ControllerDataStruct.php');
class Controller {
	/** CONSTANTS */
	static $WEB_VARCHIVE_IMG = "/usrdata/usrimg/";
	static $FFMPEG = "/usr/bin/ffmpeg";
	static $THUMBNAIL_DIR = "/vcam_thumbnail";
	static $SERVER_ROOT = "/www/vhosts/dev-vhome";
	static $VCAM_ARCHIVE_VIEWER = 2;
	static $SERVER_API_KEY = "AIzaSyC2RC_UqfoNwieXqq55yms-mZlX5H1t4yE";
	static $USR_VARCHIVE = "/www/vhosts/dev-vhome/usrdata/usrvideo/";
	static $USR_VARCHIVE_IMG = "/www/vhosts/dev-vhome/usrdata/usrimg/";
	static $WEB_VARCHIVE = "/usrdata/usrvideo/";
	
	/** @var PDO */
	protected static $pdo = null;
	/** @var int */
	protected static $resellerId = 0;
	/** @var int */
	protected $resellerIdInstanse;
	/** @var string */
	protected static $sessionId = null;
	/** @var int */
	protected $sessionIdInstanse;
	/* GETTERS & SETTERS */
	static function setResselerId($resellerId) {
		self::$resellerId = $resellerId;
	}
	static function setSessionId($sessionId) {
		self::$sessionId = $sessionId;
	}
	/**
	 *
	 * @param array $myArray        	
	 * @param string $objectName        	
	 */
	static private function Array2Object($myArray, $objectName) {
		$obj = new $objectName ();
		foreach ( $obj as $key => $value ) {
			if (isset ( $myArray [$key] ))
				$obj->$key = $myArray [$key];
		}
		return $obj;
	}
	/*********  PRIVATE METHODS  *********/
	/**
	 * database descriptor
	 *
	 * @return PDO
	 */
	static private function pdo() {
		if (! is_object ( self::$pdo )) {
			self::$pdo = new PDO ( "mysql:host=localhost;dbname=virtualhome", "vh_web", '6EusrWvUBHKJQnQF', array (
					PDO::ATTR_PERSISTENT => true 
			) );
			self::$pdo->exec ( "SET NAMES UTF8" );
			self::$pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		return self::$pdo;
	}
	/**
	 * 
	 * @param string $dirName
	 * @param string $camToken
	 * @return array
	 */
	private static function getImageArray($dirName, $camToken){
		$images = array();
		if(is_dir($dirName)) {
			foreach (new DirectoryIterator($dirName) as $file) {
				if ($file->isFile()) {
					$storage_dir_path = $dirName;
					$web_dir_path = substr($camToken,0,16) . '_' . substr(basename($dirName),0,16);
					$images[] = self::$WEB_VARCHIVE_IMG . $web_dir_path . '/'.$file->getFilename();
					$web_dir_path = self::USR_VARCHIVE_IMG . $web_dir_path;
					
					if(!is_link($web_dir_path)){
						exec("ln -s $storage_dir_path $web_dir_path");
					}
				}
			}
		}
		return $images;
	}
	/**
	 * 
	 * @param unknown $item
	 * @param unknown $key
	 */
	private static function mybasename(&$item, $key) {
		$issueDate = $item[1];
		$duration = $item[2];
		$size = $item[3];
		$item[0] = basename($item[0]);
		$item[1] = date("d/m/y", strtotime($issueDate) ); //дата
		$item[2] = date("H:i:s", strtotime($issueDate) ); //время
		$item[3] = date("i:s", $duration);
		$item[4] = round($size /(1024 * 1024), 1);
	}
	/**
	 * 
	 * @param string $user_token
	 * @throws Exception
	 * @return int
	 */
	private static function getICustomerByToken($user_token) {
		$sqlStr = "SELECT I_CUSTOMER FROM customer WHERE TOKEN = '$user_token'";
		$iCustomer = self::pdo()->query($sqlStr)->fetchColumn();
		if( empty( $iCustomer ) ) {
			throw new Exception("unknown user token" );
		}
		return $iCustomer ;
	}
	/*********  PUBLIC METHODS  *********/
	/**
	 * 
	 * @param createVCamThumbnailRequest $request
	 * @throws Exception
	 * @return createVCamThumbnailResponse
	 */
	public static function createVCamThumbnail($request) {
		$argsList = array('token','size');
		foreach ($argsList as $argName){ 
			if( empty($request[$argName] ) )
				throw new Exception(__METHOD__ . " argument '$argName' is not passed.");
			else 
				$$argName = $request[$argName];
		}
		$sqlStr = "SELECT t17.NAME FROM customer_vcam t4
			INNER JOIN varchive t17 ON t4.I_CUSTOMER_VCAM = t17.I_CUSTOMER_VCAM
			WHERE t4.TOKEN = '$token' AND t17.CLOSED = 1 ORDER BY t17.ISSUE_DATE DESC LIMIT 1";
  		
  		$videoName = self::pdo()->query($sqlStr)->fetchColumn();
  		if(empty($videoName)){
  			return null;
  		}
   		$imageName = self::$THUMBNAIL_DIR ."/".  $token . ".jpg";
  		$interval = 5;
  		$cmd = self::$FFMPEG . 
  			" -i $videoName -deinterlace -an -ss $interval -f mjpeg -t 1 -r 1 -y -s $size ". 
  			self::$SERVER_ROOT .
  			$imageName ." 2>&1";
  		exec($cmd);
  		$result = new createVCamThumbnailResponse();
  		$result->imageName = $imageName;
  		return($result);
	}
	/**
	 * Check if customer belong to the Reseller
	 *
	 * @param int $iCustomer
	 * @param int $iReseller
	 * @return bool
	 * @throws Exception
	 */
	static private function checkICustomer($iCustomer) {
		$sqlStr = "SELECT I_CUSTOMER FROM customer t3
				INNER JOIN reseller r ON t3.I_RESELLER = r.I_RESELLER
				WHERE t3.I_CUSTOMER = ? AND r.I_RESELLER = ?";
		
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				$iCustomer,
				self::$resellerId 
		) );
		$test = $sth->fetchColumn ();
		if ($test == false) {
			throw new Exception ( "Customer ID = '$iCustomer' do not belong to the reseller ID = '" . self::$resellerId . "'" );
		}
		return true;
	}
	/**
	 * Check for session id (session token)
	 *
	 * @param
	 *        	$session_id
	 * @return int
	 * @throws Exception
	 */
	static function set_session_id($session_id) {
		$sqlStr = "SELECT I_RESELLER FROM reseller WHERE TOKEN = '$session_id'";
		self::$resellerId = intval ( self::pdo ()->query ( $sqlStr )->fetchColumn () );
		if (empty ( self::$resellerId )) {
			throw new Exception ( "Wrong session ID: '$session_id'" );
		}
		self::$sessionId = $session_id;
		return self::$resellerId;
	}
	static function isLogedIn($methodName) {
		if (empty ( self::$resellerId )) {
			throw new Exception ( "Unauthorized access to the function: '$methodName'." );
		}
	}
	/**
	 * Return Customer Action
	 *
	 * @param
	 *        	$iCustomer
	 * @param
	 *        	$action
	 * @return bool
	 */
	static private function customerAction($iCustomer, $action) {
		
		/** @var string $sqlStr */
		$sqlStr = "SELECT `EMAIL` FROM `customer` WHERE `I_CUSTOMER` = '$iCustomer' LIMIT 1";
		/** @var string $email */
		$email = self::pdo ()->query ( $sqlStr )->fetchColumn ();
		
		$description = "<customer id='$iCustomer'><username>$email</username><action>$action</action></customer>";
		$sqlStr = "INSERT IGNORE INTO `syslog` (`SEVERITY`,`DESCRIPTION`) VALUES ( 6, :description)";
		
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->bindValue ( ':description', $description );
		$sth->execute ();
		
		return true;
	}
	/**
	 *
	 * @param GetCustomerVcamLimitRequest $args        	
	 * @return GetCustomerVcamLimitResponse
	 */
	static function get_customer_vcam_limit($args) {
		/** @var int */
		$i_customer = 0;
		
		$argNames = array (
				'i_customer' 
		);
		
		foreach ( $argNames as $name ) {
			if (empty ( $args->$name )) {
				throw new Exception ( __METHOD__ . " Argument '$name' is empty" );
			}
			$$name = $args->$name;
		}
		self::checkICustomer ( $i_customer );
		
		$getCustomerVcamLimitResponse = new GetCustomerVcamLimitResponse ();
		
		$sqlStr = "SELECT getProductIntValByICustomer( '$i_customer','VCAM_LIMIT','MAX_VCAM')";
		$current_vcam = self::pdo ()->query ( $sqlStr )->fetchColumn ();
		
		$getCustomerVcamLimitResponse->vcam_limit = is_null ( $current_vcam ) ? 0 : $current_vcam;
		
		$sqlStr = "SELECT COUNT(I_CUSTOMER_VCAM) FROM customer_vcam WHERE I_CUSTOMER = '$i_customer' AND DELETED IS NOT NULL";
		
		$getCustomerVcamLimitResponse->current_vcam = intval ( self::pdo ()->query ( $sqlStr )->fetchColumn () );
		
		return $getCustomerVcamLimitResponse;
	}
	
	/**
	 * Get Customer Video Camera Info
	 *
	 * @param $args GetCustomerVcamRequest        	
	 * @return VcamInfo
	 * @throws Exception
	 */
	static function get_customer_vcam($args) {
		/** @var int */
		$i_customer_vcam = 0;
		
		/**
		 * * Processing arguments **
		 */
		$argNames = array (
				'i_customer_vcam' 
		);
		foreach ( $argNames as $name ) {
			if (empty ( $args->$name )) {
				throw new Exception ( __METHOD__ . " Argument '$name' is empty" );
			}
			$$name = $args->$name;
		}
		
		// throw new Exception("i_customer_vcam = $i_customer_vcam");
		
		$sqlStr = "SELECT MAX(t18_1.URL) AS HLS_URL, MAX(t18_2.URL) URL, MAX(t18_1.PORT) HLS, MAX(t18_2.PORT) RTMP,
		t3.I_CUSTOMER I_CUSTOMER, t3.EMAIL EMAIL, t1.NAME VENDOR_NAME, t2.NAME VCAM_NAME,
		HEX(t4.SCHEDULE) SCHEDULE, t4.NAME CUSTOMER_VCAM_NAME, t4.OPTIONS OPTIONS, t4.LOGIN CUSTOMER_VCAM_LOGIN,
		t4.PASSWORD CUSTOMER_VCAM_PASSWORD, t4.I_CUSTOMER_VCAM I_CUSTOMER_VCAM, t4.TOKEN TOKEN, t4.ROD ROD,
		t4.ROS ROS, t4.ON_AIR AS 'ON_AIR', 63 AS 'RESTRICTION', 'OWNER' AS 'TYPE', EXTRACTVALUE(t4.OPTIONS,'/LAT') AS LAT,
		EXTRACTVALUE(t4.OPTIONS,'/LNG') AS LNG, EXTRACTVALUE(t4.OPTIONS,'/VCAM_LOCATION') AS VCAM_LOCATION
		FROM vendor t1
		INNER JOIN vcam t2 ON t2.I_VENDOR = t1.I_VENDOR
		INNER JOIN customer_vcam t4 ON t4.I_VCAM = t2.I_VCAM
		INNER JOIN customer t3 ON t3.I_CUSTOMER = t4.I_CUSTOMER
		INNER JOIN customer_vcam_server t22 ON t4.I_CUSTOMER_VCAM = t22.I_CUSTOMER_VCAM
		LEFT JOIN `server` t18_1 ON (t22.I_SERVER = t18_1.I_SERVER AND  t22.`TYPE` = 'HLS')
		LEFT JOIN `server` t18_2 ON (t22.I_SERVER = t18_2.I_SERVER AND t22.`TYPE` = 'RTMP')
		WHERE t3.I_RESELLER = ? AND t4.I_CUSTOMER_VCAM = ? AND t4.DELETED IS NOT NULL GROUP BY t4.I_CUSTOMER_VCAM";
		
		$sth = self::pdo ()->prepare ( $sqlStr );
		// $sth->execute(array(self::$resellerId, $i_customer_vcam));
		$sth->execute ( array (
				self::$resellerId,
				$i_customer_vcam 
		) );
		/** @var VcamInfo $vcamInfo */
		$vcamInfoArray = $sth->fetch ( PDO::FETCH_ASSOC );
		if (isset ( $vcamInfoArray ['OPTIONS'] )) {
			$tmpArray = ( array ) simplexml_load_string ( '<OPTIONS>' . $vcamInfoArray ['OPTIONS'] . '</OPTIONS>', "SimpleXMLElement", LIBXML_NOCDATA );
			$vcamInfoArray ['OPTIONS'] = self::Array2Object ( $tmpArray, 'VcamOptions' );
		}
		$vcamInfo = self::Array2Object ( $vcamInfoArray, 'VcamInfo' );
		return $vcamInfo;
	}
	
	/**
	 * Get Customer Vcam
	 *
	 * @param
	 *        	GetCustomerVcamListRequest
	 * @return VcamInfoArray
	 */
	static function get_customer_vcam_list($args) {
		$i_customer = null;
		self::isLogedIn ( __METHOD__ );
		$argNames = array (
				'i_customer' 
		);
		foreach ( $argNames as $name ) {
			if (empty ( $args->$name )) {
				throw new Exception ( __METHOD__ . " Argument '$name' is empty" );
			}
			$$name = $args->$name;
		}
		
		self::checkICustomer ( $i_customer );
		
		$sqlStr = "SELECT I_CUSTOMER_VCAM FROM customer_vcam WHERE I_CUSTOMER = '$i_customer' AND DELETED IS NOT NULL ORDER BY I_CUSTOMER_VCAM";
		$sth = self::$pdo->query ( $sqlStr );
		
		$getCustomerVcamRequest = new GetCustomerVcamRequest ();
		
		$customerInfoArray = array ();
		
		while ( $res = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
			$getCustomerVcamRequest->i_customer_vcam = $res ['I_CUSTOMER_VCAM'];
			$customerInfoArray [] = self::get_customer_vcam ( $getCustomerVcamRequest );
		}
		return $customerInfoArray;
	}
	/**
	 *
	 * @param
	 *        	$args
	 * @return int
	 * @throws Exception
	 */
	static function add_customer_vcam($args) {
		self::isLogedIn ( __METHOD__ );
		/** @var string $vcam_info */
		$VCAM_INFO = null;
		/** @var int $i_reseller */
		$I_RESELLER = 0;
		/** @var int $i_customer */
		$I_CUSTOMER = null;
		/** @var string $schedule */
		$SCHEDULE = null;
		/**
		 * * REQUIRED PARAMS **
		 */
		/** @var string $vendor_name */
		$VENDOR_NAME = null;
		/** @var string $vcam_name */
		$VCAM_NAME = null;
		/** @var string $customer_vcam_name */
		$CUSTOMER_VCAM_NAME = null;
		/** @var string $customer_vcam_login */
		$CUSTOMER_VCAM_LOGIN = null;
		/** @var string $customer_vcam_password */
		$CUSTOMER_VCAM_PASSWORD = null;
		/** @var string $token */
		$TOKEN = null;
		/**
		 * OPTIONS
		 */
		/** @var string $OPTIONS */
		$OPTIONS = null;
		$requiredOption = array (
				'VCAM_IP',
				'VCAM_DNAME',
				'VCAM_PORT' 
		);
		
		/**
		 * * Processing arguments **
		 */
		$argNames = array (
				'vcam_info',
				'i_reseller' 
		);
		foreach ( $argNames as $name ) {
			if (empty ( $args->$name )) {
				throw new Exception ( "add_customer_vcam::Argument '$name' is empty" );
			}
			$$name = $args->$name;
		}
		/**
		 * * Processing parameters required **
		 */
		$argNames = array (
				'I_CUSTOMER',
				'VENDOR_NAME',
				'VCAM_NAME',
				'CUSTOMER_VCAM_NAME',
				'CUSTOMER_VCAM_LOGIN',
				'CUSTOMER_VCAM_PASSWORD',
				'TOKEN',
				'OPTIONS' 
		);
		foreach ( $argNames as $name ) {
			if (empty ( $vcam_info->$name )) {
				throw new Exception ( "add_customer_vcam. Parameter 'vcam_info->$name' is required" );
			}
			$$name = $vcam_info->$name;
		}
		/**
		 * * Check if customer belong to reseller **
		 */
		self::checkICustomer ( $I_CUSTOMER );
		/**
		 * * Processing Options **
		 */
		if (! is_array ( $OPTIONS )) {
			throw new Exception ( 'Options is not array' );
		}
		foreach ( $OPTIONS as $optionName => $optionValue ) {
			// $$optionName = $optionValue;
		}
		
		/**
		 * * Create schedule hex string **
		 */
		if (! empty ( $vcam_info->schedule )) {
			$schedule = '';
			foreach ( str_split ( $_REQUEST ["schedule"], 4 ) as $h ) {
				$schedule .= base_convert ( $h, 2, 16 );
			}
		}
		/**
		 * *
		 * Check camera model & manufactura
		 */
		$sqlStr = 'SELECT t2.I_VCAM FROM vcam t2 INNER JOIN vendor t1 ON t2.I_VENDOR = t1.I_VENDOR
					WHERE t2.NAME = ? AND t1.NAME = ?';
		/** @var PDOStatement $sth */
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				$VCAM_NAME,
				$VENDOR_NAME 
		) );
		/** @var int $iVcam */
		$iVcam = intval ( $sth->fetchColumn () );
		if (empty ( $iVcam )) {
			throw new Exception ( "Unknown camera model '$VCAM_NAME' or cameras manufactura '$VENDOR_NAME'" );
		}
		/**
		 * Chick for camera limit
		 */
		$args->i_customer = $I_CUSTOMER;
		$getCustomerVcamLimitResponse = self::get_customer_vcam_limit ( $args );
		if ($getCustomerVcamLimitResponse->current_vcam >= $getCustomerVcamLimitResponse->vcam_limit) {
			throw new Exception ( 'VCAM_LIMIT' );
		}
		/**
		 * Check if video camera exist
		 */
		$sqlStr = "SELECT COUNT(I_CUSTOMER_VCAM) FROM customer_vcam
			WHERE I_CUSTOMER = ? AND NAME = ? AND DELETED IS NOT NULL";
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				$I_CUSTOMER,
				$CUSTOMER_VCAM_NAME 
		) );
		if (! empty ( $sth->fetchColumn () )) {
			throw new Exception ( 'DUPLICATION' );
		}
		/**
		 * Options processing
		 */
		/*
		 * $argNames = array('VCAM_IP', 'VCAM_DNAME', 'VCAM_PORT');
		 * foreach ($argNames as $name) {
		 * if (empty($OPTIONS->$name)) {
		 * throw new Exception("add_customer_vcam. 'Option $name' is required");
		 * }
		 * $$name = $OPTIONS->$name;
		 * }
		 */
		/*
		 * //Prevent creation of duplicate camera
		 * $sqlStr = "SELECT getCountVCamByPortIpOrDNS(?,?,?,'')";
		 * $sth = self::pdo()->prepare($sqlStr);
		 * $sth->execute(array( $vcam_ip, $vcam_dname, $vcam_port ));
		 * if(!empty($sth->fetchColumn())) {
		 * throw new Exception('VCAM_EXIST');
		 * }
		 */
		$sqlStr = "INSERT IGNORE INTO customer_vcam (NAME,LOGIN,PASSWORD,I_CUSTOMER,I_VCAM,TOKEN,SCHEDULE)
			VALUES (:camName, :camLogin, :camPass, :iCustomer, :iVCam, MD5(UUID()), UNHEX(:schedule))";
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->bindValue ( ':camName', $CUSTOMER_VCAM_NAME, PDO::PARAM_STR );
		$sth->bindValue ( ':camLogin', $CUSTOMER_VCAM_LOGIN, PDO::PARAM_STR );
		$sth->bindValue ( ':camPass', $CUSTOMER_VCAM_PASSWORD, PDO::PARAM_STR );
		$sth->bindValue ( ':iCustomer', $I_CUSTOMER, PDO::PARAM_INT );
		$sth->bindValue ( ':iVCam', $iVcam, PDO::PARAM_INT );
		$sth->bindValue ( ':schedule', $SCHEDULE, PDO::PARAM_STR );
		$sth->execute ();
		if ($sth->rowCount () < 1) {
			throw new Exception ( "Cameras's number limit reached" );
		}
		/** @var int $i_customer_vcam */
		$i_customer_vcam = intval ( self::pdo ()->query ( "SELECT LAST_INSERT_ID()" )->fetchColumn () );
		if (empty ( $i_customer_vcam )) {
			throw new Exception ( "Can't create camera " . $i_customer_vcam );
		}
		/**
		 * INSERT records into customer_vcam_server table
		 */
		/** @var int $i_server */
		$i_server = 0;
		/** @var string $type */
		$type = 'RTMP';
		$sqlStr = "INSERT INTO customer_vcam_server (`I_CUSTOMER_VCAM`,`I_SERVER`,`TYPE`)
				VALUES (':i_customer_vcam', ':i_server', ':type')";
		$insertCustomerVcamServer = self::pdo ()->prepare ( $sqlStr );
		$insertCustomerVcamServer->bindValue ( ':i_customer_vcam', $i_customer_vcam );
		$insertCustomerVcamServer->bindParam ( ':i_server', $i_server, PDO::PARAM_INT );
		$insertCustomerVcamServer->bindParam ( ':type', $type, PDO::PARAM_STR );
		self::pdo ()->exec ( "CALL getStreamServerId(@sID, @hID, @percent)" );
		$sth = self::pdo ()->query ( "SELECT @sID, @hID, @percent" );
		/** @var int $sID */
		$sID = intval ( $sth->fetchColumn ( 0 ) );
		/** @var int $hID */
		$hID = intval ( $sth->fetchColumn ( 1 ) );
		
		if (! empty ( $sID )) {
			$i_server = $sID;
			// $insertCustomerVcamServer->execute();
			// Выбор HLS сервера делает функция getStreamServerId(),
			// если "hID" empty, право на выбор сервера переходит функции getHLSStreamServerId()
			$type = 'HLS';
			if (! empty ( $hID )) {
				$i_server = $hID;
			} else {
				self::pdo ()->exec ( "CALL getHLSStreamServerId( @hID, @percent)" );
				$sth = self::pdo ()->query ( "SELECT @hID, @percent" );
				$i_server = intval ( $sth->fetchColumn ( 0 ) );
			}
			// $insertCustomerVcamServer->execute();
		}
		/**
		 * * Create Record server **
		 */
		self::pdo ()->exec ( "CALL getRecordServerId(@sID, @percent)" );
		$sth = self::pdo ()->query ( "SELECT @sID, @percent" );
		$i_server = intval ( $sth->fetchColumn ( 0 ) );
		if (! empty ( $i_server )) {
			$type = 'RECORD';
			// $insertCustomerVcamServer->execute();
		}
		/**
		 * * Create Control server **
		 */
		self::pdo ()->exec ( "CALL getControlServerId(@sID, @percent)" );
		$sth = self::pdo ()->query ( "SELECT @sID, @percent" );
		$i_server = intval ( $sth->fetchColumn ( 0 ) );
		if (! empty ( $i_server )) {
			$type = 'CONTROL';
			// $insertCustomerVcamServer->execute();
		}
		/**
		 * Check R_CHUNK_TIME 60-1800
		 */
		$OPTIONS ["R_CHUNK_TIME"] = ($OPTIONS ["R_CHUNK_TIME"] > 1800) ? 1800 : (($OPTIONS ["R_CHUNK_TIME"] < 120) ? 120 : $OPTIONS ["R_CHUNK_TIME"]);
		
		/**
		 * * AUDIO ON/OFF/AAC **
		 */
		// Example: getVCamOption(vcamName CHAR(128), vendorName CHAR(128), optionName CHAR(255))
		$sqlStr = "SELECT getVCamOption( ?, ?, 'AUDIO')";
		$sth = self::pdo ()->prepare ( $sqlStr );
		/** @var string $audioParam */
		$sth->execute ( array (
				$VCAM_NAME,
				$VENDOR_NAME 
		) );
		$audioParam = $sth->fetchColumn ();
		if ($OPTIONS ['VCAM_AUDIO'] == 'AUDIO_ON') {
			$OPTIONS ['VCAM_AUDIO'] = $audioParam;
		}
		// --------------
		$sqlStr = "SELECT setCustomerVCamOptionById(:iCastomerVcam, :optionName, :optionValue)";
		$sth = self::$pdo->prepare ( $sqlStr );
		$sth->bindValue ( ':iCastomerVcam', $i_customer_vcam, PDO::PARAM_STR );
		$sth->bindParam ( ':optionName', $optionName, PDO::PARAM_STR, 50 );
		$sth->bindParam ( ':optionValue', $optionValue, PDO::PARAM_STR, 50 );
		
		if (isset ( $OPTIONS ['CONFIG_PORT'] )) {
			$OPTIONS ['CONFIG_PORT'] = empty ( $OPTIONS ['CONFIG_PORT'] ) ? 80 : $OPTIONS ['CONFIG_PORT'];
		}
		
		foreach ( $OPTIONS as $optionName => $optionValue ) {
			if ($optionName == 'VCAM_URLSUFFIX_HIGH' || $optionName == 'VCAM_URLSUFFIX_LOW') {
				if ($optionValue == '') {
					continue;
				}
			}
			$sth->execute ();
		}
		// ------------------ LOGING
		$action = "Added: '$VENDOR_NAME':'$VCAM_NAME' with the name of the camera '$CUSTOMER_VCAM_NAME'.";
		self::customerAction ( $I_CUSTOMER, $action );
		return $i_customer_vcam;
	}
	/**
	 * Delete Customer Video Camera
	 *
	 * @param $args DeleteCustomerVcamRequest        	
	 * @return DeleteCustomerVcamResponse
	 * @throws Exception
	 */
	static function delete_customer_vcam($args) {
		self::isLogedIn ( __METHOD__ );
		/** @var int $i_customer_vcam */
		$i_customer_vcam = 0;
		/** @var int $i_reseller */
		$i_reseller = 0;
		
		/**
		 * * Processing arguments **
		 */
		$argNames = array (
				'i_customer_vcam',
				'i_reseller' 
		);
		foreach ( $argNames as $name ) {
			if (empty ( $args->$name )) {
				throw new Exception ( __METHOD__ . " Argument '$name' is empty" );
			}
			$$name = $args->$name;
		}
	}
	/**
	 *
	 * @param GetCustomerInfoRequest $request        	
	 * @return CustomerInfo
	 */
	static function get_customer_info($request) {
		self::isLogedIn ( __METHOD__ );
		$args = array (
				'i_customer' 
		);
		foreach ( $args as $arg ) {
			if (empty ( $request->$arg )) {
				throw new Exception ( "Argument '$arg' is empty" );
			}
			$$arg = $request->$arg;
		}
		$sqlStr = "SELECT t3.I_CUSTOMER 'I_CUSTOMER', t3.EMAIL 'EMAIL', t3.PASSWORD 'PASSWORD', t3.BALANCE 'BALANCE',
	        t3.BILLING_MODEL 'BILLING_MODEL', t3.BLOCKED 'BLOCKED', t3.ACTIVE 'ACTIVE', t3.ISSUE_DATE 'ISSUE_DATE',
	        t3.FIRST_USAGE 'FIRST_USAGE', t3.LAST_USAGE 'LAST_USAGE'
			FROM customer t3
	        INNER JOIN reseller t26 ON t3.I_RESELLER = t26.I_RESELLER
			WHERE t26.TOKEN = ? AND t3.I_CUSTOMER = ?";
		
		// throw new Exception(self::$sessionId);
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				self::$sessionId,
				$i_customer 
		) );
		$customerArray = $sth->fetch ( PDO::FETCH_ASSOC );
		
		return self::Array2Object ( $customerArray, 'CustomerInfo' );
	}
		/**
		 * Get Customer Info List
		 *
		 * @return CustomerInfoArray
		 */
		static function get_customer_list() {
			self::isLogedIn ( __METHOD__ );
			$result = new GetCustomerListResponse ();
			
			$sqlStr = "SELECT t3.I_CUSTOMER 'I_CUSTOMER'
				FROM customer t3
		        INNER JOIN reseller t26 ON t3.I_RESELLER = t26.I_RESELLER
	            WHERE t26.TOKEN = :session_id ORDER BY t3.I_CUSTOMER";
			
			$sth = self::pdo ()->prepare ( $sqlStr );
			$sth->bindParam ( ':session_id', self::$sessionId );
			$sth->execute ();
			
			$result = array ();
			
			$request = new GetCustomerInfoRequest ();
			
			while ( $row = $sth->fetch ( PDO::FETCH_ASSOC ) ) {
				$request->i_customer = $row ['I_CUSTOMER'];
				$result [] = self::get_customer_info ( $request );
			}
			return $result;
		}
	/**
	 * Add Customer
	 *
	 * @param AddCustomerRequest $addCustomerRequest        	
	 * @return int
	 */
	static function add_customer($addCustomerRequest) {
		self::isLogedIn ( __METHOD__ );
		/** @var CustomerInfo */
		$customer_info = null;
		$args = array (
				'customer_info' 
		);
		foreach ( $args as $arg ) {
			if (empty ( $addCustomerRequest->$arg )) {
				throw new Exception ( __METHOD__ . " Argument '$arg' is empty" );
			}
			$$arg = $addCustomerRequest->$arg;
		}
		
		$args = array (
				'EMAIL',
				'PASSWORD' 
		);
		foreach ( $args as $arg ) {
			if (empty ( $customer_info->$arg )) {
				throw new Exception ( __METHOD__ . " required parameter '$arg' is missing in customer_info parametr" );
			}
			$$arg = $customer_info->$arg;
		}
		
		$sqlStr = "INSERT INTO customer (I_RESELLER,EMAIL,PASSWORD) VALUES (?,?,?)";
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				self::$resellerId,
				$EMAIL,
				$PASSWORD 
		) );
		/** @var int */
		$iCustomer = self::pdo ()->lastInsertId ();
		
		return ($iCustomer);
	}
	/**
	 * Delete Customer
	 *
	 * @param DeleteCustomerRequest $request        	
	 * @return DeleteCustomerResponse
	 */
	static function delete_customer($request) {
		self::isLogedIn ( __METHOD__ );
		/** @var int */
		$i_customer = null;
		
		$args = array (
				'i_customer' 
		);
		foreach ( $args as $arg ) {
			if (empty ( $request->$arg )) {
				throw new Exception ( "Argument '$arg' is empty" );
			}
			$$arg = $request->$arg;
		}
		$deleteCustomerResponse = new DeleteCustomerResponse ();
		// Recieve customer_info
		$deleteCustomerResponse->customer_info = self::get_customer_info ( $request );
		if (empty ( $deleteCustomerResponse->customer_info ))
			throw new Exception ( __METHOD__ . " Customer id '$i_customer' empty." );
		
		$sqlStr = "DELETE FROM customer WHERE I_CUSTOMER = ? AND I_RESELLER = ?";
		$sth = self::pdo ()->prepare ( $sqlStr );
		$sth->execute ( array (
				$i_customer,
				self::$resellerId 
		) );
		$rowCount = $sth->rowCount ();
		
		return ($deleteCustomerResponse);
	}
}