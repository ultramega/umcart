<?php
/**
 * Front loader
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

require 'config.php';

function __autoload($class) {
    $class = str_replace('_', '/', strtolower($class));
    require_once Config::APP_ROOT . '/' . $class . '.class.php';
}

new Controller();