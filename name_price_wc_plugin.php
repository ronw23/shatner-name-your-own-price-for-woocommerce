<?
class SV_WC_Donation extends WC_Cart
{

    public function __construct()
    {

        if ( is_admin() ){ // admin actions
          $this->init_form_fields();
          add_action( 'admin_menu', array($this, 'add_shatner_menu' ));
          add_action( 'admin_init', array($this, 'admin_init' ));
          add_action('woocommerce_before_calculate_totals',  array($this,  'add_custom_price' ));
          add_action('woocommerce_product_options_pricing',  array($this,  'add_donation_radio'));
        } else {



            //add_filter('woocommerce_get_price_html',           array($this,  'add_price_html'));
            if(get_option('use_shatner_templates') == 1)
            {
                add_filter('woocommerce_locate_template',       array($this, 'template_override'),10,3);
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'remove_link'),10);
            }


            add_action('woocommerce_product_options_pricing',  array($this,  'add_donation_radio'));
            add_action('save_post',                            array($this,  'set_named_price'));
            add_action('woocommerce_add_to_cart',              array($this,  'add_to_cart_hook'));
            add_action('woocommerce_before_calculate_totals',  array($this,  'add_custom_price' ));
            add_action('init', array($this, 'init_css'));
        }
    }

    public function remove_link(){
        return '';
    }

public function add_shatner_menu()
    {
        add_options_page(
            'Shatner Plugin Settings', 
            'Shatner Settings', 
            'manage_options', 
            'shatner_plugin', 
            array($this, 'shatner_plugin_settings_page')
        );
    }

    public function shatner_plugin_settings_page()
    {
        include(sprintf("%s/templates/settings.php", dirname(__FILE__))); 
    }


    /**
     * hook into WP's admin_init action hook
     */
    public function admin_init()
    {

        // add your settings section
        add_settings_section(
            'wp_plugin_template-section_shatner', 
            'Shatner Plugin Template Settings', 
            array(&$this, 'settings_section_shatner_plugin_template'), 
            'wp_plugin_template_shatner'
        );
        
        foreach($this->form_fields as $setting)
        {

            // register your plugin's settings
            register_setting('wp_plugin_template-group-shatner', $setting['title']);
            // add your setting's fields
            add_settings_field(
                $setting['title'], 
                $setting['description'],
                array(&$this, 'settings_field_input_'. $setting['type']), 
                'wp_plugin_template_shatner', 
                'wp_plugin_template-section_shatner',
                array(
                    'field' => $setting['title']
                )
            );
        }
    } // END public static function activate

    public function init_form_fields()
    {
        $this->form_fields = array(
            array(
                'type'        => 'radio_button',
                'title'       => __('use_shatner_templates', 'woothemes'),
                'description' => __('Shatner Templates override pricing, disable if you want to customize using your theme', 'woothemes'),
                'default'     => __('1', 'woothemes')
            )
        );
     }
    
    public function settings_section_shatner_plugin_template()
    {
        // Think of this as help text for the section.
        echo 'These settings do things for the WP Plugin Template.';
    }
    
    /**
     * This function provides text inputs for settings fields
     */
    public function settings_field_input_text($args)
    {
        // Get the field name from the $args array
        $field = $args['field'];
        // Get the value of this setting
        $value = get_option($field);
        // echo a proper input type="text"
        echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
    } // END public function settings_field_input_text($args)


    /**
     * This function provides text inputs for settings fields
     */
    public function settings_field_input_radio_button($args)
    {

        // Get the field name from the $args array
        $field = $args['field'];
        // Get the value of this setting
        $value = get_option($field);
        
        $html = '<input type="radio" id="'.$field.'_1" name="'.$field.'" value="1"' . checked( 1, $value, false ) . '/> ';
        $html .= ' <label for="'.$field.'_1">Enable</label> <br>';
        
        
        $html .= ' <input type="radio" id="'.$field.'_2" name="'.$field.'" value="2"' . checked( 2, $value, false ) . '/> ';
        $html .= ' <label for="'.$field.'_2">Disable</label>';
        
        echo $html;

    } // END public function settings_field_input_radio($args)





    public function template_override($template, $template_name, $template_path ) {
    // Modification: Get the template from this plugin, if it exists

    $plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) ). '/templates/';
    
    if ( file_exists( $plugin_path . $template_name ) )
    {
      $template = $plugin_path . $template_name;
      return $template;
    }

    return $template;

    }

    public function init_css()
    {
        wp_register_style('donation_css', plugins_url('custom_styles.css',__FILE__ ), false, '1.0.1', 'all');
        wp_enqueue_style( 'donation_css' );
    }

    public function add_to_cart_hook($key)
    {
        global $woocommerce;
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) 
        {
            if($cart_item_key == $key)
            {
                $values['data']->set_price($_POST['price']);
                $woocommerce->session->__set($key .'_named_price', $_POST['price']);
            }
        }

    return $key;
    }


    public function set_named_price($post)
    {
        if($_POST['_own_price']){
            if(!get_post_meta($_POST['post_ID'], '_own_price', true )){
                add_post_meta($_POST['post_ID'], '_own_price', $_POST['_own_price']);
            } else {
                update_post_meta($_POST['post_ID'], '_own_price', $_POST['_own_price']);
            }
        }
    }

    public function add_donation_radio($content)
    {
       global $post;
       woocommerce_wp_radio(array(
           'id' => '_own_price', 
           'class' => 'wc_own_price short', 
           'label' => __( 'Name you own price', 'woocommerce' ), 
           'options' => array(
                'yes' => 'yes',
                'no' => 'no',
            )
          )
       );
    }

    public function add_price_html($content)
    {
        global $post;
        if(!$post)
            return;
        $post = get_post_meta($post->ID, '_own_price', true);
        
        if($post == 'yes')
        {
            print "
            <script>
                jQuery(function($){
                    $('.cart').submit(function(){
                        $('#price').clone().attr('type','hidden').appendTo($('form.cart'));
                        return;
                    });
                });
            </script>";
            return sprintf("<div class='name_price'><label>%s</label><input name='price' id='price' type='text' /></div>", "Name your own price");
        }
        return ;
        
    }

    public function add_custom_price( $cart_object ) {
        global $woocommerce;
        print "A";
        foreach ( $cart_object->cart_contents as $key => $value ) {

                $named_price = $woocommerce->session->__get($key .'_named_price');
                if($named_price)
                {
                    $value['data']->price = $named_price;
                }
        }
    }

}
new SV_WC_Donation();
