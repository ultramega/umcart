<?php
/**
 * GD image manipulation
 * 
 * @author Steve Guidetti <sguidetti@ultramegatech.com>
 * @license http://sguidetti.mit-license.org/ The MIT License (MIT)
 * @package UMCart
 * @subpackage Image
 */

/**
 * GD Wrapper
 */
class Image_GD implements iImage {
    /**
     * GD image resource
     *
     * @var resource
     */
    protected $image;
    /**
     * Image type to output function map
     *
     * @var array
     */
    protected $types = array(
        'gif'   => 'imagegif',
        'jpg'   => 'imagejpeg',
        'jpeg'  => 'imagejpeg',
        'png'   => 'imagepng',
        'bmp'   => 'imagewbmp'
    );
    /**
     * Image details
     *
     * @var array
     */
    public $info = array();
    /**
     * Load an image file
     * 
     * @param string $path path to the image file
     * @return bool success
     */
    public function load($path) {
        if(file_exists($path)) {
            $data = file_get_contents($path);
            $info = getimagesize($path);
            $this->info = array(
                'width'     => $info[0],
                'height'    => $info[1]
            );
            return $this->image = imagecreatefromstring($data);
        }
        return false;
    }
    /**
     * Save the image file to disk
     * 
     * @param string $path path to save image file
     * @return bool success
     */
    public function save($path) {
        if(!isset($this->image)) {
            return;
        }
        $pathinfo = pathinfo($path);
        if(!is_dir($pathinfo['dirname']) || !is_writable($pathinfo['dirname'])) {
            return false;
        }
        $ext = strtolower($pathinfo['extension']);
        if(array_key_exists($ext, $this->types) && function_exists($this->types[$ext])) {
            $function = $this->types[$ext];
            return $function($this->image, $path);
        }
        return imagepng($this->image, $path);
    }
    /**
     * Output image to browser
     */
    public function output() {
        if(!isset($this->image)) {
            return;
        }
        header('Content-Type: image/png');
        imagepng($this->image);
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
        if(!isset($this->image)) {
            return;
        }
        
        $orig_w = $this->info['width'];
        $orig_h = $this->info['height'];
        
        extract(Image::calculateResize($width, $height, $orig_w, $orig_h, $fill));
        
        $new = imagecreatetruecolor($width, $height);
        
        if(imagecopyresampled($new, $this->image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $orig_w, $orig_h)) {
            $this->info['width'] = $width;
            $this->info['height'] = $height;
            imagedestroy($this->image);
            $this->image = $new;
            return true;
        }
        imagedestroy($new);
        return false;
    }
    /**
     * Destroy the image resource
     */
    public function __destruct() {
        if(isset($this->image)) {
            imagedestroy($this->image);
        }
    }
}