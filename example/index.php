<?php
/**
 * This file holds an example of how to use the Email2Image class
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

require_once '../Email2Image.php';

// Instantiate object
$email2Image = new Email2Image();

// Set the salt to be used for additional security
$email2Image->setSalt('example-salt-string');

// The key/value pairs you want to encrypt and pass to image.php
$parameters = array(
   'email' => 'example@example.com',
   'width' => '400',
   'height' => '200'
);

// Gather the response from the encrypt method
$response = $email2Image->encrypt($parameters);
?>
<!DOCTYPE html>
<html>
<head>
   <title>Email2Image Example</title>
</head>
<body>
   <img 
      src="image.php?<?php echo http_build_query($response, '', '&amp;'); ?>"
      width="<?php echo $parameters['width']; ?>"
      height="<?php echo $parameters['height']; ?>"
      alt=""/>
</body>
</html>
