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
require_once 'Email2Image.php';
$email2Image = new Email2Image();
$email2Image->setFontPath('./fonts/');
$email2Image->setFontFile('tahoma.ttf');
$email2Image->setEmail('example@example.com');
$email2Image->outputImage();
``` 

Example - Set Font and Image Preferences
---------------------

This example will show how to set the font, font size, image dimensions, 
background color, and foreground color.
 

```php
require_once 'Email2Image.php';
$email2Image = new Email2Image();
$email2Image->setFontPath('./fonts/');
$email2Image->setFontFile('tahoma.ttf');
$email2Image->setFontSize(12);
$email2Image->setWidth(400);
$email2Image->setHeight(300);
$email2Image->setBackgroundColor('000000');
$email2Image->setForegroundColor('FFFFFF');
$email2Image->setEmail('example@example.com');
$email2Image->outputImage();
```  
