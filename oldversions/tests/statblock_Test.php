<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

// We require the file we need to test.
// Relative path to the current working dir (root of xampp)
$GLOBALS['rootpath'] = "htdocs\Game-Library\server";
require_once $GLOBALS['rootpath']."\inc\CurlRequest.class.php";

/**
 * @group include
 * @group classtest
 */
final class statBlock_Test extends TestCase
{
	
}