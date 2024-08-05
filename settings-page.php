<h2>5x5 Reviews Schema Settings</h2>

<?php
if( isset( $_POST ) && count($_POST) ) {
    unset( $_POST['submit'] );
    update_option( 'ff_grs', $_POST );
}

$settings = get_option( 'ff_grs' );
?>

<form class="ff-grs-settings-form" action="" method="post">
    <table class="form-table">
        <tr>
            <th>name</th>
            <td><input type="text" name="name" value="<?php if( isset( $settings['name'] ) ) echo $settings['name']; ?>" class="regular-text"></td>
        </tr>

        <tr>
            <th>image</th>
            <td><input type="text" name="image" value="<?php if( isset( $settings['image'] ) ) echo $settings['image']; ?>" class="regular-text" placeholder="Enter image url"></td>
        </tr>

        <tr>
            <th>description</th>
            <td><textarea name="description" class="large-text code" rows=5 cols=100><?php if( isset( $settings['description'] ) ) echo $settings['description']; ?></textarea></td>
        </tr>

        <tr>
            <th>sku</th>
            <td><input type="text" name="sku" value="<?php if( isset( $settings['sku'] ) ) echo $settings['sku']; ?>" class="regular-text"></td>
        </tr>

        <tr>
            <th>mpn</th>
            <td><input type="text" name="mpn" value="<?php if( isset( $settings['mpn'] ) ) echo $settings['mpn']; ?>" class="regular-text"></td>
        </tr>
        
        <tr>
            <td colspan=2><hr/></td>
        </tr>

        <tr>
            <th>Google Places API Key</th>
            <td><input type="text" name="api_key" value="<?php if( isset( $settings['api_key'] ) ) echo $settings['api_key']; ?>" class="regular-text"></td>
        </tr>

        <tr>
            <th>Place ID</th>
            <td><input type="text" name="place_id" value="<?php if( isset( $settings['place_id'] ) ) echo $settings['place_id']; ?>" class="regular-text"></td>
        </tr>

        <tr>
            <td colspan=2>
                <hr/>
                <h4>If you can't get the Google places API key, you can manually set the values here (if api key and places id is set, this will automatically get updated once a day)</h4>
            </td>
        </tr>

        <tr>
            <th>Rating</th>
            <td><input type="text" name="rating" value="<?php if( isset( $settings['rating'] ) ) echo $settings['rating']; ?>" class="regular-text"></td>
        </tr>

        <tr>
            <th>Count</th>
            <td><input type="text" name="count" value="<?php if( isset( $settings['count'] ) ) echo $settings['count']; ?>" class="regular-text"></td>
        </tr>
        
    </table>

    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>

</form>