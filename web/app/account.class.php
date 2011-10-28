<?php
/**
 * Account management functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

/**
 * Account Helper
 */
class Account {
    /**
     * Session handler
     *
     * @var Session
     */
    private static $session;
    /**
     * Create a new user account
     *
     * @param string $email
     * @param string $password
     * @param string $level user or admin
     * @return bool success
     */
    public static function create($email, $password, $level = 'user') {
        $user = new Model_User();
        $user->setMultiple(array(
            'email'             => $email,
            'password'          => $password,
            'level'             => $level,
            'date_registered'   => time()
        ));
        $user->save();
        
        return self::login($user->id());
    }
    /**
     * Load a user into the session
     *
     * @param int|Model_User $user user ID or user model object
     * @return bool success
     */
    public static function login($user) {
        if(!isset(self::$session)) {
            self::$session = new Session();
        }
        if(is_int($user)) {
            $user = new Model_User($user);
        }
        if($user instanceof Model_User) {
            $id = $user->id();
            if(isset(self::$session->cart_id)) {
                $user->set('cart_id', self::$session->cart_id);
                $user->save();

                $cart = new Model_Cart($user->get('cart_id'));
                $cart->set('user', $id);
                $cart->save();
            }

            self::$session->destroy();
            self::$session->regenerate();

            self::$session->logged_in = true;
            self::$session->user_id = $id;
            self::$session->user_email = $user->get('email');
            self::$session->user_level = $user->get('level');
            self::$session->address_id = $user->get('default_address');
            self::$session->cart_id = $user->get('cart_id');
            
            return true;
        }
        return false;
    }
}