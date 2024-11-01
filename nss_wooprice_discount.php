<?php
/* protected */
if (!defined('ABSPATH'))
exit;
/* class initial */
class nss_product_discount
{   
    /*Property initial*/
    public $nss_option_page_number;
    /* method initial */    
    function __construct()
    {
        add_shortcode('nss_showing_discount_product', array($this, 'nss_discountproduct_items'));
        if( is_admin() ):
		  add_action( 'admin_menu', array( $this, 'nss_addmin_meue' ) );
		  add_action( 'admin_init', array( $this, 'nss_page_init' ) );
        endif;
    }
    /*Admin Menu*/
	function nss_addmin_meue()
	{
		 add_options_page(
            'Custom Settings', 
            'Woo Settings', 
            'manage_options', 
            'woo-setting-admin', 
            array( $this, 'nss_admin_page_method' )
        );
	}
	/*Admin Page Method*/
	function nss_admin_page_method()
	{
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__("Woo Settings","nsstheme");?></h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'nss_option_group' );
                do_settings_sections( 'woo-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
	}
    /*Page init*/
	function nss_page_init()
    {
		 register_setting(
            'nss_option_group', // Option group
            'nss_option_page_item', // Option name
            array( $this, 'nss_sanitize_field' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Shortcode Settings', // Title
            array( $this, 'nss_print_section_info' ), // Callback
            'woo-setting-admin' // Page
        );  

        add_settings_field(
            'nss_number_of_page', // ID
            'Number of Page Item', // Title 
            array( $this, 'nss_id_number_callback' ), // Callback
            'woo-setting-admin', // Page
            'setting_section_id' // Section           
        );
         
	}
    //Callback Sanitize method
    function nss_sanitize_field($input)
    {
        
        $new_input = array();
        if( isset( $input['nss_number_of_page'] ) )
        {
           $new_input['nss_number_of_page'] = absint( $input['nss_number_of_page'] );
        }      
        return $new_input;
     }

    //  Callback method of section
	function nss_print_section_info()
    {
		print 'Shortcode Here: <b> [nss_showing_discount_product] </b> <br/> just copy it and past it any pages' ;
	}

    //  Callback method of field
	function nss_id_number_callback()
    {
        $this->nss_option_page_number = get_option( 'nss_option_page_item' );
		printf(
            '<input type="text" id="nss_number_of_page" name="nss_option_page_item[nss_number_of_page]" value="%s" />',
            isset( $this->nss_option_page_number['nss_number_of_page'] ) ? esc_attr( $this->nss_option_page_number['nss_number_of_page']) : ''
        );
	}

    /* method decleartion */
    function nss_discountproduct_items()
    {  
        $this->nss_option_page_number = get_option( 'nss_option_page_item' );
        $nss_product_params = array(
            'posts_per_page' => $this->nss_option_page_number['nss_number_of_page'],
            'post_type' => array('product'),
            'order'=>'ASC',
            'orderby'=>'rand',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => '_sale_price',
                    'value'=>'',
                    'compare' => '>=',
                    'type' => 'TIME'
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key' => '_sale_price_dates_from',
                        'value' => strtotime(date('Y-m-d')),
                        'compare' => '<=',
                        'type' => 'TIME'
                    ),
                    array(
                        'key' => '_sale_price_dates_to',
                        'value' => strtotime(date('Y-m-d')),
                        'compare' => '>=',
                        'type' => 'TIME'
                    ),
                )
            )
        );
        ?>
        <div class="nss_woo_product_main">
        <?php
            $nss_product_que = new WP_Query($nss_product_params);   
            if ($nss_product_que->have_posts())
            {
                while ($nss_product_que->have_posts())
                {
                    $nss_product_que->the_post();
                    ?>
                    <a href = "<?php the_permalink(); ?>" title = "<?php the_title_attribute(); ?>">
                        <h2><?php echo get_the_title(); ?></h2>
                        <?php
                        if (has_post_thumbnail())
                        {
                            the_post_thumbnail( 'full' );
                        }                   
                        ?>
                    </a>
                    <div class="nss_price_cart">
                        <?php
                            $nss_p_id=get_the_ID();
                            echo do_shortcode( '[add_to_cart id=' . $nss_p_id . ']' )
                        ?>
                    </div>
                    <?php
                }
            } 
            else
            {
                echo esc_html__('No product Found Available Here!', 'nsstheme');
            }
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }
}
