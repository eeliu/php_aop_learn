<?php
/**
 * User: eeliu
 * Date: 1/3/19
 * Time: 6:48 PM
 */
namespace Pinpoint;
use PDO;
/**
 *  register classloader if needs
 *  require built-in function
 *  require built-in class
 */

//require_once __DIR__ ."PDO.php";

//use Pinpoint\PDO as PDO;
//use Pinpoint\mysql as mysql;


/// register classloader for redefined class
///


$servername = "dev-test-mysql:3306";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;", $username, $password);
    $sql = 'show databases;';
    foreach ($conn->query($sql) as $row) {
        print_r($row);
    }

//    PDO::abc(array(1,2,3,4,5));

}
catch(PDOException $e)
{
    echo $e->getMessage();
}