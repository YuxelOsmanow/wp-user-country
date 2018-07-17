<?php
add_action( 'template_redirect', 'mm_check_user_location' );
function mm_check_user_location() {
    $user_ip = mm_get_client_ip_server();
    $geo = unserialize( file_get_contents( "http://www.geoplugin.net/php.gp?ip=$user_ip" ) );
    $country = $geo[ "geoplugin_countryName" ];
    $redirect_page = get_page_by_path( 'we-are-sorry' );
    $current_page_id = get_the_ID();

    if ( $country !== 'United States' ) {
        if ( get_permalink( $current_page_id ) !== get_permalink( $redirect_page->ID ) ) {
            wp_redirect( get_permalink( $redirect_page->ID ) );
            exit;
        }
    }
}

function mm_get_client_ip_server() {
    $ip_address = '';

    if ( isset( $_SERVER[ 'HTTP_CLIENT_IP' ] ) )
        $ip_address = $_SERVER[ 'HTTP_CLIENT_IP' ];
    else if ( isset( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) )
        $ip_address = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        $forward_for = '';

        if ( strpos( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ], ', ') ) {
            $forward_for = explode( ', ', $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] );
        }

        if ( is_array( $forward_for ) ) {
            $ip_address = $forward_for[ 0 ];
        }
    else if ( isset( $_SERVER[ 'HTTP_X_FORWARDED' ] ) )
        $ip_address = $_SERVER[ 'HTTP_X_FORWARDED' ];
    else if ( isset( $_SERVER[ 'HTTP_FORWARDED_FOR' ] ) )
        $ip_address = $_SERVER[ 'HTTP_FORWARDED_FOR' ];
    else if ( isset( $_SERVER[ 'HTTP_FORWARDED' ] ) )
        $ip_address = $_SERVER[ 'HTTP_FORWARDED' ];
    else if ( isset( $_SERVER[ 'REMOTE_ADDR' ] ) )
        $ip_address = $_SERVER[ 'REMOTE_ADDR' ];
    else
        $ip_address = 0;

    return $ip_address;
}
