<?php
/**
* QueryWall Notice
*
* Notice class for QueryWall.
*
* @package QueryWall
* @since   1.0.6
*/
defined( 'ABSPATH' ) or die( 'You shall not pass!' );
if ( ! class_exists( 'QWall_Notice' ) ):
class QWall_Notice {
    /**
    * Message to be shown
    */
    private $message;
    /**
    * CSS classes to apply on the notice div
    */
    private $css_classes = array( 'notice' );
    /**
    * Link text for permanent dismissal
    */
    private $dismiss_message = '';
    /**
    * Option to store permanent dismiss
    */
    private $dismiss_option = '';
    /**
    * Permanent dismiss link variable
    */
    private $dismiss_link = '';
    /**
    * Magic starts here.
    *
    * @param  string  $message      Message to be shown
    * @param  array   $css_classes  CSS classes to apply on the notice div
    *
    * @since 1.0.6
    * @return void
    */
    public function __construct( $message, $css_classes, $dismiss_message = '', $dismiss_option = '', $dismiss_link = '' ) {
        $this->message = $message;
        if( ! empty( $css_classes ) && is_array( $css_classes ) ) {
            $this->css_classes = array_merge( $this->css_classes, $css_classes );
        }
        if( !empty( $dismiss_message ) && is_string( $dismiss_message ) ) {
            $this->dismiss_message = $dismiss_message;
            if( ! empty( $dismiss_option ) && is_string( $dismiss_option ) ) {
                $this->dismiss_option = $dismiss_option;
                if(empty( $dismiss_link ) || !is_string( $dismiss_link ) ) {
                    $this->dismiss_link = $dismiss_option;
                }else{
                    $this->dismiss_link = $dismiss_link;
                }
            }
        }
        add_action( 'admin_init', array( $this, 'dismiss_admin_notice' ));
        add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
    }
    /**
    * Displays admin notice on success, error, warning, etc.
    *
    * @since 1.0.6
    * @return void
    */
    public function display_admin_notice() {
        global $current_user;
        $user_id = $current_user->ID;
        if ( empty( $this->dismiss_option ) || $this->dismiss_option == '' || !get_user_meta($user_id, $this->dismiss_option)) {
            ?>
  <div class="<?php echo implode( ' ', $this->css_classes ); ?>">
    <p>
      <?php echo $this->message; ?>
        <?php if ( !empty( $this->dismiss_option ) && !$this->dismiss_option == '' ) {
                ?>
          </br><a href="?<?=$this->dismiss_link;?>" class="notice-permanent-dismiss"><?=$this->dismiss_message;?></a>
          <?php $this->print_css();
            } ?>
    </p>
  </div>
  <?php
        }
    }
    
    
    public function dismiss_admin_notice() {
        //wp_die((isset($_GET[$this->dismiss_link])?$this->dismiss_link:'not set'));
        if ( !empty( $this->dismiss_option ) && !$this->dismiss_option == '' ) {
            global $current_user;
            $user_id = $current_user->ID;
            if (isset($_GET[$this->dismiss_link])) {
                add_user_meta($user_id, $this->dismiss_option, 'true', true);
            }
        }
    }
    
    public static function print_css() {
        //wp_die(__FILE__);
        ?>
    <!-- QWALL DISMISSABLE NOTICE -->
    <style type="text/css">
      .notice-permanent-dismiss {
        position: absolute;
        bottom: -5px;
        right: 23px;
        border: none;
        margin: 0;
        padding: 9px;
        background: 0 0;
        color: #b4b9be;
        cursor: pointer;
        text-decoration: none;
      }
      
      .notice-permanent-dismiss:after {
        position: inherit;
        bottom: 6px;
        background: 0 0;
        color: #b4b9be;
        content: "\f153";
        font: 400 16px/20px dashicons;
        speak: none;
        height: 20px;
        text-align: center;
        width: 20px;
        -webkit-font-smoothing: antialiased;
        margin-left: 2px;
      }
      
      .notice-permanent-dismiss:active,
      .notice-permanent-dismiss:focus,
      .notice-permanent-dismiss:hover {
        color: #c00;
      }
      
      .notice-permanent-dismiss:active:after,
      .notice-permanent-dismiss:focus:after,
      .notice-permanent-dismiss:hover:after {
        color: #c00;
      }
    </style>
    <?php
    }
}
endif;