<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(  'class'    => 'LoadConfig',
                                    'function' => 'load_config',
                                    'filename' => 'load_config.php',
                                    'filepath' => 'hooks');

