<?php
/**
 * Created by PhpStorm.
 * User: vignatyev
 * Date: 23.11.2015
 * Time: 7:52
 */
global $pdo;
$pdo = new PDO("mysql:host=localhost;dbname=virtualhome","vh_web", '6EusrWvUBHKJQnQF',array( PDO::ATTR_PERSISTENT => true));
$pdo->exec("SET NAMES UTF8");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
