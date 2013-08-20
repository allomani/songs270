<?php
/**
 *  Allomani Audio and Video v2.7
 * 
 * @package Allomani.Audio.and.Video
 * @version 2.7
 * @copyright (c) 2006-2009 Allomani , All rights reserved.
 * @author Ali Allomani <info@allomani.com>
 * @link http://allomani.com
 * @license GNU General Public License version 3.0 (GPLv3)
 * 
 */

class sec_img_verification {
	var $im = NULL;
	var $string = NULL;
   var $height = 100 ;
   var $width = 30 ;
	function sec_img_verification ($height = 150, $width = 35, $sid = NULL) {
		if ($sid != NULL) {
			@session_name($sid);
		}

		// Start session
		@session_start();
      
        
	}
	function generate_string () {
		// Create random string
		$this->string = substr(sha1(mt_rand()), 17, 6);

		// Set session variable
		$_SESSION['gd_string'] = $this->string;
	}
	function verify_string ($gd_string) {
		// Check if the original string and the passed string match...
		if (strtolower($_SESSION['gd_string']) === strtolower($gd_string)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	function output_input_box ($name, $parameters = NULL) {
		return '<input type="text" name="' . $name . '" ' . $parameters . ' /> ';
	}
	function create_image () {
		// Seed string
		$this->generate_string();

		$this->im = imagecreatetruecolor($this->height, $this->width); // Create image

		// Get width and height
		$img_width = imagesx($this->im);
		$img_height = imagesy($this->im);

		// Define some common colors
		$black = imagecolorallocate($this->im, 0, 0, 0);
		$white = imagecolorallocate($this->im, 255, 255, 255);
		$red = imagecolorallocatealpha($this->im, 255, 0, 0, 75);
		$green = imagecolorallocatealpha($this->im, 0, 255, 0, 75);
		$blue = imagecolorallocatealpha($this->im, 0, 0, 255, 75);

		// Background
		imagefilledrectangle($this->im, 0, 0, $img_width, $img_height, $white);

		// Ellipses (helps prevent optical character recognition)
		imagefilledellipse($this->im, ceil(rand(5,145)), ceil(rand(0,35)), 30, 30, $red);
		imagefilledellipse($this->im, ceil(rand(5,145)), ceil(rand(0,35)), 30, 30, $green);
		imagefilledellipse($this->im, ceil(rand(5,145)), ceil(rand(0,35)), 30, 30, $blue);

		// Borders
		imagefilledrectangle($this->im, 0, 0, $img_width, 0, $black);
		imagefilledrectangle($this->im, $img_width - 1, 0, $img_width - 1, $img_height - 1, $black);
		imagefilledrectangle($this->im, 0, 0, 0, $img_height - 1, $black);
		imagefilledrectangle($this->im, 0, $img_height - 1, $img_width, $img_height - 1, $black);

		imagestring ($this->im, 5, intval(($img_width - (strlen($this->string) * 9)) / 2),  intval(($img_height - 15) / 2), $this->string, $black); // Write string to photo
	}
	function output_image() {
		$this->create_image(); // Generate image

		header("Content-type: image/jpeg"); // Tell the browser the data is a JPEG image

		imagejpeg($this->im); // Output Image
		imagedestroy($this->im); // Flush Image
	}
}
?>