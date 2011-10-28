<?php
/**
 * Base command functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Controller
 */

/**
 * Command
 */
abstract class Command {
    /**
     * Data store for templates
     *
     * @var array
     */
    protected $data = array();
    /**
     * Session handler
     *
     * @var Session
     */
    protected $session;
    /**
     * GET parameters
     *
     * @var array
     */
    protected $get = array();
    /**
     * POST parameters
     *
     * @var array
     */
    protected $post = array();
    /**
     * Initialize
     */
    public final function __construct() {
        $this->init();
    }
    /**
     * Initialize command
     */
    public function init() {
        $this->exec();
    }
    /**
     * Execute application logic
     */
    public abstract function exec();
    /**
     * Parse request parameters
     *
     * Returns array of GET or POST vars with keys converted to lower case.
     * Also handles CSRF prevention and any other security features.
     * @param bool $secure ignore POST vars if CSRF check fails
     */
    public function parseParams($secure = false) {
        foreach($_GET as $key => $val) {
            $key = strtolower($key);
            $this->get[$key] = $val;
        }
        if($secure && (!array_key_exists('csrftoken', $_POST) || !isset($this->session, $this->session->csrf_token) || $this->session->csrf_token !== $_POST['csrftoken'])) {
            return;
        }
        foreach($_POST as $key => $val) {
            $key = strtolower($key);
            $this->post[$key] = $val;
        }
    }
    /**
     * Retreive errors stored in the session
     */
    protected function loadErrors() {
        if(isset($this->session, $this->session->error)) {
            $this->data['error'] = $this->session->error;
            unset($this->session->error);
        }
    }
    /**
     * Execute view file
     *
     * @param string $view basename of view file to load
     * @param mixed $param,... unlimited optional parameters
     */
    protected function loadView($view) {
        $params = func_get_args();
        extract($this->data);

        $path = sprintf("%s/%s/%s.php", Config::$theme_root, Config::$theme, $view);
        //chdir(dirname($path));
        //include basename($path);
        include $path;
    }
    /**
     * Generate a query string with the specified values changed
     *
     * @param array $params associative array of parameters to alter
     * @return string
     */
    protected function generateQueryString(array $params = array()) {
        $params = array_merge($this->get, $params);
        return '?' . http_build_query($params);
    }
    /**
     * Get an array of model objects
     *
     * @param Model $obj
     * @return array
     */
    protected function getList(Model $obj) {
        $page = 1;
        if(isset($this->get['page'])) {
            $page = (int)$this->get['page'];
        }
        $sort_by = 'id';
        if(isset($this->get['sort'])) {
            $sort_by = strtolower($this->get['sort']);
        }
        $sort_dir = 'ASC';
        if(isset($this->get['dir'])) {
            $sort_dir = strtoupper($this->get['dir']);
        }
        
        if(isset($this->get['filter']) && is_array($this->get['filter'])) {
            foreach($this->get['filter'] as $field => $value) {
                $obj->setFilter($field, $value);
            }
        }
        
        return $obj->getCollection(Config::$items_per_page, $page, $sort_by, $sort_dir);
    }
    /**
     * Process column headers and add to output data
     *
     * @param array $columns associative array of column headings
     */
    protected function columnHeadings(array $columns) {
        $sort_by = 'id';
        if(isset($this->get['sort'])) {
            $sort_by = strtolower($this->get['sort']);
        }
        $sort_dir = 'ASC';
        if(isset($this->get['dir'])) {
            $sort_dir = strtoupper($this->get['dir']);
        }
        
        $column_data = array();
        foreach($columns as $name => $value) {
            $dir = 'asc';
            $arrow = '';
            if($sort_by === $name) {
                if($sort_dir === 'ASC') {
                    $dir = 'desc';
                    $arrow = Lang::SORT_ASC;
                }
                else {
                    $arrow = Lang::SORT_DESC;
                }
            }
            $url = $this->generateQueryString(array('sort' => $name, 'dir' => $dir));
            $column_data[$name] = sprintf('<a href="%s">%s</a>%s', Template::rewrite($url, true), $value, $arrow);
        }
        
        $this->data['columns'] = $column_data;
    }
    /**
     * Generate page selector and add to output data
     *
     * @param int $total total number of items
     */
    protected function pageSelector($total) {
        $num_pages = max(1, (int)ceil($total/Config::$items_per_page));
        
        $page = 1;
        if(isset($this->get['page'])) {
            $page = (int)$this->get['page'];
        }
        
        $pagination = range(1, $num_pages);
        foreach($pagination as $k => $p) {
            if($p !== $page) {
                $url = $this->generateQueryString(array('page' => $p));
                $pagination[$k] = sprintf('<a href="%s">%d</a>', Template::rewrite($url, true), $p);
            }
        }
        $pagination = implode(', ', $pagination);
        
        if($page > 1) {
            $first_url = Template::rewrite($this->generateQueryString(array('page' => 1)), true);
            $prev_url = Template::rewrite($this->generateQueryString(array('page' => $page-1)), true);
            $pagination = sprintf('<a href="%s">%s</a> <a href="%s">%s</a> %s', $first_url, Lang::PAGE_FIRST, $prev_url, Lang::PAGE_PREVIOUS, $pagination);
        }
        else {
            $pagination = Lang::PAGE_FIRST . ' ' . Lang::PAGE_PREVIOUS . ' ' . $pagination;
        }
        
        if($page < $num_pages) {
            $last_url = Template::rewrite($this->generateQueryString(array('page' => $num_pages)), true);
            $next_url = Template::rewrite($this->generateQueryString(array('page' => $page+1)), true);
            $pagination .= sprintf(' <a href="%s">%s</a> <a href="%s">%s</a>', $next_url, Lang::PAGE_NEXT, $last_url, Lang::PAGE_LAST);
        }
        else {
            $pagination .= ' ' . Lang::PAGE_NEXT . ' ' . Lang::PAGE_LAST;
        }
        
        $this->data['pagination'] = $pagination;
    }
}