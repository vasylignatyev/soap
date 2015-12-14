<?php
require_once('database.php');
/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 19.11.2015
 * Time: 8:50
 */
/**
 * Class LoginRequest
 * @pw_element string $user_name User Name
 * @pw_element string $user_pass A string with a user password
 * @pw_complex LoginRequest The complex type name definition
 */
class LoginRequest
{
    public $user_name;
    public $user_pass;
}
/**
 * Class LoginResponse
 * @pw_element string $session_id A string with a session identification
 * @pw_complex LoginResponse The complex type name definition
 */
class LoginResponse
{
    public $session_id;
}
class Session
{
    /**
     * Login to VHOME SERVER
     *
     * @param LoginRequest $loginRequest
     * @return LoginResponse
     */
    public function login(LoginRequest $loginRequest) {

        global $pdo;
        try {
            $sessionId = md5( uniqid());
            $sqlStr = "UPDATE reseller SET TOKEN = :session_id WHERE  PASSWORD = MD5(:user_pass) AND NAME = :user_name";
            $sth = $pdo->prepare($sqlStr);
            $sth->bindParam(':user_name', $loginRequest->user_name);
            $sth->bindParam(':user_pass', $loginRequest->user_pass);
            $sth->bindParam(':session_id', $sessionId);
            $sth->execute();
            if( $sth->rowCount() == 0 ){
                throw new Exception( "Authentication error");
            }
            return array('session_id' => $sessionId);
        } catch (Exception $e){
            return( new SoapFault( 'Session SOAP Server.', $e->getMessage()));
        }
    }
}
