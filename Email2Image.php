<?php
/**
 * This file holds the Email2Image class
 *
 * PHP Version 5
 *
 * @category  Email2Image
 * @package   JesseForrest
 * @author    Jesse Forrest <jesseforrest@gmail.com>
 * @copyright 2012 Jesse Forrest
 * @license   MIT License (MIT)
 * @link      https://github.com/jesseforrest/Email2Image
 * @filesource
 */

/**
 * This class provides functionality to convert an email address into an image
 * so that it can be displayed on websites to prevent unwanted scrapers from 
 * gathering email addresses from your website.
 * 
 * This class requires that mcrypt module is installed with PHP.
 *
 * PHP Version 5
 *
 * @category  Email2Image
 * @package   JesseForrest
 * @author    Jesse Forrest <jesseforrest@gmail.com>
 * @copyright 2012 Jesse Forrest
 * @license   MIT License (MIT)
 * @link      https://github.com/jesseforrest/Email2Image
 * @filesource
 */
class Email2Image
{
   /**
    * The path to the font
    * 
    * @var string
    */
   protected $fontPath = './fonts/';
   
   /**
    * The filename of the font to be used to create the image
    * 
    * @var string
    */
   protected $fontFile = 'tahoma.ttf';
   
   /**
    * The font size to be used
    * 
    * @var integer
    */
   protected $fontSize = 10;
   
   /**
    * The width of the image
    * 
    * @var integer|null
    */
   protected $width = null;

   /**
    * The height of the image
    *
    * @var integer|null
    */
   protected $height = null;
   
   /**
    * Holds the 6-digit hex color to be used for the background color. If 
    * <var>null</var> is passed in, no background color will be used.
    * 
    * @var string|null
    */
   protected $backgroundColor = null;

   /**
    * Holds the 6-digit hex color to be used for the foreground color.
    *
    * @var string
    */
   protected $foregroundColor = '000000';
   
   /**
    * Holds the email address that will be displayed
    * 
    * @var string|null
    */
   protected $email = null;
   
   /**
    * Sets the path to where the font is located
    * 
    * @param string $fontPath The path to the font.  You must include the 
    *                         end slash "/".
    *                         
    * @return void
    */
   public function setFontPath($fontPath)
   {
      $this->fontPath = $fontPath;
   }
   
   /**
    * Sets the file name of the .ttf font.
    *
    * @param string $fontFile The filename of the font, with the extension 
    *                         included.
    *
    * @return void
    */
   public function setFontFile($fontFile)
   {
      $this->fontFile = $fontFile;
   }
   
   /**
    * Sets the font size to be used
    *
    * @param integer $fontSize The font size to be used
    *
    * @return void
    */
   public function setFontSize($fontSize)
   {
      $this->fontSize = $fontSize;
   }
   
   /**
    * Holds the width, in pixels, of the image.  If width is <var>null</var>, 
    * then the width will automatically be determined by the font size and the
    * email address string length.
    * 
    * @param integer|null $width The width, in pixels, of the image
    * 
    * @return void
    */
   public function setWidth($width)
   {
      $this->width = $width;
   }

   /**
    * Holds the height, in pixels, of the image.  If height is <var>null</var>,
    * then the height will automatically be determined by the font size.
    *
    * @param integer|null $height The height, in pixels, of the image
    *
    * @return void
    */
   public function setHeight($height)
   {
      $this->height = $height;
   }
   
   /**
    * Sets the 6-digit hex color to be used for the foreground color.
    * 
    * @param string $foregroundColor The 6-digit hex color to be used for the
    *                                foreground color.
    * 
    * @return void
    */
   public function setForegroundColor($foregroundColor)
   {
      $this->foregroundColor = $foregroundColor;
   }
   
   /**
    * Holds the 6-digit hex color to be used for the background color. If 
    * <var>null</var> is passed in, no background color will be used.
    * 
    * @param string $backgroundColor The 6-digit hex color to be used for the
    *                                background color. If <var>null</var> is 
    *                                passed in, no background color will be 
    *                                used.
    *                                
    * @return void
    */
   public function setBackgroundColor($backgroundColor)
   {
      $this->backgroundColor = $backgroundColor;
   }
   
   /**
    * Sets the email address that will be displayed. 
    * 
    * @param string $email The decrypted email that should be displayed
    * 
    * @return void
    */
   public function setEmail($email)
   {
      $this->email = $email;
   }
   
   /**
    * Returns a random alphanumeric string to be used as the key
    *
    * @param integer $length The length of the key to create.
    *
    * @return string
    */
   public function getKey($length = 8)
   {
      $chars = 'abcdefghjkmnpqrstuvwxyz0123456789';
      $i = 0;
      $key = '';
      while ($i < $length)
      {
         $num = mt_rand() % 33;
         $tmp = substr($chars, $num, 1);
         $key = $key . $tmp;
         $i++;
      }
      return $key;
   }
   
   /**
    * Encrypt an email address with the passed in key
    *
    * @param string $email The email address to encrypt
    * @param string $key   The key used to encrypt the string
    *
    * @return string Returns an encrypted string
    */
   public function getEncryptedEmail($email, $key)
   {
      // For sake of MCRYPT_RAND
      srand((double) microtime() * 1000000);
   
      // To improve variance
      $key = md5($key);
   
      // Open module, and create IV
      $descriptor = mcrypt_module_open('des', '', 'cfb', '');
      $key = substr($key, 0, mcrypt_enc_get_key_size($descriptor));
      $ivSize = mcrypt_enc_get_iv_size($descriptor);
      $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
   
      // Initialize encryption handle
      if (mcrypt_generic_init($descriptor, $key, $iv) != -1)
      {
         // Encrypt data
         $cT = mcrypt_generic($descriptor, $email);
         mcrypt_generic_deinit($descriptor);
         mcrypt_module_close($descriptor);
         $cT = $iv . $cT;
   
         return urlencode(base64_encode($cT));
      }
   }
   
   /**
    * This function will take an encrypted email and key and decrypt it to the
    * original email.
    *
    * @param string $encryptedEmail The encoded email address
    * @param string $key            The key to decode the string
    *
    * @return string Returns the string decrypted
    */
   public function getDecryptedEmail($encryptedEmail, $key)
   {
      $encryptedEmail = base64_decode($encryptedEmail);
   
      // To improve variance
      $key = md5($key);
   
      // Open module, and create IV
      $descriptor = mcrypt_module_open('des', '', 'cfb', '');
      $key = substr($key, 0, mcrypt_enc_get_key_size($descriptor));
      $ivSize = mcrypt_enc_get_iv_size($descriptor);
      $iv = substr($encryptedEmail, 0, $ivSize);
      $encryptedEmail = substr($encryptedEmail, $ivSize);
   
      // Initialize encryption handle
      if (mcrypt_generic_init($descriptor, $key, $iv) != -1)
      {
         // Decrypt data
         $decryptedEmail = mdecrypt_generic($descriptor, $encryptedEmail);
         mcrypt_generic_deinit($descriptor);
         mcrypt_module_close($descriptor);
   
         return $decryptedEmail;
      }
   }
   
   /**
    * This will output the image in PNG format
    * 
    * @return void
    */
   public function outputImage()
   {
      header('Content-type: image/png');
      
      // Get bounding box array
      $bbox = imageftbbox(
         $this->fontSize, 
         0, 
         $this->fontPath . $this->fontFile, 
         $this->email);
      
      // Determine width of image
      $width = $this->width;
      if ($width == null)
      {
         $width = abs($bbox[4] - $bbox[0]);
      }
      
      // Determine height of image
      $height = $this->height;
      if ($height == null)
      {
         $height = abs($bbox[1] - $bbox[5]);
      } 
      
      // Create image resource
      $image = imagecreate($width, $height);
      
      // Set background color if necessary
      if ($this->backgroundColor != null)
      {
         $r = hexdec('0x' . substr($this->backgroundColor, 0, 2));
         $g = hexdec('0x' . substr($this->backgroundColor, 2, 2));
         $b = hexdec('0x' . substr($this->backgroundColor, 4, 2));
         $backgroundColor = imagecolorallocate($image, $r, $g, $b);
         //@todo create background
      }
      
      // Set foreground color 
      if ($this->foregroundColor != null)
      {
         $r = hexdec('0x' . substr($this->foregroundColor, 0, 2));
         $g = hexdec('0x' . substr($this->foregroundColor, 2, 2));
         $b = hexdec('0x' . substr($this->foregroundColor, 4, 2));
         $foregroundColor = imagecolorallocate($image, $r, $g, $b);
         
         // Write the email string to the image
         imagefttext(
            $image,
            $this->fontSize,
            0,
            0,
            $height,
            $foregroundColor,
            $this->fontPath . $this->fontFile,
            $this->email);
      }
      
      // Output the image
      imagepng($image);
      
      // Destroy the image
      imagedestroy($image);
   }
}
