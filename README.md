Email2Image
===========

This is a PHP library that can be used to securely convert email addresses to 
PNG images.

Overview
--------

The only file that needs to be included in order to utilize Email2Image is the
Email2Image.php file.  We additionally include an *example* directory to show 
common use cases.

Requirements
------------

Your version of PHP will need to be compiled with the following libraries:
 - GD: Supports necessary image functions
 - Mcrypt: Supports a wide variety of block algorithms
 
Example - Basic Usage
---------------------

This example will show how to select the font to be used and output an image.

```php
<?php
require_once 'Email2Image.php';
$email2Image = new Email2Image();
$email2Image->setFontPath('./fonts/');
$email2Image->setFontFile('tahoma.ttf');
$email2Image->setEmail('example@example.com');
$email2Image->outputImage();
``` 

Example - Set Font and Image Preferences
----------------------------------------

This example will show how to set the font, font size, image dimensions, 
background color, and foreground color.
 
```php
<?php
require_once 'Email2Image.php';
$email2Image = new Email2Image();
$email2Image->setFontPath('./fonts/');
$email2Image->setFontFile('tahoma.ttf');
$email2Image->setFontSize(12);
$email2Image->setWidth(400);
$email2Image->setHeight(300);
$email2Image->setBackgroundColor('293134');
$email2Image->setForegroundColor('668aaf');
$email2Image->setEmail('example@example.com');
$email2Image->outputImage();
```  

Example - Securely Encode an Email Address for Display on a Website
-------------------------------------------------------------------

This example will show how to use Email2Image to securely encode an email 
address and display it as an image on a website. This will prevent scrapers
from easily gathering email addresses from your website.

First you will need to create a page that will display the email address as 
an image.  In this example, we call this page *index.php* and it should be
in the same directory as *Email2Image.php*.

The file *index.php* should have these contents:
 ```php
<?php
require_once 'Email2Image.php';

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
```  

You will want to replace 'example-salt-string' with a unique 32 character salt 
key.  You can update the *$parameters* array with any data that you want to 
pass to *image.php*.  All the data passed to *image.php* will be encrypted.

Next, you want to create an *image.php* file that will decrypt the information
and output a PNG image. This file should be in the same directory as 
*index.php* and *Email2Image.php*.

The file *image.php* should have these contents:
 ```php
<?php
require_once 'Email2Image.php';

// The email address to be displayed
$email = '';

// Instantiate Email2Image
$email2Image = new Email2Image();
$email2Image->setSalt('example-salt-string');
$email2Image->setFontPath('./fonts/');
$email2Image->setFontFile('tahoma.ttf');
$email2Image->setFontSize(12);
$email2Image->setBackgroundColor('293134');
$email2Image->setForegroundColor('668aaf');
$email2Image->setHorizontalAlignment(Email2Image::LEFT);
$email2Image->setVerticalAlignment(Email2Image::MIDDLE);

// Decrypt parameters passed via encryption
if (isset($_GET['encrypted_data'], $_GET['public_key']))
{
   $encryptedData = $_GET['encrypted_data'];
   $publicKey = $_GET['public_key'];
   
   $response = $email2Image->decrypt($encryptedData, $publicKey);
   
   if ($response == null)
   {
      $email2Image->setEmail('Unknown Email');
   }
   else
   {
      if (isset($response['email']))
      {
         $email2Image->setEmail($response['email']);
      }
      if (isset($response['width']))
      {
         $email2Image->setWidth($response['width']);
      }
      if (isset($response['height']))
      {
         $email2Image->setHeight($response['height']);
      }
   }
}

// Output image
$email2Image->outputImage();
```

You will want to replace 'example-salt-string' with the same salt key you used
in *index.php*.  

You should note that this example utilizes 3 encoded parameters:
 - email
 - width
 - height
 
If you encoded more fields in *index.php*, then you could use those fields in
*image.php*.
