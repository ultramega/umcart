<?php
/**
 * Default Admin Command
 */
class Command_Admin_Default extends Command_Admin_Common {
    /**
     * Execute command
     */
    public function exec() {
        $this->loadView('admin/default');
    }
}