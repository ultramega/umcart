<?php
/**
 * Template helper functions
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Template
 */

/**
 * Template Helper
 */
class Template {
    /**
     * Output root relative path from view relative path
     *
     * @param string $path relative path
     * @param bool $return true to return path
     * @return string web accessible path
     */
    public static function path($path, $return = false) {
        $out = sprintf('%s/%s/%s', Config::$theme_web_root, Config::$theme, $path);
        if($return) {
            return $out;
        }
        echo $out;
    }
    /**
     * Rewrite URL based on rewriting rules
     *
     * @param string $url URL with query string
     * @param bool $return true to return rewritten URL
     * @return string rewritten URL
     */
    public static function rewrite($url, $return = false) {
        if($return) {
            return $url;
        }
        echo $url;
    }
    /**
     * Output hidden CSRF token field
     */
    public static function csrfToken() {
        $session = new Session();
        if(!isset($session->csrf_token)) {
            $session->csrf_token = String::randomString();
        }
        echo self::createInput('csrftoken', $session->csrf_token, 'hidden') . PHP_EOL;
    }
    /**
     * Generate HTML input tag
     *
     * @param string $name name of the field
     * @param string $value default value
     * @param string $type type of field
     * @return string input tag HTML
     */
    public static function createInput($name, $value = '', $type = 'text') {
        return sprintf('<input type="%s" name="%s" id="i%2$s" value="%s">', $type, $name, $value);
    }
    /**
     * Generate HTML checkbox input
     *
     * @param string $name name of the field
     * @param bool $checked initially selected
     * @return string input tag HTML
     */
    public static function createCheckboxInput($name, $checked = false) {
        $selected = '';
        if((bool)$checked) {
            $selected = ' checked';
        }
        return sprintf('<input type="checkbox" name="%s" id="i%1$s" value="1"%s>', $name, $selected);
    }
    /**
     * Generate HTML selection input
     *
     * @param string name of the field
     * @param array $options associative array of options
     * @param int $value initially selected value
     * @param bool $required remove blank option
     * @return string select tag HTML
     */
    public static function createSelectorInput($name, array $options, $value = null, $required = false) {
        $out = sprintf('<select name="%s" id="i%1$s">', $name);
        if(!$required) {
            $out .= '<option value=""></option>';
        }
        foreach($options as $id => $option) {
            $selected = '';
            if($id === $value) {
                $selected = ' selected';
            }
            $out .= sprintf('<option value="%s"%s>%s</option>', htmlspecialchars($id), $selected, htmlspecialchars($option));
        }
        $out .= '</select>';
        return $out;
    }
    /**
     * Generate HTML boolean select tag
     *
     * @param string $name name of the field
     * @param bool $value initially selected value
     * @return string select tag HTML
     */
    public static function createBoolInput($name, $value = null) {
        if(isset($value)) {
            $value = (int)$value;
        }
        $options = array(
            1 => Lang::YES,
            0 => Lang::NO
        );
        return self::createSelectorInput($name, $options, $value);
    }
    /**
     * Generate HTML list from array
     *
     * @param array $list
     * @param string $type either ul or ol
     * @param string $class class to give the root list tag
     * @param string $id id to give the root list tag
     * @param int $parent used internally
     * @param int $level used internally
     * @return string HTML list markup
     */
    public static function createList(array $list, $type = 'ul', $class = null, $id = null, $parent = 0, $level = 0) {
        $has_children = false;
        $out = '';
        foreach($list as $key => $item) {
            if($item['parent'] === $parent) {
                if(!$has_children) {
                    $has_children = true;
                    $att = '';
                    if(isset($class)) {
                        $att .= sprintf(' class="%s"', $class);
                    }
                    if(isset($id)) {
                        $att .= sprintf(' id="%s"', $id);
                    }
                    $out .= sprintf('<%s%s>',$type, $att);
                    $level++;
                }
                $out .= sprintf('<li>%s%s</li>', $item['text'], self::createList($list, $type, null, null, $key, $level));
            }
        }
        if($has_children) {
            $out .= sprintf('</%s>', $type);
        }
        return $out;
    }
    /**
     * Output HTML list of category links
     *
     * @param array $cats category array
     */
    public static function categoryTree(array $cats) {
        foreach($cats as $key => $cat) {
            $url = self::rewrite('?command=viewcategory&category_id=' . $key, true);
            $cats[$key]['text'] = sprintf('<a href="%s">%s</a>', $url, htmlspecialchars($cat['name']));
        }
        echo self::createList($cats, 'ul', 'categories');
    }
    /**
     * Output HTML list of category selections
     *
     * @param array $cats category array
     * @param array $selected list of categories initially selected
     * @param string $name name of the fields
     * @param string $type input type
     */
    public static function categorySelector(array $cats, $selected = array(), $name = 'categories[]', $type = 'checkbox') {
        $selected = (array)$selected;
        foreach($cats as $key => $cat) {
            $checked = '';
            if(in_array($key, $selected)) {
                $checked = ' checked';
            }
            $cats[$key]['text'] = sprintf('<label><input type="%s" name="%s" value="%d"%s> %s</label>', $type, $name, $key, $checked, htmlspecialchars($cat['name']));
        }
        echo self::createList($cats, 'ul', 'categories');
    }
    /**
     * Get web accessible path to a scaled image
     *
     * @param string $image image file name
     * @param int $width width in pixels
     * @param int $height height in pixels (null = dynamic height)
     * @param bool $fill stretch the image to fill area (retains proportions)
     * @return string web accessible path
     */
    public static function scaledImage($image, $width, $height = null, $fill = false) {
        $path = Config::$image_root . '/' . $image;
        if(!is_file($path)) {
            throw new Exception('File "' . $path . '" does not exist or is not a valid image');
        }
        $pathinfo = pathinfo($path);
        $basename = basename($path, '.' . $pathinfo['extension']);
        
        $outfile = sprintf('%s-%d-%d.%s', $basename, $width, $height, $pathinfo['extension']);
        $outpath = $pathinfo['dirname'] . '/' . $outfile;
        $webpath = Config::$image_web_root . '/' . $outfile;
        
        if(!file_exists($outpath)) {
            $img = new Image();
            $img->load($path);
            $img->resize($width, $height, $fill);
            if($img->save($outpath)) {
                return $webpath;
            }
        }
        else {
            return $webpath;
        }
        
        return Config::$image_web_root . '/' . $image;
    }
}