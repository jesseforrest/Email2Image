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
