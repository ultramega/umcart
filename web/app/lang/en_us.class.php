<?php
/**
 * Translations
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 */

/**
 * Language: en_US
 */
class Lang {
    /**
     * Translations
     */
    const 
        /**
         * General
         */
        YES                             = 'Yes',
        NO                              = 'No',
        ADD_TO_CART                     = 'Add to Cart',
        GUEST                           = 'Guest',
        LOGIN                           = 'Log In',
        LOGOUT                          = 'Log Out',
        GREETING                        = 'Hello %s!',
        COPYRIGHT                       = 'Copyright &copy; %s %s',
        BUTTON_APPLY                    = 'Apply',
        SKIP_NAVIGATION                 = 'Skip navigation',
        OPTIONAL                        = 'optional',
        PAGE                            = 'Page',
        PAGE_FIRST                      = '&#0171;',
        PAGE_PREVIOUS                   = '&#0139;',
        PAGE_LAST                       = '&#0187;',
        PAGE_NEXT                       = '&#0155;',
        BUTTON_SAVE                     = 'Save',
        BUTTON_DELETE                   = 'Delete',
        SORT_ASC                        = '&nbsp;&#8595;',
        SORT_DESC                       = '&nbsp;&#8593;',
        NO_RECORDS                      = 'No records found',
        HEADER_NOT_FOUND                = 'Not Found',
        HEADER_ACCESS_DENIED            = 'Access Denied',
        
        // Account
        ACCOUNT                         = 'Account',
        YOUR_ACCOUNT                    = 'Your Account',
        CREATE_ACCOUNT                  = 'Create Account',
        LABEL_EMAIL                     = 'Email Address',
        LABEL_PASSWORD                  = 'Password',
        BUTTON_LOGIN                    = 'Login',
        LOGGED_IN_AS                    = 'Logged in as %s',
        HEADER_LOGIN                    = 'Login',
        
        // Shopping Cart
        CART                            = 'Cart',
        CART_HEADER                     = 'Shopping Cart',
        UPDATE_CART                     = 'Update Cart',
        CART_IS_EMPTY                   = 'Cart is empty.',
        COL_DELETE                      = 'Remove',
        COL_QUANTITY                    = 'Quantity',
        COL_PRODUCT                     = 'Product',
        COL_DISCOUNT                    = 'Discount',
        COL_PRICE                       = 'Price',
        TOTAL                           = 'Total',
        COUPON_CODE                     = 'Coupon Code',
        
        // Checkout
        BUTTON_CHECKOUT                 = 'Checkout',
        CHECKOUT_HEADER                 = 'Checkout',
        BUTTON_ORDER                    = 'Submit Order',
        SHIPPING_ADDRESS                = 'Shipping Address',
        LABEL_FULLNAME                  = 'Full Name',
        LABEL_ADDRESS1                  = 'Address Line 1',
        LABEL_ADDRESS2                  = 'Address Line 2',
        LABEL_CITY                      = 'City',
        LABEL_STATE                     = 'State/Province/Region',
        LABEL_ZIP                       = 'ZIP/Postal Code',
        ORDER_SUMMARY                   = 'Order Summary',
        ORDER_SUBMITTED                 = 'Order Submitted',
        
        // Errors
        E_FIELD_REQUIRED                = '%s must not be left blank',
        E_FIELD_NUMERIC                 = '%s must be numeric',
        E_FIELD_INVALID                 = 'Invalid option selected for %s',
        E_FIELD_DIRECTORY               = '%s must be a valid directory',
        E_FIELD_EMAIL                   = '%s must be a valid email address',
        
        /**
         * Administration
         */
        ADMIN                           = 'Admin',
        
        // Menu
        ADMIN_MENU_PRODUCT              = 'Products',
        ADMIN_MENU_ALL_PRODUCT          = 'All Products',
        ADMIN_MENU_ADD_PRODUCT          = 'Add Product',
        ADMIN_MENU_CATEGORY             = 'Categories',
        ADMIN_MENU_ALL_CATEGORY         = 'All Categories',
        ADMIN_MENU_ADD_CATEGORY         = 'Add Category',
        ADMIN_MENU_ATTRIBUTE            = 'Attributes',
        ADMIN_MENU_ALL_ATTRIBUTE        = 'All Attributes',
        ADMIN_MENU_ADD_ATTRIBUTE        = 'Add Attribute',
        ADMIN_MENU_COUPON               = 'Coupons',
        ADMIN_MENU_ALL_COUPON           = 'All Coupons',
        ADMIN_MENU_ADD_COUPON           = 'Add Coupon',
        ADMIN_MENU_ORDER                = 'Orders',
        ADMIN_MENU_ALL_ORDER            = 'All Orders',
        ADMIN_MENU_USER                 = 'Users',
        ADMIN_MENU_ALL_USER             = 'All Users',
        ADMIN_MENU_ADD_USER             = 'Add User',
        ADMIN_MENU_SYSTEM               = 'System',
        ADMIN_MENU_SYSTEM_SETTINGS      = 'Settings',
        
        // List Products
        HEADER_ALL_PRODUCTS             = 'All Products',
        COL_PRODUCT_NAME                = 'Product Name',
        COL_PURCHASES                   = 'Purchases',
        COL_STOCK                       = 'Stock',
        
        // Edit Product
        LABEL_PRODUCT_NAME              = 'Product Name',
        LABEL_DESCRIPTION               = 'Description',
        LABEL_PRODUCT_IMAGE             = 'Product Image',
        LABEL_PRICE                     = 'Price',
        LABEL_STOCK                     = 'Stock',
        LABEL_ACTIVE                    = 'Active',
        LABEL_FEATURED                  = 'Featured',
        HEADER_ADD_PRODUCT              = 'Add New Product',
        HEADER_EDIT_PRODUCT             = 'Edit Product',
        PRODUCT_DETAILS                 = 'Product Details',
        PRODUCT_CATEGORIES              = 'Product Categories',
        PRODUCT_ATTRIBUTES              = 'Product Attributes',
        PRODUCT_COUPONS                 = 'Product Coupons',
        
        // List Attributes
        HEADER_ALL_ATTRIBUTES           = 'All Attributes',
        COL_NAME                        = 'Name',
        COL_TYPE                        = 'Type',
        
        // Edit Attribute
        LABEL_ATTRIBUTE_NAME            = 'Attribute Name',
        LABEL_ATTRIBUTE_TYPE            = 'Attribute Type',
        LABEL_ATTRIBUTE_OPTIONS         = 'Attribute Options',
        HEADER_ADD_ATTRIBUTE            = 'Create Attribute',
        HEADER_EDIT_ATTRIBUTE           = 'Edit Attribute',
        TYPE_BOOL                       = 'Yes/No',
        TYPE_INT                        = 'Number',
        TYPE_TEXT                       = 'Text',
        TYPE_SET                        = 'Selection',
        
        // List Orders
        HEADER_ALL_ORDERS               = 'All Orders',
        COL_USER                        = 'User',
        COL_TOTAL                       = 'Total',
        COL_STATUS                      = 'Status',
        COL_SHIPPING                    = 'Shipping',
        COL_DATE_PLACED                 = 'Placed',
        
        // Edit Order
        LABEL_STATUS                    = 'Status',
        LABEL_TOTAL                     = 'Total',
        LABEL_SHIPPING_AMOUNT           = 'Shipping Amount',
        LABEL_TRACKING                  = 'Tracking Number',
        BUTTON_RECALCULATE              = 'Recalculate',
        HEADER_EDIT_ORDER               = 'Edit Order',
        STATUS_PENDING                  = 'Pending',
        STATUS_PAID                     = 'Paid',
        STATUS_SHIPPED                  = 'Shipped',
        
        // List Categories
        HEADER_ALL_CATEGORIES           = 'All Categories',
        
        // Edit Category
        LABEL_CATEGORY_NAME             = 'Category Name',
        LABEL_CATEGORY_PARENT           = 'Parent Category',
        HEADER_ADD_CATEGORY             = 'Add New Category',
        HEADER_EDIT_CATEGORY            = 'Edit Category',
        
        // List Coupons
        HEADER_ALL_COUPONS              = 'All Coupons',
        COL_COUPON_CODE                 = 'Coupon Code',
        COL_COUPON_DISCOUNT             = 'Discount',
        COL_MIN_PURCHASE                = 'Min. Purchase',
        COL_START_TIME                  = 'Starts',
        COL_EXPIRE_TIME                 = 'Expires',
        
        // Edit Category
        LABEL_COUPON_CODE               = 'Coupon Code',
        LABEL_DISCOUNT                  = 'Discount',
        LABEL_COUPON_TYPE               = 'Coupon Type',
        LABEL_MIN_PURCHASE              = 'Minimum Purchase',
        LABEL_START_TIME                = 'Start Time',
        LABEL_EXPIRE_TIME               = 'Expire Time',
        LABEL_COUPON_PRODUCTS           = 'Applicable Products',
        HEADER_ADD_COUPON               = 'Add New Coupon',
        HEADER_EDIT_COUPON              = 'Edit Coupon',
        TYPE_GENERAL                    = 'All Products',
        TYPE_PRODUCT                    = 'Limited',
        
        // List Users
        HEADER_ALL_USERS                = 'All Users',
        COL_EMAIL                       = 'Email Address',
        COL_LEVEL                       = 'Access Level',
        COL_REGISTER_DATE               = 'Date Registered',
        
        // Edit User
        LABEL_LEVEL                     = 'Access Level',
        HEADER_ADD_USER                 = 'Add New User',
        HEADER_EDIT_USER                = 'Edit User',
        LEVEL_ADMIN                     = 'Administrator',
        LEVEL_USER                      = 'User',
        
        // Settings
        HEADER_SETTINGS                 = 'Settings',
        OPTIONS_GENERAL                 = 'General Options',
        OPTIONS_COOKIES                 = 'Cookie Options',
        OPTIONS_SECURITY                = 'Security Options',
        OPTIONS_PATHS                   = 'Paths',
        LABEL_SITE_NAME                 = 'Site Name',
        LABEL_THEME                     = 'Site Theme',
        LABEL_DEFAULT_LANGUAGE          = 'Default Language',
        LABEL_CURRENCY_SYMBOL           = 'Currency Symbol',
        LABEL_ITEMS_PER_PAGE            = 'Items Per Page',
        LABEL_SESSION_NAME              = 'Session Name',
        LABEL_COOKIE_DOMAIN             = 'Cookie Domain',
        LABEL_COOKIE_PATH               = 'Cookie Path',
        LABEL_AUTH_MAX_FAILURE          = 'Maximum User Login Attempts',
        LABEL_AUTH_LOCK_TIMEOUT         = 'Account Lock Timeout (seconds)',
        LABEL_PATH_THEMES               = 'Internal Themes Path',
        LABEL_PATH_THEMES_WEB           = 'Web Accessible Themes Path',
        LABEL_PATH_IMAGES               = 'Internal Images Path',
        LABEL_PATH_IMAGES_WEB           = 'Web Accessible Images Path';
}