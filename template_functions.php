<?php
function setTitle($path) {
    $title = '';
    switch($path) {
        case 'activation':
            $title = 'activation';
            break;
        case 'change_password':
            $title = 'change_password';
            break;
        case 'categories':
            $title = 'categories';
            break;
        case 'create_post':
            $title = 'create_post';
            break;
        case 'create_priv_message':
            $title = 'create_priv_message';
            break;
        case 'display_messages':
            $title = 'display_messages';
            break;
        case 'display_posts':
            $title = 'display_posts';
            break;
        case 'forums':
            $title = 'forums';
            break;
        case 'forgot_pass':
            $title = 'forgot_pass';
            break;
        case 'login':
            $title = 'login';
            break;
        case 'logout':
            $title = 'logout';
            break;
        case 'inbox':
            $title = 'inbox';
            break;
        case 'logout':
            $title = 'logout';
            break;
        case 'profile':
            $title = 'profile';
            break;
        case 'registration':
            $title = 'registration';
            break;
        case 'view_conversation':
            $title = 'view_conversation';
            break;
        case 'view_member':
            $title = 'view_member';
            break;
    }
    return $title;
}
?>