<?php
/**
 * Default controller
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Default Command
 */
class Command_Default extends Command_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->loadView('default');
    }
}