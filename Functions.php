<?php
require_once('VcamInfo.php');

class Functions
{
	/**
	 * @var PDO
	 */
	protected static $pdo = null;
	/**
	 * @var int
	 */
	protected static $resellerId = 5;
	/**
	 * @var int
	 */
	protected $resellerIdInstanse;
	/**
	 * @var int
	 */
	protected static $sessionId = 0;
	/**
	 * @var int
	 */
	protected $sessionIdInstanse;
	function __construct()
	{
		$this->__wakeup();
	}
	function __destruct()
	{
		$this->resellerIdInstanse = self::$resellerId;
		$this->$sessionIdInstanse = self::$sessionId;
	}
	function __wakeup()
	{
		self::$resellerId = $this->resellerIdInstanse;
		self::$sessionId = $this->sessionIdInstanse;
	}
	function  __sleep()
	{
		return array('resellerIdInstanse', 'sessionIdInstanse');
	}
	/**
	 * database descriptor
	 *
	 * @return PDO
	 */
	static private function pdo()
	{
		if (!is_object(self::$pdo)) {
			self::$pdo = new PDO("mysql:host=localhost;dbname=virtualhome", "vh_web", '6EusrWvUBHKJQnQF', array(PDO::ATTR_PERSISTENT => true));
			self::$pdo->exec("SET NAMES UTF8");
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$pdo;
	}
	/**
	 * Check if customer belong to the Reseller
	 *
	 * @param $iCustomer
	 * @param $iReseller
	 * @return bool
	 * @throws Exception
	 */
	static private function checkICustomer($iCustomer, $iReseller)
	{

		$sqlStr = "SELECT I_CUSTOMER FROM customer t3
				INNER JOIN reseller r ON t3.I_RESELLER = r.I_RESELLER
				WHERE t3.I_CUSTOMER = ? AND r.I_RESELLER = ?";

		$sth = self::pdo()->prepare($sqlStr);
		$sth->execute(array($iCustomer, $iReseller));
		$test = $sth->fetchColumn();
		if ($test == false) {
			throw new Exception("Customer ID = '$iCustomer' do not belong to the reseller ID = '$iReseller'");
		}
		return true;
	}
	/**
	 * Check for session id (session token)
	 *
	 * @param $session_id
	 * @return int
	 * @throws Exception
	 */
	static function set_session_id($session_id)
	{
		$sqlStr = "SELECT I_RESELLER FROM reseller WHERE TOKEN = '$session_id'";
		$reseller_id = intval(self::pdo()->query($sqlStr)->fetchColumn());
		if (empty($reseller_id)) {
			throw new Exception("Wrong session ID: '$session_id'");
		}
		return $reseller_id;
	}
	/**
	 * Return Customer Action
	 *
	 * @param $iCustomer
	 * @param $action
	 * @return bool
	 */
	static private function customerAction($iCustomer, $action) {

		/** @var string $sqlStr */
		$sqlStr = "SELECT `EMAIL` FROM `customer` WHERE `I_CUSTOMER` = '$iCustomer' LIMIT 1";
		/** @var string $email */
		$email = self::pdo()->query($sqlStr)->fetchColumn();

		$description = "<customer id='$iCustomer'><username>$email</username><action>$action</action></customer>";
		$sqlStr = "INSERT IGNORE INTO `syslog` (`SEVERITY`,`DESCRIPTION`) VALUES ( 6, :description)";

		$sth = self::pdo()->prepare($sqlStr);
		$sth->bindValue( ':description', $description);
		$sth->execute();

		return true;
	}
	static function get_customer_vcam_limit($args)
	{
		$i_customer = null;
		$i_reseller = null;

		$argNames = array('i_customer', 'i_reseller');

		foreach ($argNames as $name) {
			if (empty($args->$name)) {
				throw new Exception("get_customer_vcam_limit::Argument '$name' is empty");
			}
			$$name = $args->$name;
		}
		self::checkICustomer($i_customer, $i_reseller);

		$getCustomerVcamLimitResponse = new GetCustomerVcamLimitResponse();

		$sqlStr = "SELECT getProductIntValByICustomer( '$i_customer','VCAM_LIMIT','MAX_VCAM')";
		$current_vcam = self::pdo()->query($sqlStr)->fetchColumn();

		$getCustomerVcamLimitResponse->vcam_limit = is_null($current_vcam) ? 0 : $current_vcam;

		$sqlStr = "SELECT COUNT(I_CUSTOMER_VCAM) FROM customer_vcam WHERE I_CUSTOMER = '$i_customer' AND DELETED IS NOT NULL";

		$getCustomerVcamLimitResponse->current_vcam = intval(self::pdo()->query($sqlStr)->fetchColumn());

		return $getCustomerVcamLimitResponse;
	}
	static function get_customer_vcam_list($args)
	{
		$i_customer = null;
		$i_reseller = null;

		$argNames = array('i_customer', 'i_reseller');

		foreach ($argNames as $name) {
			if (empty($args->$name)) {
				throw new Exception("get_customer_vcam_limit::Argument '$name' is empty");
			}
			$$name = $args->$name;
		}
		self::checkICustomer($i_customer, $i_reseller);

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
		WHERE t3.I_CUSTOMER = ? AND t4.DELETED IS NOT NULL GROUP BY t4.I_CUSTOMER_VCAM";

		$sth = self::pdo()->prepare($sqlStr);
		$sth->execute(array($i_customer));
		$vcamList = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($vcamList as &$vcam){
			if( !empty($vcam['OPTIONS']) ) {
				$vcam['OPTIONS'] = (array)simplexml_load_string('<OPTIONS>'.$vcam['OPTIONS'].'</OPTIONS>', "SimpleXMLElement", LIBXML_NOCDATA);
				$vcam = (object)$vcam;
			}
		}
		return $vcamList;
	}
	/**
	 * @param $args
	 * @return int
	 * @throws Exception
	 */
	static function add_customer_vcam($args)
	{
		/** @var string $vcam_info */
		$VCAM_INFO = null;
		/** @var int $i_reseller */
		$I_RESELLER = 0;
		/** @var int $i_customer */
		$I_CUSTOMER = null;
		/** @var string $schedule */
		$SCHEDULE = null;
		/*** REQUIRED PARAMS ***/
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
		$requiredOption = array('VCAM_IP','VCAM_DNAME','VCAM_PORT');

		/*** Processing arguments ***/
		$argNames = array('vcam_info', 'i_reseller');
		foreach ($argNames as $name) {
			if (empty($args->$name)) {
				throw new Exception("add_customer_vcam::Argument '$name' is empty");
			}
			$$name = $args->$name;
		}
		/*** Processing parameters required ***/
		$argNames = array('I_CUSTOMER','VENDOR_NAME','VCAM_NAME','CUSTOMER_VCAM_NAME','CUSTOMER_VCAM_LOGIN','CUSTOMER_VCAM_PASSWORD','TOKEN','OPTIONS');
		foreach ($argNames as $name) {
			if (empty($vcam_info->$name)) {
				throw new Exception("add_customer_vcam. Parameter 'vcam_info->$name' is required");
			}
			$$name = $vcam_info->$name;
		}
		/*** Check if customer belong to reseller ***/
		self::checkICustomer($I_CUSTOMER, $i_reseller);
		/*** Processing Options ***/
		if( !is_array($OPTIONS)) {
			throw new Exception('Options is not array');
		}
		foreach($OPTIONS as $optionName => $optionValue) {
			//$$optionName = $optionValue;
		}

		/*** Create schedule hex string ***/
		if (!empty($vcam_info->schedule)) {
			$schedule = '';
			foreach (str_split($_REQUEST["schedule"], 4) as $h) {
				$schedule .= base_convert($h, 2, 16);
			}
		}
		/***
		 * Check camera model & manufactura
		 */
		$sqlStr = 'SELECT t2.I_VCAM FROM vcam t2 INNER JOIN vendor t1 ON t2.I_VENDOR = t1.I_VENDOR
					WHERE t2.NAME = ? AND t1.NAME = ?';
		/** @var PDOStatement $sth */
		$sth = self::pdo()->prepare($sqlStr);
		$sth->execute(array($VCAM_NAME, $VENDOR_NAME));
		/** @var int $iVcam */
		$iVcam = intval($sth->fetchColumn());
		if (empty($iVcam)) {
			throw new Exception("Unknown camera model '$VCAM_NAME' or cameras manufactura '$VENDOR_NAME'");
		}
		/**
		 * Chick for camera limit
		 */
		$args->i_customer = $I_CUSTOMER;
		$getCustomerVcamLimitResponse = self::get_customer_vcam_limit($args);
		if ($getCustomerVcamLimitResponse->current_vcam >= $getCustomerVcamLimitResponse->vcam_limit) {
			throw new Exception('VCAM_LIMIT');
		}
		/**
		 * Check if video camera exist
		 */
		$sqlStr = "SELECT COUNT(I_CUSTOMER_VCAM) FROM customer_vcam
			WHERE I_CUSTOMER = ? AND NAME = ? AND DELETED IS NOT NULL";
		$sth = self::pdo()->prepare($sqlStr);
		$sth->execute(array($I_CUSTOMER, $CUSTOMER_VCAM_NAME));
		if (!empty($sth->fetchColumn())) {
			throw new Exception('DUPLICATION');
		}
		/**
		 * Options processing
		 */
		/*
		$argNames = array('VCAM_IP', 'VCAM_DNAME', 'VCAM_PORT');
		foreach ($argNames as $name) {
			if (empty($OPTIONS->$name)) {
				throw new Exception("add_customer_vcam. 'Option $name' is required");
			}
			$$name = $OPTIONS->$name;
		}
		*/
		/*
		 //Prevent creation of duplicate camera
		$sqlStr = "SELECT getCountVCamByPortIpOrDNS(?,?,?,'')";
		$sth = self::pdo()->prepare($sqlStr);
		$sth->execute(array( $vcam_ip, $vcam_dname, $vcam_port ));
		if(!empty($sth->fetchColumn())) {
			throw new Exception('VCAM_EXIST');
		}
		*/
		$sqlStr = "INSERT IGNORE INTO customer_vcam (NAME,LOGIN,PASSWORD,I_CUSTOMER,I_VCAM,TOKEN,SCHEDULE)
			VALUES (:camName, :camLogin, :camPass, :iCustomer, :iVCam, MD5(UUID()), UNHEX(:schedule))";
		$sth = self::pdo()->prepare($sqlStr);
		$sth->bindValue(':camName', $CUSTOMER_VCAM_NAME, PDO::PARAM_STR);
		$sth->bindValue(':camLogin', $CUSTOMER_VCAM_LOGIN, PDO::PARAM_STR);
		$sth->bindValue(':camPass', $CUSTOMER_VCAM_PASSWORD, PDO::PARAM_STR);
		$sth->bindValue(':iCustomer', $I_CUSTOMER, PDO::PARAM_INT);
		$sth->bindValue(':iVCam', $iVcam, PDO::PARAM_INT);
		$sth->bindValue(':schedule', $SCHEDULE, PDO::PARAM_STR);
		$sth->execute();
		if ($sth->rowCount() < 1) {
			throw new Exception("Cameras's number limit reached");
		}
		/** @var int $i_customer_vcam */
		$i_customer_vcam = intval(self::pdo()->query("SELECT LAST_INSERT_ID()")->fetchColumn());
		if (empty($i_customer_vcam)) {
			throw new Exception("Can't create camera " . $i_customer_vcam);
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
		$insertCustomerVcamServer = self::pdo()->prepare($sqlStr);
		$insertCustomerVcamServer->bindValue(':i_customer_vcam', $i_customer_vcam);
		$insertCustomerVcamServer->bindParam(':i_server', $i_server, PDO::PARAM_INT);
		$insertCustomerVcamServer->bindParam(':type', $type, PDO::PARAM_STR);
		self::pdo()->exec("CALL getStreamServerId(@sID, @hID, @percent)");
		$sth = self::pdo()->query("SELECT @sID, @hID, @percent");
		/** @var int $sID */
		$sID = intval($sth->fetchColumn(0));
		/** @var int $hID */
		$hID = intval($sth->fetchColumn(1));

		if (!empty($sID)) {
			$i_server = $sID;
			//$insertCustomerVcamServer->execute();
			// Выбор HLS сервера делает функция getStreamServerId(),
			// если "hID" empty, право на выбор сервера переходит функции getHLSStreamServerId()
			$type = 'HLS';
			if (!empty($hID)) {
				$i_server = $hID;
			} else {
				self::pdo()->exec("CALL getHLSStreamServerId( @hID, @percent)");
				$sth = self::pdo()->query("SELECT @hID, @percent");
				$i_server = intval($sth->fetchColumn(0));
			}
			//$insertCustomerVcamServer->execute();
		}
		/*** Create Record server ***/
		self::pdo()->exec("CALL getRecordServerId(@sID, @percent)");
		$sth = self::pdo()->query("SELECT @sID, @percent");
		$i_server = intval($sth->fetchColumn(0));
		if (!empty($i_server)) {
			$type = 'RECORD';
			//$insertCustomerVcamServer->execute();
		}
		/*** Create Control server ***/
		self::pdo()->exec("CALL getControlServerId(@sID, @percent)");
		$sth = self::pdo()->query("SELECT @sID, @percent");
		$i_server = intval($sth->fetchColumn(0));
		if (!empty($i_server)) {
			$type = 'CONTROL';
			//$insertCustomerVcamServer->execute();
		}
		/**
		 * Check R_CHUNK_TIME 60-1800
		 */
		$OPTIONS["R_CHUNK_TIME"]=($OPTIONS["R_CHUNK_TIME"]>1800)?1800:(($OPTIONS["R_CHUNK_TIME"]<120)?120:$OPTIONS["R_CHUNK_TIME"]);

		/***  AUDIO ON/OFF/AAC ***/
		// Example: getVCamOption(vcamName CHAR(128), vendorName CHAR(128), optionName CHAR(255))
		$sqlStr = "SELECT getVCamOption( ?, ?, 'AUDIO')";
		$sth = self::pdo()->prepare($sqlStr);
		/** @var string $audioParam */
		$sth->execute(array($VCAM_NAME, $VENDOR_NAME));
		$audioParam = $sth->fetchColumn();
		if ($OPTIONS['VCAM_AUDIO'] == 'AUDIO_ON') {
			$OPTIONS['VCAM_AUDIO'] = $audioParam;
		}
		// --------------
		$sqlStr = "SELECT setCustomerVCamOptionById(:iCastomerVcam, :optionName, :optionValue)";
		$sth = self::$pdo->prepare($sqlStr);
		$sth->bindValue(':iCastomerVcam', $i_customer_vcam, PDO::PARAM_STR);
		$sth->bindParam(':optionName', $optionName, PDO::PARAM_STR, 50);
		$sth->bindParam(':optionValue', $optionValue, PDO::PARAM_STR, 50);

		if(isset($OPTIONS['CONFIG_PORT'])) {
			$OPTIONS['CONFIG_PORT'] = empty($OPTIONS['CONFIG_PORT']) ? 80 : $OPTIONS['CONFIG_PORT'];
		}

		foreach ($OPTIONS as $optionName => $optionValue) {
			if ($optionName == 'VCAM_URLSUFFIX_HIGH' || $optionName == 'VCAM_URLSUFFIX_LOW') {
				if ($optionValue == '') {
					continue;
				}
			}
			$sth->execute();
		}
		//------------------ LOGING
		$action = "Added: '$VENDOR_NAME':'$VCAM_NAME' with the name of the camera '$CUSTOMER_VCAM_NAME'.";
		self::customerAction($I_CUSTOMER, $action);
		return $i_customer_vcam;
	}
}