<?php
/**
 * Image manipulation
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Image
 */

/**
 * Image manipulator wrapper class
 */
class Image implements iImage {
    /**
     * Image handler
     *
     * @var Image_*
     */
    protected $obj;
    /**
     * Initialize an image handler
     */
    public function __construct() {
        if(function_exists('gd_info')) {
            $this->obj = new Image_GD();
        }
        else {
            throw new Exception;
        }
    }
    /**
     * Calculate the dimensions of a resized box
     *
     * @param int $width target width
     * @param int $height target height
     * @param int $orig_w original width
     * @param int $orig_h original height
     * @param bool $fill stretch the box to fill (maintains proportions)
     * @return int $width output overall width
     * @return int $height output overall height
     * @return int $dst_x output x position
     * @return int $dst_y output y position
     * @return int $dst_w output inner width
     * @return int $dst_h output inner height
     */
    public static function calculateResize($width, $height, $orig_w, $orig_h, $fill) {
        $dst_w = $orig_w;
        $dst_h = $orig_h;
        
        if($fill || $width < $orig_w || (!is_null($height) && $height < $orig_h)) {
            $xscale = $orig_w/$width;
            $yscale = (!is_null($height)) ? $orig_h/$height : 0;
            if($yscale <= $xscale) {
                $scale = $xscale;
            }
            else {
                $scale = $yscale;
            }
            $dst_w = $orig_w*(1/$scale);
            $dst_h = $orig_h*(1/$scale);
        }
        if(is_null($height)) {
            $height = $dst_h;
        }
        
        $dst_x = ($width/2)-($dst_w/2);
        $dst_y = ($height/2)-($dst_h/2);
        
        return array(
            'width'     => $width,
            'height'    => $height,
            'dst_x'     => $dst_x,
            'dst_y'     => $dst_y,
            'dst_w'     => $dst_w,
            'dst_h'     => $dst_h
        );
    }
    /**
     * Load an image file
     * 
     * @param string $path path to the image file
     * @return bool success
     */
    public function load($path) {
        return $this->obj->load($path);
    }
    /**
     * Save the image file to disk
     * 
     * @param string $path path to save image file
     * @return bool success
     */
    public function save($path) {
        return $this->obj->save($path);
    }
    /**
     * Output image to browser
     */
    public function output() {
        $this->obj->output();
    }
    /**
     * Create a new image
     * 
     * @param int $width width in pixels
     * @param int $height width in pixels
     * @return bool success
     */
    public function create($width, $height) {
        return $this->obj->create($width, $height);
    }
    /**
     * Resize the image
     * 
     * @param int $width width in pixels
     * @param int $height height in pixels (null = dynamic height)
     * @param bool $fill stretch the image to fill area
     * @return bool success
     */
    public function resize($width, $height = null, $fill = false) {
        return $this->obj->resize($width, $height, $fill);
    }
}