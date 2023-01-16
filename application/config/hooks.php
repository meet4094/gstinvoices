<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	http://codeigniter.com/user_guide/general/hooks.html
  |
 */



/* End of file hooks.php */
/* Location: ./application/config/hooks.php */

$hook['pre_system'][] = array(
    'class' => 'Router',
    'function' => 'pre_system',
    'filename' => 'router.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'     => 'Router',
    'function'  => 'set_device_identity',
    'filename'  => 'router.php',
    'filepath'  => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'     => 'Router',
    'function'  => 'check_site_status',
    'filename'  => 'router.php',
    'filepath'  => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'     => 'Router',
    'function'  => 'check_url_permission',
    'filename'  => 'router.php',
    'filepath'  => 'hooks'
);
