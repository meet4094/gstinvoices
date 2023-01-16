<?php

$config['response_type'] = array('json', 'xml');
$config['allow_upload_extensions'] = array("jpg", "jpeg", "gif", "png", "ico", "doc", "txt");
$config['allow_image_upload_extensions'] = array("jpg", "jpeg", "gif", "png", "ico");
$config['allow_vido_upload_extensions'] = array("vob", "mp4", "3gp", "mov", "flv", "wmv");
$config['available_languages'] = array("en", "de", "gu");
$config['admin_login_bypass_url'] = array(
    'admin\/login',
    'admin\/services\/forgot_password',
    'services\/admin/fpassword/[a-zA-Z0-9]',
    'services\/admin\/reset_password',
);
