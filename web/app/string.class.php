<?php
/**
 * Generic string functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage String
 */

/**
 * Generic string functions
 */
class String {
    /**
     * Generate random string
     *
     * @return string random 40 character string
     */
    public static function randomString() {
        return sha1(uniqid(microtime(), true));
    }
    /**
     * Format a value as money
     *
     * @param float $amount
     * @return string formatted string
     */
    public static function formatMoney($amount) {
        return sprintf('%s%01.2f', Config::$currency_symbol, $amount);
    }
    /**
     * Format string for safe inclusion in HTML
     *
     * @param string $string
     * @return string formatted string
     */
    public static function safeHTMLText($string) {
        return htmlspecialchars($string);
    }
    /**
     * Get translated string
     *
     * This is just an alias for sprintf for now
     * @param string $format translation format
     * @param mixed $param,... unlimited optional parameters
     * @return string
     */
    public static function getText($format) {
        return call_user_func_array('sprintf', func_get_args());
    }
    /**
     * Print translated string
     *
     * This is just an alias for printf for now
     * @param string $format translation format
     * @param mixed $param,... unlimited optional parameters
     */
    public static function say($format) {
        call_user_func_array('printf', func_get_args());
    }
}