<?php
/*
Plugin Name: Five by Five Reviews Schema
Description: Reviews snippet for google search results
Version: 2.0
*/

class FF_Reviews_Schema {
    
    public function __construct(){
        add_action( "admin_menu", [ $this, 'admin_menu' ] );
        $this->settings = get_option( 'ff_grs' );
        if( !$this->settings ) return;
        add_action( 'wp_head', [ $this, 'render_schema' ] );
        add_action( 'init', [ $this, 'setup_cron' ] );
    }
    
    public function admin_menu(){
        add_submenu_page(
            'fivebyfive',
            'Reviews Schema',
            'Reviews Schema',
            'administrator',
            'ff-grs-options',
            [ $this, 'settings_page' ]
        );
    }

    public function settings_page(){
        include_once 'settings-page.php';
    }
    
    function render_schema() {
        $settings = $this->settings;
        if( !$settings ) return;
        
        $schema = [
            '@context' => "https://schema.org/",
            '@type' => "Product",
        ];
        
        $skip = [ 'api_key', 'rating', 'count', 'place_id' ];
        foreach( $settings as $k => $v ) {
            if( in_array( $k, $skip ) ) continue;
            $schema[$k] = $v;
        }
    
        $schema['brand'] = [
            '@type' => 'Brand',
            'name' => $settings['name'],
        ];
    
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => $settings['rating'],
            'reviewCount' => $settings['count'],
        ];
        
        echo '<script type="application/ld+json">'. json_encode( $schema ) .'</script>';
    }
    
    function save_reviews_data(){
        if( !$this->settings['api_key'] || !$this->settings['place_id'] ) return;
        // fetch data from google maps api
        $data = $this->fetch_reviews();
        if( $data ) {
            $this->settings['rating'] = $data['rating'];
            $this->settings['count'] = $data['count'];
            // save
            update_option( 'ff_grs', $this->settings );
        }
    }

    function fetch_reviews(){
        $url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $this->settings['place_id'] . '&key=' . $this->settings['api_key'] . '&language=en';
    
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        $response = curl_exec( $ch );
        curl_close( $ch );
        $data = json_decode( $response );
    
        if( isset( $data->error_message ) ) return false;
        
        return [
            'rating' => $data->result->rating,
            'count' => $data->result->user_ratings_total,
        ];
    }

    public function setup_cron(){
        // run once a day
        if ( ! wp_next_scheduled( 'ff_grs_update' ) ) {
            wp_schedule_event( time(), 'daily', 'ff_grs_update' );
        }
        add_action( 'ff_grs_update', [ $this, 'save_reviews_data' ] );
    }

}

new FF_Reviews_Schema();