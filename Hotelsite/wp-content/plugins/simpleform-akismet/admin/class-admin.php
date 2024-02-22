<?php

/**
 * Defines the admin-specific functionality of the plugin.
 *
 * @since      1.0
 */
	 
class SimpleForm_Akismet_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0
	 */
	
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0
	 */
	
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0
	 */
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0
	 */
	
	public function enqueue_scripts($hook){
	    		
     global $sform_settings;
	 if( $hook != $sform_settings )
	 return;

     wp_enqueue_script( 'sform_akismet', plugin_dir_url( __FILE__ ) . 'js/admin.js', array( 'jquery' ), $this->version, false );
	      
	}

    /**
     * Add new Akismet field in the settings page.
     *
     * @since    1.0
     */  
     
    public function akismet_settings_field() {
	
 	 $id = isset( $_REQUEST['form'] ) ? absint($_REQUEST['form']) : '1'; 
     $settings = get_option('sform_settings'); 
     $color = ! empty( $settings['admin_color'] ) ? esc_attr($settings['admin_color']) : 'default';
     $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
     // Check for an Askimet API Key
     $akismet_key = get_option('wordpress_api_key');
     // Check if Akismet is installed with the corresponding API key
     $akismet_notes = function_exists('akismet_http_post') && ! empty($akismet_key) ? '&nbsp;' : __('You need to activate Akismet plugin and register a valid API Key', 'simpleform-akismet' );
     $akismet_action = ! empty( $settings['akismet_action'] ) ? esc_attr($settings['akismet_action']) : 'blocked';
     $spam_mark = ! empty( $settings['spam_mark'] ) ? stripslashes(esc_attr($settings['spam_mark'])) : '***'.esc_attr__('SPAM', 'simpleform-akismet').'***';
     $plugin_file = 'simpleform-contact-form-submissions/simpleform-submissions.php';
     $data_storing = ! empty( $settings['data_storing'] ) ? esc_attr($settings['data_storing']) : 'true';
     if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) { $notes = __('To report false detections to Akismet, you need to install the SimpleForm Contact Form Submissions addon', 'simpleform-akismet' ); }
     elseif ( ! class_exists( 'SimpleForm_Submissions' ) ) { $notes = __('To report false detections to Akismet, you need the SimpleForm Contact Form Submissions addon activated', 'simpleform-akismet' ); }
     elseif ( $data_storing != 'true' ) { $notes = __('To report false detections to Akismet, you need to enable the form data storing in the database', 'simpleform-akismet' ); }
	 else { $notes = '&nbsp;'; }
     $action_notes = $akismet_action == 'blocked' ? '&nbsp;' : $notes;
     $disabled = 'disabled="disabled"';
     ?>
     
     <h2 id="h2-akismet" class="options-heading"><span class="heading" section="akismet"><?php _e( 'Akismet Protection', 'simpleform-akismet' ) ?><span class="toggle dashicons dashicons-arrow-up-alt2 akismet"></span></span><?php if ( $id != '1' ) { ?><a href="<?php echo menu_page_url( 'sform-settings', false ); ?>"><span class="dashicons dashicons-edit icon-button akismet <?php echo $color ?>"></span><span class="settings-page wp-core-ui button akismet"><?php _e( 'Go to main settings for edit', 'simpleform-akismet' ) ?></span></a><?php } ?></h2>

     <div class="section akismet"><table class="form-table akismet"><tbody>

     <tr><th class="option"><span><?php _e('Akismet Anti-Spam','simpleform-akismet') ?></span></th><td id="tdakismet" class="checkbox-switch notes <?php if ($akismet !='true') { echo 'last'; } ?>"><div class="switch-box"><label class="switch-input"><input type="checkbox" name="akismet" id="akismet" class="sform-switch" value="false" <?php checked( $akismet, 'true'); if ( $id != '1' ) { echo $disabled; } ?>><span></span></label><label for="akismet" class="switch-label <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><?php _e('Enable Akismet Anti-Spam protection','simpleform-akismet') ?></label></div><p class="description"><?php echo $akismet_notes ?></p></td></tr>
	 
     <tr class="trakismet <?php if ( $akismet == 'false' ) {echo 'unseen';}?>"><th class="option"><span><?php _e( 'Akismet Action Type', 'simpleform-akismet' ) ?></span></th><td id="tdakismetaction" class="radio notes <?php if ($akismet == 'true' && $akismet_action == 'blocked') { echo 'last'; } ?>"><fieldset><label for="blocked-message" class="radio <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><input id="blocked-message" type="radio" name="akismet-action" value="blocked" <?php checked( $akismet_action, 'blocked'); if ( $id != '1' ) { echo $disabled; } ?> ><?php _e( 'Block the message and display a submission error', 'simpleform-akismet' ) ?></label><label for="flagged-message" class="radio <?php if ( $id != '1' ) { echo 'disabled'; } ?>"><input id="flagged-message" type="radio" name="akismet-action" value="flagged" <?php checked( $akismet_action, 'flagged'); if ( $id != '1' ) { echo $disabled; } ?> ><?php _e( 'Send the message marked as spam', 'simpleform-akismet' ) ?></label></fieldset><p id="akismet-action-notes" class="description <?php if ($akismet_action =='blocked') { echo 'invisible'; } ?>"><?php echo $action_notes ?></p></td></tr>
     
     <tr class="trakismet trspammark <?php if ( $akismet == 'false' || $akismet_action == 'blocked') { echo 'unseen'; }?>" ><th class="option last"><span><?php _e( 'Spam Mark', 'simpleform-akismet' ) ?></span></th><td class="text last"><input class="sform" name="spam-mark" placeholder="<?php esc_attr_e( 'Enter a word to be included in the subject of the message to mark it as spam', 'simpleform-akismet' ) ?>" id="spam-mark" type="text" value="<?php echo $spam_mark; ?>" <?php if ( $id != '1' ) { echo $disabled; } ?>\></td></tr>
 
     </tbody></table></div>

     <?php
		
    }	

    /**
     * Add error message for Akismet in the settings page.
     *
     * @since    1.0
     */  
     
    public function akismet_message_field() {

      $settings = get_option('sform_settings'); 
      $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
      $akismet_error = ! empty( $settings['akismet_error'] ) ? stripslashes(esc_attr($settings['akismet_error'])) : esc_attr_e( 'There was an error trying to send your message. Please try again later!', 'simpleform-akismet' );
      $outside_error = ! empty( $settings['outside_error'] ) ? esc_attr($settings['outside_error']) : 'bottom'; 
      if ( $outside_error == 'top' ) {
      /* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
      $error_position = __('top', 'simpleform');
      } else {
      /* translators: Used in place of %s in the string: "Please enter an error message to be displayed on %s of the form" */
      $error_position = __('bottom', 'simpleform');
      }
      ?>	

      <tr class="trakismet <?php if ( $akismet == 'false' ) {echo 'unseen';}?>"><th class="option"><span><?php _e('Spam Error','simpleform-akismet') ?></span></th><td class="text"><input class="sform out" name="akismet-error" placeholder="<?php echo sprintf( __( 'Please enter an error message to be displayed on %s of the form in case the message is considered spam', 'simpleform-akismet' ), $error_position ) ?>" id="akismet-error" type="text" value="<?php echo $akismet_error; ?>" \></td></tr>

      <?php
		
    }	
	    
	/**
	 * Add Akismet related fields values in the settings options array.
	 *
	 * @since    1.0
	 */
	
    public function add_array_akismet_settings() { 
  
      $id = isset( $_REQUEST['form'] ) ? absint($_REQUEST['form']) : '1';
      $main_settings = get_option('sform_settings'); 
      $akismet_error = isset($_POST['akismet-error']) ? sanitize_text_field(trim($_POST['akismet-error'])) : '';
     
      if ( $id == '1' ) {
      $akismet = isset($_POST['akismet']) ? 'true' : 'false';
      $akismet_action = isset($_POST['akismet-action']) ? sanitize_key($_POST['akismet-action']) : '';
      $spam_mark = isset($_POST['spam-mark']) ? sanitize_text_field(trim($_POST['spam-mark'])) : '***'.__('SPAM', 'simpleform').'***';
      $new_items = array( 'akismet' => $akismet, 'akismet_action' => $akismet_action, 'spam_mark' => $spam_mark, 'akismet_error' => $akismet_error );
      }
      else {
      $new_items = array( 'akismet' => $main_settings['akismet'], 'akismet_action' => $main_settings['akismet_action'], 'spam_mark' => $main_settings['spam_mark'], 'akismet_error' => $akismet_error );
      }

      return  $new_items;

    }
    
	/**
	 * Validate Akismet related fields in Settings page.
	 *
	 * @since    1.0
	 */
	
    public function validate_akismet_fields(){
	    
      $akismet = isset($_POST['akismet']) ? 'true' : 'false';
      $akismet_key = get_option('wordpress_api_key');

      // Check if Akismet plugin is installed
	  if ( $akismet == 'true' && ! function_exists( 'akismet_http_post' ) ) {
           echo json_encode(array('error' => true, 'update' => false, 'message' => __('You need to activate Akismet plugin for enabling Akismet Anti-Spam', 'simpleform-akismet') ));
	       exit; 
      }
        
      // Check if Akismet API keys exist before saving settings
      if ( $akismet == 'true' && function_exists( 'akismet_http_post' ) && empty($akismet_key) ) {
           echo json_encode(array('error' => true, 'update' => false, 'message' => __('You need to register Akismet API Key for enabling Akismet Anti-Spam', 'simpleform-akismet') ));
	       exit; 
      }
  
    }

	/**
	 * Validate form with Akismet when AJAX is enabled.
	 *
	 * @since    1.0
	 */
	
    public function akismet_ajax_checking( $name, $email, $message ){ 
	
      // Perform Akismet validation if Akismet plugin is enabled and a key is configured
      if ( class_exists('Akismet') && method_exists('Akismet', 'get_api_key') && Akismet::get_api_key() ) { 
        
        $settings = get_option('sform_settings'); 
        $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
        $akismet_action = ! empty( $settings['akismet_action'] ) ? esc_attr($settings['akismet_action']) : 'blocked';
        $akismet_key = get_option('wordpress_api_key');
        $akismet_error = ! empty( $settings['akismet_error'] ) ? stripslashes(esc_attr($settings['akismet_error'])) : esc_attr__( 'There was an error trying to send your message. Please try again later!', 'simpleform-akismet' );
        $args["name"] = $name; 
        $args["email"] = $email; 
        $args["message"] = $message;

       if( $akismet == 'true' && $akismet_action == 'blocked' && ! empty($akismet_key) && $this->akismet_message_check($args) ) {
          echo json_encode(array('error' => true, 'notice' => $akismet_error, 'showerror' => true ));
	      exit; 
        }
        
      }

    }	
    
	/**
	 * Send to Akismet false reports.
	 *
	 * @since    1.0
	 */
    
    public function akismet_reports( $ids, $type, $msg ){ 
	
      $msg = '';

      // Perform Akismet reports if Akismet plugin is enabled and a key is configured
	  if ( SimpleForm_Akismet::is_akismet_protection() ) {
		
		if ( $type = 'spam') {
		  if ( !is_array($ids) ) { $this->submit_spam($ids); }
		  else { foreach ( $ids as $id ) { $this->submit_spam($id); } }
	      $msg = '&nbsp;' . __( '(False negative report sent to Akismet)', 'simpleform-contact-form-submissions' );	  
        }
		
		if ( $type = 'unspam') {
		  if ( !is_array($ids) ) { $this->submit_ham($ids); }
		  else { foreach ( $ids as $id ) { $this->submit_ham($id); } }
	      $msg = '&nbsp;' . __( '(False positive report sent to Akismet)', 'simpleform-contact-form-submissions' );	  
        }
                
      }
      
      return $msg;

    }	
    
    public function submit_false_negative( $ids, $msg ){ 
	
      $msg = '';

	  if ( SimpleForm_Akismet::is_akismet_protection() ) {
		
		  if ( !is_array($ids) ) { $this->submit_spam($ids); }
		  else { foreach ( $ids as $id ) { $this->submit_spam($id); } }
	      $msg = '&nbsp;' . __( '(False negative report sent to Akismet)', 'simpleform-contact-form-submissions' );	  
      }
      
      return $msg;

    }	
  
  	/**
	 * Send to Akismet false reports.
	 *
	 * @since    1.0
	 */
    
    public function submit_false_positive( $ids, $msg ){ 
	
      $msg = '';

	  if ( SimpleForm_Akismet::is_akismet_protection() ) {
		
		  if ( !is_array($ids) ) { $this->submit_ham($ids); }
		  else { foreach ( $ids as $id ) { $this->submit_ham($id); } }
	      $msg = '&nbsp;' . __( '(False positive report sent to Akismet)', 'simpleform-contact-form-submissions' );	  
      }
      
      return $msg;

    }	
    
	/**
	 * Validate form with Akismet when AJAX is not enabled.
	 *
	 * @since    1.0
	 */
	
    public function akismet_checking($errors,$form_id,$name,$email,$message) {
	      
      // Perform Akismet validation if Akismet plugin is enabled and a key is configured
      if ( class_exists('Akismet') && method_exists('Akismet', 'get_api_key') && Akismet::get_api_key() ) { 

        $settings = get_option('sform_settings'); 
        $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
        $akismet_action = ! empty( $settings['akismet_action'] ) ? esc_attr($settings['akismet_action']) : 'blocked';
        $akismet_key = get_option('wordpress_api_key');
        $args["name"] = $name; 
        $args["email"] = $email; 
        $args["message"] = $message;

        if( $akismet == 'true' && $akismet_action == 'blocked' && ! empty($akismet_key) && $this->akismet_message_check($args) ) {
			$errors .= $form_id.';spam;';		
        }

      }	
 	  
 	  return  $errors; 
	
    }	
    
	/**
	 * Display an error when AJAX is not enabled and the Akismet filter is not passed.
	 *
	 * @since    1.0
	 */
	
    public function akismet_error($error_class){

        $settings = get_option('sform_settings'); 
        $akismet_error = ! empty( $settings['akismet_error'] ) ? stripslashes(esc_attr($settings['akismet_error'])) : __( 'There was an error trying to send your message. Please try again later!', 'simpleform-akismet' );
        $error = ''; 
        
        if ( isset($error_class['spam']) ) {
             $error = $akismet_error; 
        } 

        return $error;

    }
    
	/**
	 * Retrieve Akismet action type. Mark message as spam if Akismet validation fails and settings allow submission.
	 *
	 * @since    1.0
	 */
	
    public function akismet_action( $flagged, $name, $email, $message ) {
	      
      // Perform Akismet validation if Akismet plugin is enabled and a key is configured
      if ( class_exists('Akismet') && method_exists('Akismet', 'get_api_key') && Akismet::get_api_key() ) { 

        $settings = get_option('sform_settings'); 
        $akismet = ! empty( $settings['akismet'] ) ? esc_attr($settings['akismet']) : 'false';
        $akismet_key = get_option('wordpress_api_key');
        $akismet_action = ! empty( $settings['akismet_action'] ) ? esc_attr($settings['akismet_action']) : 'blocked';
        $args["name"] = $name; 
        $args["email"] = $email; 
        $args["message"] = $message;
        
       if( $akismet == 'true' && $akismet_action == 'flagged' && ! empty($akismet_key) && $this->akismet_message_check($args) ) {
            $spam_mark = ! empty( $settings['spam_mark'] ) ? stripslashes(esc_attr($settings['spam_mark'])) : '***'.__('SPAM', 'simpleform').'***';
			$flagged = $spam_mark . ' ';
        }

      }	
 	  
 	  return  $flagged; 
	
    }	
 
	/**
	 * Add new form data values when form is submitted.
	 *
	 * @since    1.0
	 */ 
	 
     public function spam_parameters_values($extra_values, $form_id, $name, $email) { 

      if ( $form_id == '1' ) {
       $settings = get_option('sform_settings');
	  } else {
       $settings_option = get_option('sform_'. $form_id .'_settings');
       $settings = $settings_option != false ? $settings_option : get_option('sform_settings');
      }

      $data_storing = ! empty( $settings['data_storing'] ) ? esc_attr($settings['data_storing']) : 'true';	
      $values = '';
	  $akismet = new SimpleForm_Akismet();
	  
	  if ( $akismet->is_akismet_protection() && $data_storing == 'true' ) {

	    $values = $_SERVER['REMOTE_ADDR'] .';';
	    $values .= $_SERVER['HTTP_USER_AGENT'] .';';
	    $values .= $_SERVER['HTTP_REFERER'] .';';
	    $values .= get_option('home') .';';	  
	    $values .= get_locale() .';';
	    $values .= get_option('blog_charset') .';';
	    if ( $permalink = get_permalink() ) { $values .= $permalink .';'; }	 else { $values .= ';'; }
	    $values .= $name .';';
	    $values .= $email;
		  
      } 

	  $spam_parameters = array( "spam_parameters" => $values);
      
      return  $spam_parameters;
     
     } 
   
	/**
	 * Run Akismet spam detection.
	 *
	 * @since    1.0
	 */
	
    public function akismet_message_check($args){
	
	  $query['user_ip'] = $_SERVER['REMOTE_ADDR'];
	  $query['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	  $query['referrer'] = $_SERVER['HTTP_REFERER'];
	  $query['blog'] = get_option('home');	  
	  $query['blog_lang'] = get_locale();
	  $query['blog_charset'] = get_option('blog_charset');
	  $query['comment_type'] = 'contact-form';
	  if ( $permalink = get_permalink() ) { $query['permalink'] = $permalink; }	 
	  $query['comment_author'] = $args["name"];
	  $query['comment_author_email'] = $args["email"];
	  $query['comment_content'] = $args["message"];     
      
	  $query_string = build_query($query);
	
	  $spam = false;

      // Build a query and make a request to Akismet
	  if ( is_callable( array( 'Akismet', 'http_post' ) ) ) {
		$response = Akismet::http_post( $query_string, 'comment-check' );
	  }
	  
      if ( $response[1] == 'true' ) {
		$spam = true;
	  }

	  return $spam;
    
    }
    
 	/**
	 * Fallback for database table updating if plugin is already active.
	 *
	 * @since    1.0
	 */
    
    public function version_check() {
    
      $current_db_version = SIMPLEFORM_AKISMET_DB_VERSION; 
      $installed_version = get_option('sform_aks_db_version');
    
      if ( $installed_version != $current_db_version ) {
	    // global $wpdb;
        // $wpdb->query("ALTER TABLE {$wpdb->prefix}sform_submissions ADD COLUMN spam_parameters VARCHAR(1024) NOT NULL AFTER status");          
        require_once SIMPLEFORM_AKISMET_PATH . 'includes/class-activator.php';
	    SimpleForm_Akismet_Activator::sform_akismet_settings();
	    SimpleForm_Akismet_Activator::change_db();
      }

    }

   /**
	 * Add action links in the plugin meta row
	 *
	 * @since    1.0
	 */
	
    public function plugin_links( $plugin_actions, $plugin_file ){ 
     
     $new_actions = array();
     
	 if ( SIMPLEFORM_AKISMET_BASENAME === $plugin_file ) { 
     $new_actions['sform_settings'] = '<a href="' . menu_page_url( 'sform-settings', false ) . '">' . __('Settings', 'simpleform') . '</a>';
	 }

     return array_merge( $new_actions, $plugin_actions );

    }
    
    /**
	 * Add message in the plugin meta row if core plugin is missing
	 *
	 * @since    1.0
	 */
	
    public function plugin_meta( $plugin_meta, $file ) {

	  $plugin_file = 'simpleform/simpleform.php';
	  
      if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugin_file )  && strpos( $file, SIMPLEFORM_AKISMET_BASENAME ) !== false ) {

 	  $plugin_url =  __( 'https://wordpress.org/plugins/simpleform/' );
      $message = '<a href="'.esc_url($plugin_url).'" target="_blank" style="color: orangered !important;">' . __('Install the SimpleForm plugin to allow this addon to work', 'simpleform-akismet' ) . '</a>';
	  $plugin_meta[] = $message;
	  
	  }
				
	  return $plugin_meta;

	}
	
	/**
	 * Retrieve form submission data from the database.
	 *
	 * @since    1.0
	 */
	
    public function form_submission_data( $id ) {
	
      $id = isset( $id ) ? absint($id) : '';
      $data = array();
      
      if ( ! empty($id) ) {
        global $wpdb;
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT object, spam_parameters FROM {$wpdb->prefix}sform_submissions WHERE id = %d", $id ) );
        if ( $results ) { 
          foreach ($results as $field) { 
           $data = $field->spam_parameters . ';' . $field->object;
          }
        }
      }	
 	  
 	  return  $data; 
	
    }	
	
	/**
	 * Report missed spam.
	 *
	 * @since    1.0
	 */
	
    public function submit_spam($id){
	    
	  $submission_data = $this->form_submission_data($id);
	  
	  $data = explode(';', $submission_data);
	  
	  $query = array();
	  
	  $query['user_ip'] = isset($data[0]) ? $data[0] : '';
	  $query['user_agent'] = isset($data[1]) ? $data[1] : ''; 
	  $query['referrer'] = isset($data[2]) ? $data[2] : '';
	  $query['blog'] = isset($data[3]) ? $data[3] : '';	  
	  $query['blog_lang'] = isset($data[4]) ? $data[4] : '';	
	  $query['blog_charset'] = isset($data[5]) ? $data[5] : '';
	  $query['permalink'] = isset($data[6]) ? $data[6] : '';
	  $query['comment_type'] = 'contact-form';
	  $query['comment_author'] = isset($data[7]) ? $data[7] : '';
	  $query['comment_author_email'] = isset($data[8]) ? $data[8] : '';
	  $query['comment_content'] = isset($data[9]) ? $data[9] : '';   

	  $query_string = build_query($query);
	
	  $submission = false;

	  if ( is_callable( array( 'Akismet', 'http_post' ) ) ) {
		$response = Akismet::http_post( $query_string, 'submit-spam' );
	  }
	  
      if ( $response[1] == 'Thanks for making the web a better place.' ) {
		$submission = true;
	  }

	  return $submission;
    
    }
	
	/**
	 * Report false positive.
	 *
	 * @since    1.0
	 */
	
    public function submit_ham($id){
	    
	  $submission_data = $this->form_submission_data($id);
	  
	  $data = explode(';', $submission_data);
	  
	  $query = array();
	  
	  $query['user_ip'] = isset($data[0]) ? $data[0] : '';
	  $query['user_agent'] = isset($data[1]) ? $data[1] : ''; 
	  $query['referrer'] = isset($data[2]) ? $data[2] : '';
	  $query['blog'] = isset($data[3]) ? $data[3] : '';	  
	  $query['blog_lang'] = isset($data[4]) ? $data[4] : '';	
	  $query['blog_charset'] = isset($data[5]) ? $data[5] : '';
	  $query['permalink'] = isset($data[6]) ? $data[6] : '';
	  $query['comment_type'] = 'contact-form';
	  $query['comment_author'] = isset($data[7]) ? $data[7] : '';
	  $query['comment_author_email'] = isset($data[8]) ? $data[8] : '';
	  $query['comment_content'] = isset($data[9]) ? $data[9] : '';    

	  $query_string = build_query($query);
	
	  $submission = false;

	  if ( is_callable( array( 'Akismet', 'http_post' ) ) ) {
		$response = Akismet::http_post( $query_string, 'submit-ham' );
	  }
	  
      if ( $response[1] == 'Thanks for making the web a better place.' ) {
		$submission = true;
	  }

	  return $submission;
    
    }

}