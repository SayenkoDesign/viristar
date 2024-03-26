<?php
/**
 * Enqueue scripts and styles.
 */ 


function get_url( $filename ) {
    $filename   = str_replace( '//', '/', "/$filename" );
    $public_uri = get_stylesheet_directory_uri() . '/dist';
    static $manifest;

    if ( empty( $manifest ) ) {
        $manifest_path = get_stylesheet_directory() . '/dist/mix-manifest.json';
        $manifest      = new Manifest( $manifest_path );
    }

    if ( array_key_exists( $filename, $manifest->get() ) ) {
        return $public_uri . $manifest->get()[ $filename ];
    } else {
        return $public_uri . $filename;
    }
}

function get_path( $filename ) {
    $filename    = str_replace( '//', '/', "/$filename" );
    $public_path = get_stylesheet_directory() . '/dist';

    if ( empty( $manifest ) ) {
        $manifest_path = get_stylesheet_directory() . '/dist/mix-manifest.json';
        $manifest      = new Manifest( $manifest_path );
    }

    if ( array_key_exists( $filename, $manifest->get() ) ) {
        return $public_path . $manifest->get()[ $filename ];
    } else {
        return $public_path . $filename;
    }

}

class Manifest {
    static $manifest;

    public function __construct( $manifest_path ) {
        if ( ! isset( self::$manifest ) ) {
            if ( file_exists( $manifest_path ) ) {
                self::$manifest = json_decode( file_get_contents( $manifest_path ), true );
            } else {
                self::$manifest = [];
            }
        }
    }

    public function get() {
        return self::$manifest;
    }
}