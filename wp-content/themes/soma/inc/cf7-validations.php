<?php 
/**
 * Contact Form 7 Custom Validations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Validate {
    static function Name($string) {
        if (ctype_alpha(str_replace(' ', '', $string)) === false) {
            return false;
        } else {
            return true;
        }
    }
    static function Email($string) {
        if(preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$string) && $string != "" && $string != null){
            return true;
        } else {
            return false;
        }
    }
}

function required_text_validation( $result, $tag ) {
    if ( 'fname' == $tag->name ) {
        $txtname = isset( $_POST['fname'] ) ? trim( $_POST['fname'] ) : '';
        if (!Validate::Name($txtname)) {
            $result->invalidate( $tag, "Must contain letters" );
        }
    }
    return $result;
}

function required_email_validation( $result, $tag ) {
    if ( 'email' == $tag->name || 'newsletter-email' == $tag->name ) {
        if(isset( $_POST['email'] )) {
            $email = trim( $_POST['email'] );
        } elseif(isset( $_POST['newsletter-email'] )) {
            $email = trim( $_POST['newsletter-email'] );
        }
        if(!Validate::Email($email)){
            $result->invalidate( $tag, "Invalid email." );
        }
    }
    return $result;
}

function CF7_custom_validation_message($wpcf7) {
    $submission = WPCF7_Submission::get_instance();
    $message = $wpcf7;

    if ( $submission ) {
        $posted_data = $submission->get_posted_data();
        if (isset($posted_data['fname']) && isset($posted_data['email']) && isset($posted_data['message'])){
            if ($posted_data['fname'] == '' || $posted_data['email'] == '' || $posted_data['message'] == '') {
                $message = '* Required';
            } elseif(!Validate::Name($posted_data['fname'])) {
                $message = 'Name must contain letters';
            } elseif(!Validate::Email($posted_data['email'])) {
                $message = 'Invalid email';
            }
        } 
    }

    return $message;
}

add_filter('wpcf7_validate_email*', 'required_email_validation', 20, 2);
add_filter('wpcf7_validate_text*', 'required_text_validation', 30, 2);
add_action('wpcf7_display_message', 'CF7_custom_validation_message');