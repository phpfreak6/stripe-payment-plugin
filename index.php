<?php  
/**
 * Plugin Name: Stripe Manage Payment Plugin
 * Plugin URI: http://www.iamwhiz.com
 * Description: This plugin adds diffrent payment-forms of Stripe payments.
 * Version: 1.3.3
 * Author: Atul
 * Author URI: http://atul.iamwhiz.com
 * License: GPL2
 */
// register jquery and style on initialization

add_action('wp_enqueue_scripts', 'register_scripts');
function register_scripts() {
	
	wp_register_style( 'style-plugin', plugins_url('/style-plugin.css', __FILE__));
	
	wp_register_script( 'custom_jquery', plugins_url('/js/bootstrap.min.js', __FILE__), array('jquery'), '1.1', true );
	wp_register_script( 'custom_jquery', plugins_url('/js/jquery.min.js', __FILE__), array('jquery'), '1.1', true );
	wp_register_script( 'custom_jquery', plugins_url('/js/custom.js', __FILE__), array('jquery'), '1.1', true );
}
// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'enqueue_styles');
function enqueue_styles(){
	wp_enqueue_style( 'style-plugin' );
    wp_enqueue_script('custom_jquery');
}

/***********************
Database Query 
***********************/
register_activation_hook( __FILE__, 'create_plugin_database_tables' );
function create_plugin_database_tables() {
 global $wpdb;
 $table_name = $wpdb->prefix . 'stripe_payment_tbl';
 if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
	//die('here');
 $sql = "CREATE TABLE IF NOT EXISTS $table_name (
 id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  uk_price varchar(50) NOT NULL,
  usa_price varchar(50) NOT NULL,
  aus_price varchar(50) NOT NULL,
  eur_price varchar(50) NOT NULL,
  postage_uk varchar(50) NOT NULL,
  postage_usa varchar(50) NOT NULL,
  postage_aus varchar(50) NOT NULL,
  postage_eur varchar(50) NOT NULL,
  level_id_uk varchar(255) NOT NULL,
  level_id_usa varchar(255) NOT NULL,
  level_id_aus varchar(255) NOT NULL,
  level_id_eur varchar(255) NOT NULL,
  plan_id_uk varchar(255) NOT NULL,
  update_plan_uk  varchar(255) NOT NULL,
  degrade_plan_uk varchar(255) NOT NULL,
  plan_id_usa varchar(255) NOT NULL,
  update_plan_usa  varchar(255) NOT NULL,
  degrade_plan_usa varchar(255) NOT NULL,
  plan_id_aus varchar(255) NOT NULL,
  update_plan_aus  varchar(255) NOT NULL,
  degrade_plan_aus varchar(255) NOT NULL,
  plan_id_eur varchar(255) NOT NULL,
  update_plan_eur varchar(255) NOT NULL,
  degrade_plan_eur varchar(255) NOT NULL,
  billing_address enum('yes','no') NOT NULL DEFAULT 'no',
  form_location varchar(255) NOT NULL,
  form_top_text varchar(255) NOT NULL,
  form_bottom_text varchar(255) NOT NULL,
  redirect_url varchar(255) NOT NULL,
  custom_url varchar(255) NOT NULL,
  button_label varchar(50) NOT NULL,
  fornt_btn_label varchar(255) NOT NULL,
  front_btn_css varchar(255) NOT NULL,
  course_text_uk varchar(255) NOT NULL,
  course_text_usa varchar(255) NOT NULL,
  course_text_aus varchar(255) NOT NULL,
  course_text_eur varchar(255) NOT NULL,
  
  additional_text_uk text NULL,
  additional_text_usa text NULL,
  additional_text_aus text NULL,
  additional_text_eur text NULL,
  
  custom_btn_css varchar(255) NOT NULL,
  bottom_text_css varchar(255) NOT NULL,
  top_text_css varchar(255) NOT NULL,
  custom_css varchar(255) NOT NULL,
  registrartion enum('yes','no') NOT NULL DEFAULT 'no',
  subscription enum('yes','no') NOT NULL DEFAULT 'no',
  status enum('enable','disable') NOT NULL DEFAULT 'enable',
  PRIMARY KEY  (id)
 );";
 
 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 dbDelta( $sql );
 }else{
	//die('there');
	$wpdb->query("ALTER TABLE $table_name ADD fornt_btn_label varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD front_btn_css varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD additional_text_uk text NULL");
	$wpdb->query("ALTER TABLE $table_name ADD additional_text_usa text NULL");
	$wpdb->query("ALTER TABLE $table_name ADD additional_text_aus text NULL");
	$wpdb->query("ALTER TABLE $table_name ADD additional_text_eur text NULL");
	
	$wpdb->query("ALTER TABLE $table_name ADD update_plan_uk  varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD update_plan_usa varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD update_plan_aus varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD update_plan_eur varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD degrade_plan_uk  varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD degrade_plan_usa varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD degrade_plan_aus varchar(255) NOT NULL");
	$wpdb->query("ALTER TABLE $table_name ADD degrade_plan_eur varchar(255) NOT NULL");
 }

}

/********************************************************************************
create table function for saved susbcription ID, User ID, Level ID and Charge ID
********************************************************************************/
register_activation_hook( __FILE__, 'create_plugin_database_history_tables' );
function create_plugin_database_history_tables() {
 global $wpdb;
 $histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
 if($wpdb->get_var("SHOW TABLES LIKE '$histry_table_name'") != $histry_table_name) {
	//die('here');
 $sql = "CREATE TABLE IF NOT EXISTS $histry_table_name (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) NOT NULL,
  btn_id int(11) NOT NULL,
  country varchar(50) NOT NULL,
  charge_id varchar(50) NOT NULL,
  subscription_id varchar(50) NOT NULL,
  level_id varchar(50) NOT NULL,
  status enum('active','cancelled') NOT NULL DEFAULT 'active',
  label_status enum('active','cancelled') NOT NULL DEFAULT 'active',
  subs_end_date date NOT NULL,
  PRIMARY KEY  (id)
 );";
 
 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
 dbDelta( $sql );
 }else{
	 
	$wpdb->query("ALTER TABLE $histry_table_name ADD country  varchar(50) NOT NULL");
	$wpdb->query("ALTER TABLE $histry_table_name ADD btn_id int(11) NOT NULL");
	$wpdb->query("ALTER TABLE $histry_table_name ADD status enum('active','cancelled') NOT NULL DEFAULT 'active'");
	$wpdb->query("ALTER TABLE $histry_table_name ADD label_status enum('active','cancelled') NOT NULL DEFAULT 'active'");
	$wpdb->query("ALTER TABLE $histry_table_name ADD subs_end_date date NOT NULL");
	 
 }

}

add_action('admin_menu', 'stripe_payment');
function stripe_payment(){

add_menu_page(' Stripe Payments', ' Stripe Payments ', 'administrator','view_payment', 'stripe_payment_menu_page', plugins_url('images/asp-dashboard-menu-icon.png', __FILE__),null,10);

add_submenu_page('view_payment', 'Add Button', 'Add Button', 'administrator', 'add_stripeButton', 'stripe_payment_sub_menu_page'); 

add_submenu_page('view_payment', 'Settings', 'Settings', 'administrator', 'setting', 'stripe_payment_sub_menu_page2'); 

}

function stripe_payment_menu_page()
{	
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
    include_once "template/view_all_payment.php";
}

function stripe_payment_sub_menu_page()
{	
	global $wpdb;
	include_once('vendor/init.php') ;
	if(get_option("stripe_payment_mode") == "sandbox"){
		$stripepk_Key = get_option("sandbox_publish_key");
		$stripesk_Key = get_option('sandbox_secret_key');
	}else{
		$stripepk_Key = get_option("live_publish_key");
		$stripesk_Key = get_option('live_secret_key');
	}
	\Stripe\Stripe::setApiKey($stripesk_Key);
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
    include_once "template/add_new_button.php";
}

function stripe_payment_sub_menu_page2(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
	include_once "template/setting.php";
	
}

function stripePayment($atts)
{
	
	ob_start();
    // normalize attribute keys, lowercase
	global $wpdb;
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
	$id = $atts['id'];
	$total = $wpdb->get_row( "SELECT * FROM $table_name where id = $id");
	$topcolor = $total->top_text_css;
	$bottomcolor = $total->bottom_text_css;
	$btncolor = $total->custom_btn_css;
	$front_btncolor = $total->custom_css;
	$front_btn_text = $total->fornt_btn_label;
	$front_btn_css = $total->front_btn_css;
	$desc = $total->name;
	
	if(empty($topcolor)){
		$topcolor = '#23282D';
	}elseif(empty($bottomcolor)){
		$bottomcolor = '#23282D';
	}elseif(empty($btncolor)){
		$btncolor = '#f7b977';
	}elseif(empty($front_btncolor)){
		$front_btncolor = '#7E7E7E';
	}
	elseif(empty($front_btn_css)){
		$front_btn_css = '#fff';
	}
	
	require_once 'vendor/init.php';
	
	$user_ID = get_current_user_id();
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
		
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
	}
	
	try{
	if($wkcustomer_id){
		\Stripe\Stripe::setApiKey($stripesk_Key);
		$cu = \Stripe\Customer::Retrieve(
		  array("id" => $wkcustomer_id, "expand" => array("default_source"))
		);
		// echo '<pre>'; print_r($cu); die('ok');
		$last4 = $cu->default_source->last4;
		$exp_year = $cu->default_source->exp_year;
		$exp_month = $cu->default_source->exp_month;
	}
	}catch(Exception $e){
		
		
		
	}
	$wk_logged_user = wp_get_current_user();
	$payName	 = '';
	$paylastName = '';
	$payEmail	 = '';
	if($wk_logged_user){
		$payName 	 = $wk_logged_user->user_firstname;
		$paylastName = $wk_logged_user->user_lastname ;
		$payEmail 	 = $wk_logged_user->user_email;
	}
	$html = '';
	$pk_front_btn = ($front_btn_text) ? $front_btn_text : "Pay Now";
	$html .= '<button type="button" style="background-color:'.$front_btncolor.' !important;color:'.$front_btn_css.' !important;" class="et_pb_button wk_btn" data-toggle="modal" data-target="#wz_show_pay_form_'.$id.'">'.$pk_front_btn.'</button>';
	?>
	<script>
	jQuery(document).ready(function(){
		jQuery("#wknw_card").click(function(){
			jQuery("#pay_new_card").show();
		});
		jQuery("#wkold_card").click(function(){
			jQuery("#pay_new_card").hide();
		});
	});
	</script>
	<?php
	$html .='<!-- Modal -->
	<style>
	.payment-method-options{
		display:none !important;
	}
	</style>
	<div class="pk_loader" style="display:none;">
		<img src="'.plugins_url('/images/loderr.gif', __FILE__).'" style="display:inline"/>
	</div>
	<div class="modal fade wz_show_pay_form wz_show_pay_form_'.$id.'" id="wz_show_pay_form_'.$id.'" role="dialog">
		<div class="modal-dialog">
    
		  <!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" style="float:right;">&times;</button>
				  <h4 class="modal-title custom_style_text" style="color:'.$topcolor.' !important;margin-left:15px;text-align:justify;">';
				  $html .=(empty($total->form_top_text))?
					"You're One Step Away from World-Class Training.<img class='emoji' draggable='false' alt='ðŸ”’' src='https://s.w.org/images/core/emoji/2.2.1/svg/1f512.svg'> Spread the cost with PayPal or Credit Card:":
				  $total->form_top_text;
				  
				  $html .='</h4>
				</div>
				
				<form id="fullcourse-'.$id.'" class="payment-form-'.$id.'" method="post">
				<div class="modal-body">';
				$html .=($total->form_location == "top")?
					'<div class="wz_user_detail">
						<div class="one_half">
						<input name="firstname" id="firstname" placeholder="First Name" required="" type="text" value="'.$payName.'">
						</div>
						<div class="one_half et_column_last">
						<input name="lastname" id="lastname" placeholder="Last Name" required="" type="text" value="'.$paylastName.'"> </div>
						<div class="clear"></div>
						<div class="on_2"><input name="email" placeholder="Email" required="" type="email" value="'.$payEmail.'">
						</div>
					</div>':'';
				
				
				$html .='
						<div class="row">
						';
						//$html .='<div id="payment-form-'.$id.'"> </div>';
						$html .=($total->form_location == "center")?
						'<div class="wz_user_detail" style="margin-top:25px;">
							<div class="one_half">	
							<input name="firstname" placeholder="First Name" id="firstname" required="" type="text" value="'.$payName.'">
							</div>
							<div class="one_half et_column_last"> 
							<input name="lastname" placeholder="Last Name" required="" type="text" value="'.$paylastName.'"> </div>
							<div class="clear"></div>
							<div class="on_2"><input name="email" placeholder="Email" required="" type="email" value="'.$payEmail.'">
							</div>
						</div>':'';
						$html .=($total->billing_address == "yes")?
						'<div class="wz_delivery_address">
						<h4>Delivery Address</h4>
						<p>                
						<label>Address:</label>
								<input type="text" name="address" id="address" placeholder="address" required="">
								<label>City: </label>
								<input type="text" name="city" id="city" placeholder="city">
								<label>State / County: </label>
								<input type="text" name="state" id="state" placeholder="state" required="">
								<label>Zip / Postcode:</label>
								<input type="text" name="zip" id="zip" placeholder="zip" required="">
								<label>Country:</label>
								<input type="text" name="countrys" id="country" placeholder="country" required="">
								<!--<input type="submit" class="btn" value="?? Pre-Order My Certificate">--><br>
							</p>
						</div>':"";
						$html .='</div>';
				
				
				$html .='
				<div id="container">
					<div class="add-payment-method-view">';
					
					///Check if customer has card
					$html .=(($wkcustomer_id)?
					'<div class="card_box"><div class="wkold_card">
						<p><input type="radio" name="payCrd" id="wkold_card" value="old">Use Card Ending <span style="color:green">'.$last4.'(Expiry on '.$exp_month.'/'.$exp_year.')</span></p>
					</div>
				  
					<div class="wknw_card">
						<p><input type="radio" id="wknw_card" name="payCrd" value="new">New Card</p>
					</div></div>
					<div class="form-container">
					<div class="add-payment-method-form-view" id="pay_new_card" style="display:none;">
						
				
						<div class="cell example example2">
						
						
							<div class="row">
								<div class="field">
									<div id="card-number" class="input empty"></div>
									<label for="card-number" data-tid="elements_examples.form.card_number_label">Card number</label>
									<div class="baseline"></div>
								</div>
							</div>
							
							<div class="row">
								<div class="field half-width">
									<div id="card-expiry" class="input empty"></div>
									<label for="card-expiry" data-tid="elements_examples.form.card_expiry_label">Expiration</label>
									<div class="baseline"></div>
								</div>
								<div class="field half-width">
									<div id="card-cvc" class="input empty"></div>
									<label for="card-cvc" data-tid="elements_examples.form.card_cvc_label">CVC</label>
									<div class="baseline"></div>
								</div>
							</div>
							<div class="error" role="alert" style="text-align:center;color:red;">
								
								<span class="message"></span>
							</div>
							
							</div>
							
						
						</div>
						</div>
							<div class="response_message" style="text-align:center;">
									
							</div>
						  </div>
						</div>':
				
					
						'<div class="add-payment-method-form-view">
						
				
						<div class="cell example example2">
						
						
							<div class="row">
								<div class="field">
									<div id="card-number" class="input empty"></div>
									<label for="card-number" data-tid="elements_examples.form.card_number_label">Card number</label>
									<div class="baseline"></div>
								</div>
							</div>
							
							<div class="row">
								<div class="field half-width">
									<div id="card-expiry" class="input empty"></div>
									<label for="card-expiry" data-tid="elements_examples.form.card_expiry_label">Expiration</label>
									<div class="baseline"></div>
								</div>
								<div class="field half-width">
									<div id="card-cvc" class="input empty"></div>
									<label for="card-cvc" data-tid="elements_examples.form.card_cvc_label">CVC</label>
									<div class="baseline"></div>
								</div>
							</div>
							<div class="error" role="alert" style="text-align:center;color:red;">
								
								<span class="message"></span>
							</div>
							
							</div>
							
						
						</div>
						
						
					</div>
						<div class="response_message" style="text-align:center;">
								
						</div>
					</div>
				</div>');
					
						$html .='
			<div class="stripe_btn">';
			$html .=(empty($total->button_label))?
				'<input class="btns lock_wh pk_sub_btn" value="Unlock My Course >>" type="submit" style="background-color:'.$btncolor.' !important;" data-tid="elements_examples.form.pay_button>':
				'<input class="btns pk_sub_btn" value="'.$total->button_label .'" id="pk_pay_btn" type="submit" style="background-color:'.$btncolor.' !important;" data-tid="elements_examples.form.pay_button>';
				$html .='<ul>
				<li><img src="'.plugins_url('/images/card.png', __FILE__).'"</li>

				</ul>

				<p class="custom_style_text" style="color:'.$bottomcolor.' !important;">';
				$html .=(empty($total->form_bottom_text))?
				'60-day money-back guarantee.<br> Lifetime access.':
				$total->form_bottom_text;
				$html .='</p>
			</div>	
					<input type="hidden" name="wk_desc" value="'.$desc.'">
					<input type="hidden" name="form_id" value="'.$id.'">
					<input type="hidden" name="country" value="" class="country">
					<input type="hidden" name="stripeToken" id="stripeToken" value="" />
					<input type="hidden" name="action" class="action" value="stripeCharge">
				</div>
				</form>
				
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
      
		</div>
	</div>';
	echo $html;
	
	/*********************
	Stripe Payment script
	*********************/
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	?>
	<script src="https://js.stripe.com/v3/"></script>
	<script src = "https://js.maxmind.com/js/apis/geoip2/v2.1/geoip2.js" type ="text/javascript"></script>
	<script type ="text/javascript">
	jQuery(document).ready(function() {
	try {
		var onSuccess = function(location) {
			jQuery(".payment-form-<?php echo $id; ?>").find(".country").val(location.country.iso_code);
			
			   
		};
		var onError = function(error) {
			jQuery(".payment-form-<?php echo $id; ?>").find(".country").val("usa");
		};
		 geoip2.country(onSuccess, onError);
		} catch (err) {
			
			jQuery(".payment-form-<?php echo $id; ?>").find(".country").val("usa");
			
		}
	});
	var stripe = Stripe("<?php echo $stripepk_Key; ?>");
	function registerElements(elements, exampleName) {
	
	var formClass = "." + exampleName;
	var example = document.querySelector(formClass);
	
	var form = document.getElementById("fullcourse-<?php echo $id; ?>");
	var error = form.querySelector('.error');
	var errorMessage = error.querySelector('.message');

	function enableInputs() {
		Array.prototype.forEach.call(
		  form.querySelectorAll(
			"input[type='text'], input[type='email']"
		  ),
		  function(input) {
			input.removeAttribute('disabled');
		  }
		);
	  }

	  function disableInputs() {
		Array.prototype.forEach.call(
		  form.querySelectorAll(
			"input[type='text'], input[type='email']"
		  ),
		  function(input) {
			input.setAttribute('disabled', 'true');
		  }
		);
	  }

	  // Listen for errors from each Element, and show error messages in the UI.
	  elements.forEach(function(element) {
		element.on('change', function(event) {
		  if (event.error) {
			error.classList.add('visible');
			errorMessage.innerText = event.error.message;
		  } else {
			error.classList.remove('visible');
			errorMessage.innerText = '';
		  }
		});
	  });

	  
	  form.addEventListener('submit', function(e) {
		e.preventDefault();

		// Show a loading screen...
		example.classList.add('submitting');

		// Disable all inputs.
		//disableInputs();

		// Gather additional customer data we may have collected in our form.
		var name = form.querySelector('#firstname');
		var lastname = form.querySelector('#lastname');
		var address = form.querySelector('#address');
		var city = form.querySelector('#city');
		var state = form.querySelector('#state');
		var zip = form.querySelector('#zip');
		var country = form.querySelector('#country');
		var additionalData = {
			name: name ? name.value : undefined,
			lastname: lastname ? lastname.value : undefined,
			address_line1: address ? address.value : undefined,
			address_city: city ? city.value : undefined,
			address_state: state ? state.value : undefined,
			address_zip: zip ? zip.value : undefined,
			address_country: country ? country.value : undefined
			// address_line1: address1 ? address1.value : undefined
		};
		if (jQuery('input[name="payCrd"]:checked').val() == 'old') {
			var ajaxurl = "<?php echo admin_url("admin-ajax.php" );?>";
				jQuery(".pk_loader").show();
				jQuery(".pk_sub_btn").prop("disabled", true);
				jQuery("#pk_pay_btn").attr("disabled","disabled");
				jQuery.ajax({
					url	: ajaxurl,
					type  : "POST",
					action:jQuery(".payment-form-<?php echo $id; ?>").find(".action").val(),
					data:jQuery(".payment-form-<?php echo $id; ?>").serialize(),
					// data:{fname:firstname},
					success: function(html){
					html = jQuery.parseJSON(html);
					
					//jQuery(".payment-form-<?php echo $id; ?>").find(".server-error-message").append(html);
					if(html.status == true){
						jQuery(".pk_loader").hide();
						jQuery("#pk_pay_btn").removeAttr("disabled");
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").html(html.message);
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").css("color","green");
						window.location = html.redirect;
						
					}else{
						jQuery(".pk_loader").hide();
						jQuery("#pk_pay_btn").removeAttr("disabled");
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").html(html.message);
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").css("color","red");
					}
					}
				});
		}else{
		
		stripe.createToken(elements[0], additionalData).then(function(result) {
		
		example.classList.remove('submitting');

		if (result.token) {
			
			// If we received a token, show the token ID.
			//example.querySelector('.token').innerText = result.token.id;
			document.getElementById("stripeToken").value = result.token.id;
			example.classList.add('submitted');
			
			var form_id = "<?php echo $id; ?>";
				var firstname = jQuery("#firstname").val();
				var ajaxurl = "<?php echo admin_url("admin-ajax.php" );?>";
				jQuery(".pk_loader").show();
				jQuery("#pk_pay_btn").attr("disabled","disabled");
				jQuery(".__PrivateStripeElement-input").prop("disabled", true);
				jQuery(".InputElement").prop("disabled", true);
				//debugger;
				jQuery.ajax({
					url	: ajaxurl,
					type  : "POST",
					action:jQuery(".payment-form-<?php echo $id; ?>").find(".action").val(),
					data:jQuery(".payment-form-<?php echo $id; ?>").serialize(),
					// data:{fname:firstname},
					success: function(html){
					html = jQuery.parseJSON(html);
					//jQuery(".payment-form-<?php echo $id; ?>").find(".server-error-message").append(html);
					if(html.status == true){
						
						jQuery(".pk_loader").hide();
						jQuery("#pk_pay_btn").removeAttr("disabled");
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").html(html.message);
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").css("color","green");
						window.location = html.redirect;
					}else{
						jQuery(".pk_loader").hide();
						jQuery("#pk_pay_btn").removeAttr("disabled");
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").html(html.message);
						jQuery(".payment-form-<?php echo $id; ?>").find(".response_message").css("color","red");
					}
					}
				});
				
			var form_id = "<?php echo $id; ?>";
		}else{
			// Otherwise, un-disable inputs.
			enableInputs();
		}
		
		});
		
		}
	  });

	
	}

jQuery(document).ready(function() {	

	// Create an instance of Elements
	var elements = stripe.elements({
    fonts: [
      {
        cssSrc: 'https://fonts.googleapis.com/css?family=Source+Code+Pro',
      },
    ],
    
    locale: window.__exampleLocale
  });

  // Floating labels
  var inputs = document.querySelectorAll('.cell.example.example2 .input');
  Array.prototype.forEach.call(inputs, function(input) {
    input.addEventListener('focus', function() {
      input.classList.add('focused');
    });
    input.addEventListener('blur', function() {
      input.classList.remove('focused');
    });
    input.addEventListener('keyup', function() {
      if (input.value.length === 0) {
        input.classList.add('empty');
      } else {
        input.classList.remove('empty');
      }
    });
  });

	var elementStyles = {
    base: {
      color: '#32325D',
      fontWeight: 500,
      fontFamily: 'Source Code Pro, Consolas, Menlo, monospace',
      fontSize: '16px',
      fontSmoothing: 'antialiased',

      '::placeholder': {
        color: '#CFD7DF',
      },
     ':-webkit-autofill': {
        color: '#e39f48',
      },
    },
    invalid: {
      color: '#E25950',

      '::placeholder': {
        color: '#FFCCA5',
      },
    },
	};

	  var elementClasses = {
		focus: 'focused',
		empty: 'empty',
		invalid: 'invalid',
	  };

	  var cardNumber = elements.create('cardNumber', {
		style: elementStyles,
		classes: elementClasses,
	  });
	  cardNumber.mount('#card-number');

	  var cardExpiry = elements.create('cardExpiry', {
		style: elementStyles,
		classes: elementClasses,
	  });
	  cardExpiry.mount('#card-expiry');

	  var cardCvc = elements.create('cardCvc', {
		style: elementStyles,
		classes: elementClasses,
	  });
	  cardCvc.mount('#card-cvc');

	  registerElements([cardNumber, cardExpiry, cardCvc], 'example2');
});
	</script>
	<?php
	
	$output_string = ob_get_clean();
	return $output_string; 
}



/**********************************************************
ajax function to delete the payment card
**********************************************************/
add_action( 'wp_ajax_delete_card', 'delete_card' );
add_action( 'wp_ajax_nopriv_delete_card', 'delete_card' );

function delete_card(){
	// echo '<pre>'; print_r($_POST); die('okk');
	require_once 'vendor/init.php';
	// require_once 'vendor/productionconfig.php';
	
	// Get user ID
	$user_ID = get_current_user_id();
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	$creditCardToken = $_POST['card'];
	
	if($wkcustomer_id){
		
		try{
			// die('ok');
			\Stripe\Stripe::setApiKey($stripesk_Key);
			$customer = \Stripe\Customer::retrieve($wkcustomer_id);
			$customer->sources->retrieve($creditCardToken)->delete();
			echo json_encode(array("status"=>true,"message"=>"Card Delete Successfully !"));
		
		}catch(Exception $e) {
			echo json_encode(array("status"=>false,"message"=>"Something went wrong."));
		}
		
	}
	die;
}


/**********************************************************
ajax function to delete the payment subscription
**********************************************************/
add_action( 'wp_ajax_del_stripe_subscription', 'del_stripe_subscription' );
add_action( 'wp_ajax_nopriv_del_stripe_subscription', 'del_stripe_subscription' );

function del_stripe_subscription(){
	
	require_once 'vendor/init.php';
	
	global $wpdb;
	// Get user ID
	$user_ID = get_current_user_id();
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	$cancel_subscription = $_POST['subscription'];
	$table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	$total = $wpdb->get_row( "SELECT * FROM $table_name where user_id = $user_ID AND subscription_id = '$cancel_subscription'");
	$total->level_id;
	$id = $total->id;
	if($wkcustomer_id){
		$userInfo = wp_get_current_user();
		$userEmail = $userInfo->user_email;
		$userName = $userInfo->user_nicename;
		try{
			
			\Stripe\Stripe::setApiKey($stripesk_Key);
			$sub = \Stripe\Subscription::retrieve($cancel_subscription);
			$cancel_date = date("Y-m-d",$sub->current_period_end);
			$sub->cancel();
			// $user_levels = array_keys(WLMAPI::GetUserLevels($user_ID));
			// $result = WLMAPI:: DeleteUserLevels($user_ID,$user_levels,true);
			// $result = WLMAPI:: DeleteUserLevels($user_ID,array($total->level_id),true);
			
			// $result = WLMAPI:: CancelLevel($user_ID,$total->level_id);
			$wpdb->query("UPDATE $table_name SET status='cancelled',subs_end_date='$cancel_date' WHERE id = $id");
			user_cancel_subs_notifications($userEmail,$userName);
			admin_cancel_subs_notifications($userEmail,$userName);
			echo json_encode(array("status"=>true,"message"=>"Subscription Cancel Successfully !"));
		
		}catch(Exception $e) {
			echo json_encode(array("status"=>false,"message"=>"Something went wrong."));
		}
		
	}
	die;
}


/*****************************************************
ajax function for upgrade the monthly plan to annually
*****************************************************/
add_action( 'wp_ajax_stripeupgradePlan', 'stripeupgradePlan' );
add_action( 'wp_ajax_nopriv_stripeupgradePlan', 'stripeupgradePlan' );

function stripeupgradePlan(){
	
	require_once 'vendor/init.php';
	
	global $wpdb;
	
	$user_ID = get_current_user_id(); // Get current user ID
	
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	$plan = $_POST['upgrade_planid'];
	$cancel_subscription = $_POST['subscription'];
	$html = '';
	
	
	
	//Define table name
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
	$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	
	// Get level ID
	$total = $wpdb->get_row( "SELECT * FROM $histry_table_name where user_id = $user_ID AND subscription_id = '$cancel_subscription'");
	
	$label_ID = $total->level_id;
	$btn_id = $total->btn_id;
	$country = $total->country;
	$subsscription = $total->subscription_id;
	$id = $total->id;
	
	// get plan name and country name using button ID.
	$joins = $wpdb->get_row("SELECT * FROM $histry_table_name INNER JOIN $table_name ON $histry_table_name.btn_id = $table_name.id where subscription_id = '$subsscription'");
	
	$update_plan = 'update_plan_'.$joins->country;
	$degrade_plan = 'degrade_plan_'.$joins->country;
	if($wkcustomer_id){
		
		$userInfo = wp_get_current_user();
		$userEmail = $userInfo->user_email;
		$userName = $userInfo->user_nicename;
		
		try{
			
			\Stripe\Stripe::setApiKey($stripesk_Key);
			$sub = \Stripe\Subscription::retrieve($cancel_subscription);
			$sub->cancel(); // Cancel subscription
			$wpdb->query("UPDATE $histry_table_name SET status='cancelled' WHERE id = $id");
			
			$subscription = \Stripe\Subscription::create(array(
				  "customer" => $sub['customer'],
				  "items" => array(
					array(
					  "plan" => $plan,
					),
				  )
			));
			
			if($subscription->id){
				
				//Insert new plan with subscription
				$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id,country,btn_id) VALUES ('$user_ID', '', '$subscription->id', '$label_ID', '$country','$btn_id')");
			
				$html .= '<tr id="'.$subscription->id .'">
							<td>'.$subscription->id .'</td>
							<td>'.$subscription->plan->id .'</td>';
			
				$okdate = date("Y-m-d",$subscription->current_period_start);
				$okdates = date("Y-m-d",$subscription->current_period_end);
				$StartDate = new DateTime($okdate);
				$EndDate = new DateTime($okdates);
				$newdate = $StartDate->diff($EndDate);
				
				if($newdate->days < '32'){
					if($joins->$update_plan){ 
					$html .='<td><a href="javascript:void(0)" data-upgrade="'.$subscription->id .'" data-pid="'.$joins->$update_plan .'" class="pk_upgrade_plan">Upgrade to Annually</a></td>';
					}
				}else{
					if($joins->$degrade_plan){
					$html .='<td><a href="javascript:void(0)" data-degrade="'.$subscription->id .'" data-deid="'.$joins->$degrade_plan .'" class="pk_degrade_plan">Downgrade to Monthly</a></td>';
					}
				}
				$html .='</tr>';
				
				echo json_encode(array("status"=>true,"message"=>"Subscription Upgrade Successfully !","display"=>$html));
				
			}else{
				
				echo json_encode(array("status"=>false,"message"=>"Something went wrong1."));
				
			}
			
		}catch(Exception $e) {
			echo json_encode(array("status"=>false,"message"=>"Something went wrong."));
		}
	}
	
	die;
}

/******************************************************
ajax function for degrade the annually plan to monthly
******************************************************/
add_action( 'wp_ajax_stripedegradePlan', 'stripedegradePlan' );
add_action( 'wp_ajax_nopriv_stripedegradePlan', 'stripedegradePlan' );

function stripedegradePlan(){
	
	require_once 'vendor/init.php';
	
	global $wpdb;
	
	$user_ID = get_current_user_id(); // Get current user ID
	
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	$plan = $_POST['degrade_planid'];
	$cancel_subscription = $_POST['subscription'];
	$html = '';
	
	
	//Define table name
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
	$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	
	// Get level ID
	$total = $wpdb->get_row( "SELECT * FROM $histry_table_name where user_id = $user_ID AND subscription_id = '$cancel_subscription'");
	
	$label_ID = $total->level_id;
	$btn_id = $total->btn_id;
	$country = $total->country;
	$subsscription = $total->subscription_id;
	$id = $total->id;
	
	
	if(isset($wkcustomer_id)){
		
		$userInfo = wp_get_current_user();
		$userEmail = $userInfo->user_email;
		$userName = $userInfo->user_nicename;
		
		try{
			
			\Stripe\Stripe::setApiKey($stripesk_Key);
			$sub = \Stripe\Subscription::retrieve($cancel_subscription);
			$sub->cancel(); // Cancel subscription
			
			$wpdb->query("UPDATE $histry_table_name SET status='cancelled' WHERE id = $id");
			
			$subscription = \Stripe\Subscription::create(array(
				  "customer" => $sub['customer'],
				  "items" => array(
					array(
					  "plan" => $plan,
					),
				  )
			));
			
			if($subscription->id){
				
				$subscription_id = $subscription->id;
				
				//Insert new plan with subscription
				$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id,country,btn_id) VALUES ('$user_ID', '', '$subscription_id', '$label_ID', '$country','$btn_id')");
			
				$html .= '<tr id="'.$subscription->id .'">
							<td>'.$subscription->id .'</td>
							<td>'.$subscription->plan->id .'</td>';
				$joins = $wpdb->get_row("SELECT * FROM $histry_table_name INNER JOIN $table_name ON $histry_table_name.btn_id = $table_name.id where subscription_id = '$subsscription'");
				// echo '<pre>'; print_r($joins);
				$update_plan = 'update_plan_'.$joins->country;
				$degrade_plan = 'degrade_plan_'.$joins->country;
			
				$okdate = date("Y-m-d",$subscription->current_period_start);
				$okdates = date("Y-m-d",$subscription->current_period_end);
				$StartDate = new DateTime($okdate);
				$EndDate = new DateTime($okdates);
				$newdate = $StartDate->diff($EndDate);
				
				if($newdate->days < '32'){
					if($joins->$update_plan){ 
					$html .='<td><a href="javascript:void(0)" data-upgrade="'.$subscription->id .'" data-pid="'.$joins->$update_plan .'" class="pk_upgrade_plan">Upgrade to Annually</a></td>';
					}
				}else{
					if($joins->$degrade_plan){
					$html .='<td><a href="javascript:void(0)" data-degrade="'.$subscription->id .'" data-deid="'.$joins->$degrade_plan .'" class="pk_degrade_plan">Downgrade to Monthly</a></td>';
					}
				}
				$html .='</tr>';
				
				echo json_encode(array("status"=>true,"message"=>"Subscription Upgrade Successfully !","display"=>$html));
				
			}else{
				
				echo json_encode(array("status"=>false,"message"=>"Something went wrong."));
				
			}
			
		}catch(Exception $e) {
			echo json_encode(array("status"=>false,"message"=>"Something went wrong."));
		}
	}
	die;
}


/*************************************
user email for cancel subscription
*************************************/
function user_cancel_subs_notifications($userEmail,$userName){

	require_once("vendor/phpmailer/PHPMailerAutoload.php");
	$mail = new PHPMailer(true);
	$mail->SMTPAuth   = true;
	$mail->Mailer = "smtp";
	$mail->Host= get_option('stripe_mail_host_name');
	$mail->Port = get_option('stripe_mail_port');
	$mail->Username = get_option('stripe_mail_username');
	$mail->Password = get_option('stripe_mail_password');
	$form_email = get_option('stripe_email_from');
	$custom_email_txt = str_replace('https://', '', get_option( 'custom_email_url' ));
	$mail->SetFrom(($form_email) ? $form_email : 'noreply@writestorybooksforchildren.com');
	$mail->Subject = 'Cancelled Subscription';
	$message .= 'Hello, '.$userName.'<br><br><br>';
	$message .= 'We are sorry to see you go <br><br><br>';
	$message .= 'Your subscription has been cancelled and your card will not be charged again.<br><br><br>';
	$message .= 'If this cancellation was an error, please reply to this email so that we can reactivate your account.<br><br><br>';
	$message .= 'Thank you.<br><br><br>';
	$message .= (get_option('custom_email_url')) ?  '<a href="'.get_option('custom_email_url').'">'.$custom_email_txt.'</a>':'<a href="https://www.writestorybooksforchildren.com/">www.writestorybooksforchildren.com</a>';
	$mail->MsgHTML($message);
	$mail->AddAddress($userEmail);
	if(!$mail->Send())
		return false;
	else
		return true;
}

/**************************************
admin email for cancel subscription
**************************************/
function admin_cancel_subs_notifications($userEmail,$userName){
	
	require_once("vendor/phpmailer/PHPMailerAutoload.php");
	$mail2 = new PHPMailer(true);
	$mail2->IsSMTP(true);
	$mail2->SMTPAuth   = true;
	$mail2->Mailer = "smtp";
	$mail2->Host= get_option('stripe_mail_host_name');
	$mail2->Port = get_option('stripe_mail_port');
	$mail2->Username = get_option('stripe_mail_username');
	$admin_email = get_option('stripe_admin_to_email');
	$sender_email = get_option('stripe_admin_email_from');
	$from = ($sender_email) ? $sender_email : "mail@velocitelearning.com";
	$to = ($admin_email) ? $admin_email : 'admin@digitalsea.co.uk';
	// $to = ($admin_email) ? $admin_email : 'testing.whizkraft2@gmail.com';
	$mail2->Password = get_option('stripe_mail_password');
	$mail2->SetFrom($from, 'noreply');
	$mail2->AddReplyTo($from,'mail@velocitelearning.com');
	$subject = 'Cancelled Subscription';
	$mail2->Subject = $subject;
	$body = $userName.'('.$userEmail.') has been cancelled there Subscription !';
	$mail2->MsgHTML($body);
	$address = $to;
	$mail2->AddAddress($address);
	if(!$mail2->Send())
		return false;
	else
		return true;
}


/*****************************************
Add new card script
*****************************************/
add_action( 'init', 'add_sripecard' );
function add_sripecard(){

if(isset($_POST['action']) && $_POST['action'] == 'add_sripecard'){
	
	require_once 'vendor/init.php';
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	// Get user ID
	$user_ID = get_current_user_id();
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	\Stripe\Stripe::setApiKey($stripesk_Key);
	if($wkcustomer_id){
		try {
			$customer = \Stripe\Customer::retrieve($wkcustomer_id);
			$card = $customer->sources->create(array("source" => $_POST['stripeToken']));
			$customer->default_source = $card->id;
			$customer->save();
			
			header('Location: #'.$_POST['tab-action']);
		exit;
		}catch(Exception $e) {
			
			echo 'Message: ' .$e->getMessage();

		}
		
	}
	
}	
}


/*****************************************
Stripe Download PDF Script
*****************************************/
add_action( 'init', 'pdf_download' );
function pdf_download(){

if(isset($_POST['pdf_action']) && $_POST['pdf_action'] == 'pdf_download'){
	
	require 'pdf-invoicr-master/phpinvoice.php';
	$invoice = new phpinvoice('A4',$_POST['pdf_currency']);
	
	
	$siteurl = get_site_url();
	$_url = wp_parse_url($siteurl);
	
	/* Header Settings */
	
	$invoice->setLogo(wp_get_attachment_url(get_option('custom_admin_logo_id')));
	$invoice->setColor("#AA3939");
	$invoice->setDate(date('M dS ,Y',time()));
	$invoice->setFrom(array("Purchaser Name",$_POST['pdf_fname'],$_POST['pdf_lname']));
	/* Adding Items in table */
	$invoice->addItem($_POST['pdf_product'],$_POST['pdf_txnID'],1,false,$_POST['pdf_amount'],false,$_POST['pdf_amount']);
	/* Add totals */
	$invoice->addTotal("Total",$_POST['pdf_amount'],true);
	$invoice->setFooternote($_url['host']);
	/* Render */
	$invoice->render('invoice.pdf','D'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */
		
	
}

}

/***********************************************************
Stripe View Transaction and Cancel Subscription Shortcode
************************************************************/

function stripe_view_cancel_subscription(){
	// die('ok');
	ob_start();
	global $wpdb;
	require_once 'vendor/init.php';
	// include braintree configuration
	$api_website = get_option('wlm_website');
	$api_ky = get_option('stripe_wlm_api');
	// require_once 'vendor/productionconfig.php';
	require_once 'vendor/wlmapiclass.php';
	
	$slug = basename(get_permalink());
	$pkredirect_url = get_site_url().'/'.$slug;

	// Get user ID
	$user_ID = get_current_user_id();
	
	$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	$table_name = $wpdb->prefix . 'stripe_payment_tbl';
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wkcustomer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
		$stripepk_Key = get_option('sandbox_publish_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
		$stripepk_Key = get_option('live_publish_key');
	}
	
	if($wkcustomer_id){
	$html .= '
	<div class="pk_cardloader" style="display:none;">
		<img src="'.plugins_url('/images/loderr.gif', __FILE__).'" style="display:inline"/>
	</div>
	<div class="pk_subsloader" style="display:none;">
		<img src="'.plugins_url('/images/loderr.gif', __FILE__).'" style="display:inline"/>
	</div>
	<div class="container">
    <div class="row">
		<div class="col-md-6">
			<h3></h3>
			<!-- tabs -->
			<div class="wk_tabbable">
				<ul class="nav nav-tabs wk_stripe_cstm_tab">
					<li class="active"><a href="javascript:void(0);" data-value="#viewPayments">View Transaction</a></li>
					<li><a href="javascript:void(0);" data-value="#changeCards">Change Card</a></li>
					<li><a href="javascript:void(0);" data-value="#tweee">Cancel Subscription</a></li>
					<li><a href="javascript:void(0);" data-value="#tweee1">Change Subscription</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane fade in active" id="viewPayments">
						<table class="table">
							<thead>
							<tr>
							  <th>Charge ID</th>
							  <th>Customer Name</th>
							  <th>Amount</th>
							  <th>Product Name</th>
							  <th>Download Invoice</th>
							</tr>
							</thead>
							<tbody>';
							\Stripe\Stripe::setApiKey($stripesk_Key);
							$list_all = Stripe\Charge::all(array(
							"customer" => $wkcustomer_id
							));
							
							// echo '<pre>'; print_r($list_all);die;
							$member_levels = WLMAPI::GetLevels();
							foreach($list_all->data as $_data){
								// echo '<pre>'; print_r($_data);die;
								$idd = $_data['id'];
								if($_data['currency'] == 'usd'){
									$currency = '&#36;';
								}elseif($_data['currency'] == 'aud'){
									$currency = '&#36;';
								}elseif($_data['currency'] == 'gbp'){
									$currency = '&#163;';
								}elseif($_data['currency'] == 'eur'){
									$currency = '&#8364; ';
								}
								if($_data['invoice']){
									$Invoicess = \Stripe\Invoice::retrieve($_data['invoice']);
									$subsss = $Invoicess->lines['data'];
									$card_firstname = $subsss[0]->metadata['First Name'];
									$card_lastname = $subsss[0]->metadata['Last Name'];
									$subssID = $subsss[0]->id;
									$txn_level = $wpdb->get_row( "SELECT * FROM $histry_table_name where user_id = $user_ID AND subscription_id LIKE '".$subssID."'");
									// echo '<pre>'; print_r($txn_level); die;
									
								}else{
									$card_firstname = $_data['metadata']['First Name'];
									$card_lastname = $_data['metadata']['Last Name'];
									$txn_level  = $wpdb->get_row( "SELECT * FROM $histry_table_name where user_id = $user_ID AND charge_id LIKE '".$idd."'");
									
								}
								// foreach($_data['source'] as $_list_all){
								$amount = $_data['amount'] / 100;
								$html .= '<tr>
										<td>'.$_data['id'] .'</td>
										<td>'.$card_firstname.'&nbsp'.$card_lastname.'</td>
										<td>'.$currency.'&nbsp'.$amount.'</td>';
									$html .='<td>'.$member_levels[$txn_level->level_id]['name'].'</td>';
									$html .='<td>
											<form action="" method="post">
												<input type="hidden" name="pdf_product" value="'.$member_levels[$txn_level->level_id]['name'].'">
												<input type="hidden" name="pdf_txnID" value="'.$_data['id'].'">
												<input type="hidden" name="pdf_amount" value="'.$amount.'">
												<input type="hidden" name="pdf_fname" value="'.$card_firstname.'">
												<input type="hidden" name="pdf_lname" value="'.$card_lastname.'">
												<input type="hidden" name="pdf_currency" value="'.$currency.'">
												<input type="hidden" name="pdf_action" value="pdf_download">
												<input type="submit" name="pdf_dwn" value="Download PDF">
											</form>
											</td>';
										
									$html .='</tr>';
								// }
							}
							$html .= '</tbody>
							</table>
					</div>
					
					
					<!------- change card ----------------->
					<div class="tab-pane fade pk-delstripeCard" id="changeCards">
					
					<table class="table">
						<thead>
						<tr>
							<th>Old Card</th>
							<th>Expiry Date</th>
							<th>Delete card</th>
						</tr>
						</thead>
						<tbody id="table-card">';
						\Stripe\Stripe::setApiKey($stripesk_Key);
						$all_cards = \Stripe\Customer::Retrieve($wkcustomer_id);
						foreach($all_cards->sources->data as $_all_cards){
							
							// echo '<pre>'; print_r($_all_cards);
							if(isset($_all_cards)){
							$statusshow = true;	
							$last_four = '************'.$_all_cards['last4'];
							$exp_month = $_all_cards['exp_month'];
							$exp_year = $_all_cards['exp_year'];
							$html .= '<tr id="'.$_all_cards['id'].'">
										<td>'.$last_four.'</td>
										<td>'.$exp_month.' / '.$exp_year.'</td>';
									$html .='<td><a href="javascript:void(0)" data-card="'.$_all_cards['id'].'" class="pk_del_card">Delete Card</a></td>';
										
									$html .='</tr>';
							}
						}
						if($statusshow != true){
							$html .='<td colspan="4">No Card found.</td>';
						}
						$html .='</tbody>
						<script>
						jQuery(document).ready(function() {
							
							jQuery(".wk_stripe_cstm_tab a").click(function(){
								jQuery(".wk_stripe_cstm_tab li").removeClass("active");
								jQuery(".tab-content").find(".tab-pane").removeClass("active").removeClass("in");
								
								jQuery(this).closest("li").addClass("active");
								jQuery(".tab-content").find(jQuery(this).data("value")).addClass("active").addClass("in");
								
							});
							
							var hash = window.location.hash;
							jQuery(\'.wk_stripe_cstm_tab a[data-value="\' + hash + \'"]\').trigger(\'click\');
							
							jQuery(".pk_del_card").click(function(){
								var ajaxurl = "'.admin_url("admin-ajax.php" ).'";
								var cards = jQuery(this).data("card");
								jQuery(".pk_cardloader").show();
								jQuery.ajax({
									url	: ajaxurl,
									type  : "POST",
									data: {card: cards,action:"delete_card"},
									success: function(html){
										html = jQuery.parseJSON(html);
										if(html.status == true){
											jQuery(".pk_cardloader").hide();
											jQuery(".pk-delstripeCard").find(".pk_card_msg").html(html.message);
											jQuery(".pk-delstripeCard").find(".pk_card_msg").css("color","green");
										}else{
											jQuery(".pk_cardloader").hide();
											jQuery(".pk-delstripeCard").find(".pk_card_msg").html(html.message);
											jQuery(".pk-delstripeCard").find(".pk_card_msg").css("color","red");
										}
										setTimeout(function() {
											jQuery("#"+cards).remove();
											if(jQuery("#table-card").children("tr").length == 0){
												jQuery("#table-card").append(\'<tr><td colspan="4">You have delete your all cards.</td></tr>\');
											}
											jQuery(".pk_card_msg").hide(); 
										}, 2000);
									}
								});
							});
							
						});
						</script>
					
					</table>
					<form id="" class="" method="post">
						<div class="container" id="addNew_Card">
						</div>
					</form>
					
					<a href="javascript:void(0)" class="pk-add-cards" data-toggle="modal" data-target="#addnewCard">Add New Card</a>
					
					<form action="" method="post" id="stripeAddcard" style="display:none;">
						<script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
							  data-key="'.$stripepk_Key.'"
							  data-description="Add New Card"
							  data-locale="auto"
							  data-panel-label="ADD">
						</script>
						<input type="hidden" name="action" value="add_sripecard">
						<input type="hidden" name="tab-action" value="changeCards">
					</form>
					
					<div class="pk_card_msg" style="text-align:center;margin-top:10px;"></div>
								
					</div>';
					
					// Add new card script
					
					$html .='<script src="https://js.braintreegateway.com/js/braintree-2.23.0.min.js"></script>
							<script>
							jQuery(document).ready(function() {
								jQuery(".pk-add-cards").click(function(){
									jQuery(".stripe-button-el").trigger("click");
								});
							});
							</script>';
					
					// cancel subscription
					$html .='<div class="tab-pane fade pk_subss_del" id="tweee">
					<div class="pk_subsloader" style="display:none;">
						<img src="'.plugins_url('/images/loderr.gif', __FILE__).'" style="display:inline"/>
					</div>
					<table class="table">
						<thead>
						<tr>
						  <th>Subscription ID</th>
						  <th>Plan Name</th>
						  <th>Subscription</th>
						  <th></th>
						</tr>
						</thead>
						<tbody id="table-subscription">';
						
						\Stripe\Stripe::setApiKey($stripesk_Key);
						$subscriptions = \Stripe\Subscription::all(array('customer' => $wkcustomer_id, "status" => "active"));
						// echo '<pre>'; print_r($subscriptions['data']);
						foreach($subscriptions['data'] as $_subscriptions) {
							// echo '<pre>'; print_r($_subscriptions);
							$showStatuss = true;
							$html .= '<tr id="'.$_subscriptions['id'].'">
										<td>'.$_subscriptions['id'].'</td>
										<td>'.$_subscriptions->plan->id .'</td>';
									$html .='<td><a href="javascript:void(0)" data-subcription="'.$_subscriptions['id'].'" class="pk_del_subscription">Cancel Subscription</a></td>';
									
														
									$html .='</tr>';
							
						}
						// die;
						if($showStatuss != true){
							$html .='<td colspan="4">No Sunscription found.</td>';
						}
						$html .='</tbody>
						
						
						<script>
						jQuery(document).ready(function() {
							jQuery(".pk_del_subscription").click(function(){
								var ajaxurl = "'.admin_url("admin-ajax.php" ).'";
								var subcriptions = jQuery(this).data("subcription");
								jQuery(".pk_subsloader").show();
								jQuery.ajax({
									url	: ajaxurl,
									type  : "POST",
									data: {subscription: subcriptions,action:"del_stripe_subscription"},
									success: function(html){
										html = jQuery.parseJSON(html);
										if(html.status == true){
											jQuery(".pk_subsloader").hide();
											jQuery(".pk_subss_del").find(".pk_subs_msg").html(html.message);
											jQuery(".pk_subss_del").find(".pk_subs_msg").css("color","green");
										}else{
											jQuery(".pk_subsloader").hide();
											jQuery(".pk_subss_del").find(".pk_subs_msg").html(html.message);
											jQuery(".pk_subss_del").find(".pk_subs_msg").css("color","red");
										}
										setTimeout(function() {
											jQuery("#"+subcriptions).remove();
											if(jQuery("#table-subscription").children("tr").length == 0){
												jQuery("#table-subscription").append(\'<tr><td colspan="4">You have cancelled your all subscriptions.</td></tr>\');
											}
											jQuery(".pk_subs_msg").hide(); 
										}, 2000);
									}
								});
							});
							
						});
						
						</script>
						
					</table>
					<div class="pk_subs_msg" style="text-align:center;margin-top:10px;"></div>
					</div>';
					
					$html .='<div class="tab-pane fade pk_can_subss_del" id="tweee1">
					<div class="pk_can_subsloader" style="display:none;">
						<img src="'.plugins_url('/images/loderr.gif', __FILE__).'" style="display:inline"/>
					</div>
					<table class="table">
						<thead>
						<tr>
						  <th>Subscription ID</th>
						  <th>Plan Name</th>
						  <th>Action</th>
						</tr>
						</thead>
						<tbody id="table-subscription">';
						
						\Stripe\Stripe::setApiKey($stripesk_Key);
						$subscriptions = \Stripe\Subscription::all(array('customer' => $wkcustomer_id, "status" => "active"));
						// echo '<pre>'; print_r($subscriptions['data']);
						foreach($subscriptions['data'] as $_subscriptions) {
							// echo '<pre>'; print_r($_subscriptions);
							$showStatuss = true;
							$suuuub_id = $_subscriptions['id'];
							$html .= '<tr id="'.$_subscriptions['id'].'">
										<td>'.$_subscriptions['id'].'</td>
										<td>'.$_subscriptions->plan->id .'</td>';
										
									$joins = $wpdb->get_row("SELECT * FROM $histry_table_name INNER JOIN $table_name ON $histry_table_name.btn_id = $table_name.id where subscription_id = '$suuuub_id'");
									// echo '<pre>'; print_r($joins);
									$update_plan = 'update_plan_'.$joins->country;
									$degrade_plan = 'degrade_plan_'.$joins->country;
									
								
									$okdate = date("Y-m-d",$_subscriptions->current_period_start);
									$okdates = date("Y-m-d",$_subscriptions->current_period_end);
									$StartDate = new DateTime($okdate);
									$EndDate = new DateTime($okdates);
									$newdate = $StartDate->diff($EndDate);
									
									if($newdate->days < '32'){
										if($joins->$update_plan){
										$html .='<td><a href="javascript:void(0)" data-upgrade="'.$_subscriptions['id'].'" data-pid="'.$joins->$update_plan .'" class="pk_upgrade_plan">Upgrade to Annually</a></td>';
										}
									}else{
										if($joins->$degrade_plan){
										$html .='<td><a href="javascript:void(0)" data-degrade="'.$_subscriptions['id'].'" data-deid="'.$joins->$degrade_plan .'" class="pk_degrade_plan">Downgrade to Monthly</a></td>';
										}
									}
														
									$html .='</tr>';
							
						}
						
						$html .='</tbody>
						
						
						<script>
						
						jQuery(".pk_upgrade_plan").click(function(){
							
							var ajaxurl = "'.admin_url("admin-ajax.php" ).'";
							var upgrade = jQuery(this).data("upgrade");
							var pk_plan = jQuery(this).data("pid");
							var dis_html = jQuery(this).parent("td").parent("tr");
					
							jQuery(".pk_can_subsloader").show();
							jQuery.ajax({
									url	: ajaxurl,
									type  : "POST",
									data: {subscription: upgrade,upgrade_planid: pk_plan,action:"stripeupgradePlan"},
									success: function(html){
										//debugger;
										html = jQuery.parseJSON(html);
										if(html.status == true){
											jQuery(".pk_can_subsloader").hide();
											dis_html.replaceWith(html.display);
											// dis_html.remove();
											// dis_html.append(html.display);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").html(html.message);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").css("color","green");
										}else{
											jQuery(".pk_can_subsloader").hide();
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").html(html.message);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").css("color","red");
										}
									}
							});
							
						});
						
						jQuery(".pk_degrade_plan").click(function(){
							
							var ajaxurl = "'.admin_url("admin-ajax.php" ).'";
							var degrade = jQuery(this).data("degrade");
							var pk_plan = jQuery(this).data("deid");
							var dis_html = jQuery(this).parent("td").parent("tr");
							jQuery(".pk_can_subsloader").show();
							jQuery.ajax({
									url	: ajaxurl,
									type  : "POST",
									data: {subscription: degrade,degrade_planid: pk_plan,action:"stripedegradePlan"},
									success: function(html){
										//debugger;
										html = jQuery.parseJSON(html);
										if(html.status == true){
											jQuery(".pk_can_subsloader").hide();
											// dis_html.remove();
											dis_html.replaceWith(html.display);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").html(html.message);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").css("color","green");
										}else{
											jQuery(".pk_can_subsloader").hide();
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").html(html.message);
											jQuery(".pk_can_subss_del").find(".pk_subs_msgs").css("color","red");
										}
									}
							});
							
						});
						
						
						</script>
						
					</table>
					<div class="pk_subs_msgs" style="text-align:center;margin-top:10px;"></div>
					</div>
					
					
				</div>
			</div>
			<!-- /tabs -->
		</div>
	</div>
	</div>';
	}
	echo  $html;
	$output_string = ob_get_clean();
	return $output_string;
}

function wporg_shortcodes_stripe_view_cancel_subs_init()
{
    add_shortcode('view_cancel_stripe_subscription', 'stripe_view_cancel_subscription');
}
add_action('init', 'wporg_shortcodes_stripe_view_cancel_subs_init');


/**************************************
Stripe Payment Shortcode
**************************************/
function wporg_shortcodes_inits()
{
    add_shortcode('stripePayment', 'stripePayment');
}
add_action('init', 'wporg_shortcodes_inits');

add_action( 'wp_ajax_stripeCharge', 'stripeCharge' );
add_action( 'wp_ajax_nopriv_stripeCharge', 'stripeCharge' );

function stripeCharge(){
	
	require_once 'vendor/init.php';
	
 	// include braintree configuration
	$api_website = get_option('wlm_website');
	$api_ky = get_option('stripe_wlm_api');
	// require_once 'vendor/productionconfig.php';
	require_once 'vendor/wlmapiclass.php';
	session_start();
	$api = new wlmapiclass(($api_website) ? $api_website : 'https://www.writestorybooksforchildren.com', ($api_ky) ? $api_ky : '15f0e0f4bb1d3d109fa6f6f732a941c3');
	$api->return_format = 'php';
	
	$id = $_POST['form_id'];
	
	$current_user = wp_get_current_user();
	$user_ID = get_current_user_id();
	
	global $wpdb;
	
	$tablename = $wpdb->prefix . 'stripe_payment_tbl';
	$total = $wpdb->get_row( "SELECT * FROM $tablename where id = $id");
	
	$_POST["country"] = strtoupper($_POST["country"]);
	if($_POST["country"] == 'UK' || $_POST["country"] == 'GB' || $_POST["country"] == 'IM' || $_POST["country"] == 'JE' || $_POST["country"] == 'GG'){
		
		$wk_currency = 'GBP';
		$price 	= $total->uk_price;
		$totalprice = $priceorig =  '&#163; '.$total->uk_price;
		if($total->postage_uk){
			
			$price 		= $price+$total->postage_uk;
			$totalprice =  '&#163; '.$price;
		}
		$email		= $total->email_uk;
		// $merchantID = $total->merchant_id_uk; 
		$levelID	= $total->level_id_uk;
		$planID		= $total->plan_id_uk;
		$countryname 	= 'uk';
		$btnID			= $total->id;
		if($total->postage_uk){
		$postage	= '&#163;'.$total->postage_uk;
		}else{
			$postage = '';
		}
		// $descname	= $total->descriptor_name_uk;
		$content	= $total->course_text_uk;
		$additional_content	= $total->additional_text_uk;
		$redirecturl = $total->custom_url;
	}else if($_POST["country"] == 'EUR' || $_POST["country"] == 'AT' || $_POST["country"] == 'BE' || $_POST["country"] == 'CY' || $_POST["country"] == 'DE' || $_POST["country"] == 'DK' || $_POST["country"] == 'EE' || $_POST["country"] == 'FI' || $_POST["country"] == 'FR' || $_POST["country"] == 'GR' || $_POST["country"] == 'IS' || $_POST["country"] == 'IE' || $_POST["country"] == 'IT' || $_POST["country"] == 'LV' || $_POST["country"] == 'LT' || $_POST["country"] == 'LU' || $_POST["country"] == 'PT' || $_POST["country"] == 'SK' || $_POST["country"] == 'SI' || $_POST["country"] == 'NL' || $_POST["country"] == 'NO' || $_POST["country"] == 'ES'){
		$wk_currency = 'EUR';
		$price 	= $total->eur_price;
		$totalprice =  $priceorig =  '&#8364; '.$total->eur_price;
		if($total->postage_eur){
			
			$price 		= $price+$total->postage_eur;
			$totalprice =  '&#8364; '.$price;
		}
		
		$email 		= $total->email_eur;
		// $merchantID = $total->merchant_id_eur;
		$levelID	= $total->level_id_eur;
		$planID		= $total->plan_id_eur;
		$countryname 	= 'eur';
		$btnID			= $total->id;
		if($total->postage_eur){
		$postage	= '&#8364; '.$total->postage_eur;
		}else{
			$postage = '';
		}
		// $descname	= $total->descriptor_name_eur;
		$content	= $total->course_text_eur;
		$additional_content	= $total->additional_text_eur;
		$redirecturl = $total->custom_url;
		
	}else if($_POST["country"] == 'AUS' || $_POST["country"] == 'AU'){
		
		$wk_currency = 'AUD';
		$price 	= $total->aus_price;
		$totalprice =  $priceorig =  '&#36; '.$total->aus_price;
		if($total->postage_aus){
			
			$price 		= $price+$total->postage_aus;
			$totalprice =  '&#36; '.$price;
		}
		
		$email 		= $total->email_aus;
		// $merchantID = $total->merchant_id_aus;
		$levelID	= $total->level_id_aus;
		$planID		= $total->plan_id_aus;
		$countryname 	= 'aus';
		$btnID			= $total->id;
		if($total->postage_aus){
		$postage	= '&#36;'.$total->postage_aus;
		}else{
			$postage = '';
		}
		// $descname	 = $total->descriptor_name_aus;
		$content	= $total->course_text_aus;
		$additional_content	= $total->additional_text_aus;
		$redirecturl = $total->custom_url;
	}else{
		$wk_currency = 'USD';
		$price = $total->usa_price;
		$totalprice = $priceorig =  '&#36; '.$total->usa_price;
		if($total->postage_usa){
			
			$price 		= $price+$total->postage_usa;
			
			$totalprice =  '&#36; '.$price;
		}
		
		$email 		= $total->email_usa;
		// $merchantID = $total->merchant_id_usa;
		$levelID	= $total->level_id_usa;
		$planID		= $total->plan_id_usa;
		$countryname 	= 'usa';
		$btnID			= $total->id;
		if($total->postage_usa){
		$postage	= '&#36;'.$total->postage_usa;
		}else{
			$postage = '';
		}
		// $descname	= $total->descriptor_name_usa;
		$content	= $total->course_text_usa;
		$additional_content	= $total->additional_text_usa;
		$redirecturl = $total->custom_url;
		
	}
	if(!$redirecturl){
		$redirecturl = get_site_url();
	}
	
	$user_ID = get_current_user_id();
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$wk_customer_id = get_user_meta($user_ID, 'stripe_customer_id_sandbox', true);
	}else{
		$wk_customer_id = get_user_meta($user_ID, 'stripe_customer_id_production', true);
	}
	/************************************
		Get Stripe keys
	/************************************/
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
	}
	
	
	/***************************************************
		Stripe charge and Customer create script
	/**************************************************/
	if($_POST['action'] == 'stripeCharge'){
		
		// echo '<pre>'; 
		// echo $wk_currency.'</br>';
		// print_r($_POST); die('jkjkjkj');
		\Stripe\Stripe::setApiKey($stripesk_Key);
		$firstname 	= $_POST['firstname'];
		$lastname 	= $_POST['lastname'];
		$email 		= $_POST['email'];
		$address 	= $_POST['address'];
		$city 		= $_POST['city'];
		$state 		= $_POST['state'];
		$zip 		= $_POST['zip'];
		$country 	= $_POST['countrys'];
		$desc 		= $_POST['wk_desc'];
		$tokenid	= $_POST['stripeToken'];
		
		$dataarray = array();
		
		$dataarray['First Name']  	= $firstname;
		$dataarray['Last Name'] 	= $lastname;
		
		if($total->billing_address == 'yes'){
			$dataarray['Address'] 		= $address;
			$dataarray['State'] 		= $state;
			$dataarray['Country'] 		= $country;
			$dataarray['Zip'] 			= $zip;
		}
		
		\Stripe\Stripe::setApiKey($stripesk_Key);
		
		if($wk_customer_id){
			$cusID = $wk_customer_id;
			
			
			if($_POST['payCrd'] == 'new'){
				
				$customer = \Stripe\Customer::retrieve($wk_customer_id);
				$card = $customer->sources->create(array("source" => $tokenid));
				$customer->default_source = $card->id;
				$customer->save();
			}
			
			if($total->subscription == 'yes'){
			
				$subscription = \Stripe\Subscription::create(array(
				  "customer" => $wk_customer_id,
				  "metadata" => $dataarray,
				  "items" => array(
					array(
					  "plan" => $planID,
					),
				  )
				));
				// echo '<pre>'; print_r($subscription); die("here");
				$subscription_ID = $subscription->id;
			}else{
				
				try{
					$charge = \Stripe\Charge::create(array(
						"amount" => $price*100,
						"currency" => $wk_currency,
						"description" => $desc,
						"metadata" => $dataarray,
						"customer" => $wk_customer_id
					));
					// echo '<pre>'; print_r($charge); die("here1");
				}catch(Exception $e) {
						
					echo 'Message: ' .$e->getMessage();
					  
				}
				
				
			}
		}else{
			
			if($total->subscription == 'yes'){
			$planIDs = $planID;
		
				try{
					$customer = \Stripe\Customer::create(array(
						"email" => $email,
						"source" => $tokenid,
						"description" => $desc,
						"metadata" => $dataarray
					)
					);
					
					$cusID = $customer->id;
					
					$subscription = \Stripe\Subscription::create(array(
					  "customer" => $cusID,
					  "metadata" => $dataarray,
					  "items" => array(
						array(
						  "plan" => $planID,
						),
					  )
					));
					// echo '<pre>'; print_r($subscription); die("here");
					$subscription_ID = $subscription->id;
					
				}catch(Exception $e) {
						
					echo 'Message: ' .$e->getMessage();
					  
				}
			}else{
				try{
					$customer = \Stripe\Customer::create(array(
						"email" => $email,
						"source" => $tokenid,
						"description" => $desc,
						"metadata" => $dataarray
					)
					);
					$cusID = $customer->id;
					$charge = \Stripe\Charge::create(array(
						"amount" => $price*100,
						"currency" => $wk_currency,
						"description" => $desc,
						"customer" => $cusID,
						"metadata" => $dataarray
					));
				}catch(Exception $e) {
						
					echo 'Message: ' .$e->getMessage();
					  
				}
			
			}
			
		}
		// echo '<pre>' ; print_r($total) ; die;
		$_SESSION['customer_id'] = $cusID;
		$_SESSION['charge_ID'] = '';
		$_SESSION['subss_ID'] = '';
		$_SESSION['paymentgateway'] = 'stripe';
		
		$_SESSION['country_name'] = '';
		$_SESSION['btn_ids'] = '';
		
		$_SESSION['country_name'] = $countryname;
		$_SESSION['btn_ids'] = $btnID;
		if ($cusID) {
			$_SESSION['lebel_ID'] = $level_id = $levelID;
			
			if(isset($charge->id)){
				
				$_SESSION['charge_ID'] = $charge->id;
				
				send_admins($charge->id);
				
			}else{
				
				send_admins($subscription_ID);
				$_SESSION['subss_ID'] = $subscription_ID;
				
			}
			
			$user_ID = get_current_user_id();
			
			if(is_user_logged_in()){
				
				$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
				$chargee_id = $_SESSION['charge_ID'];
				$subscription_IDs = $_SESSION['subss_ID'];
				$lebel_ID = $_SESSION['lebel_ID'];
				$btn_ids = $_SESSION['btn_ids'];
				$country_name = $_SESSION['country_name'];
				$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id, btn_id, country) VALUES ('$user_ID', '$chargee_id', '$subscription_IDs', '$lebel_ID', '$btn_ids', '$country_name')"  );
				// session_unset($_SESSION['subss_ID']);
				// session_unset($_SESSION['charge_ID']);
				
			}
			
			if(isset($_SESSION['lebel_ID'])){			
				$data = array('Users' => $user_ID);
				$response = $api->post('/levels/'.$_SESSION['lebel_ID'].'/members', $data);
				$response = unserialize($response);
				if(isset($response['success']) && $response['success'] && $user_ID){
					
					session_unset($_SESSION['charge_ID']);
					session_unset($_SESSION['subss_ID']);
					session_unset($_SESSION['lebel_ID']);
					session_unset($_SESSION['country_name']);
					session_unset($_SESSION['btn_ids']);
					
				}
				
				$check = WLMAPI:: GetUserLevels($user_ID,$_SESSION['lebel_ID'],'','','',1);
				
				if($check){
					
					$check = WLMAPI:: UnCancelLevel($user_ID,$_SESSION['lebel_ID']);
					// $wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id) VALUES ('$user_ID', '$transaction_id', '$subscription_ID', '$lebel_ID')"  );
					
					// session_unset($_SESSION['transaction_id']);
					// session_unset($_SESSION['subscription_ID']);
					// session_unset($_SESSION['lebel_ID']);
					
				}
			}	
			
			if(Send_Reciepts($priceorig,$content,$additional_content,$postage,$totalprice)){
				
				/* if(is_user_logged_in()){
					
					$redirecturl = get_site_url();
					
				} */
				
				echo json_encode(array("status"=>true,"message"=>"Payment Received !","redirect"=>$redirecturl));	
				
			}else{
							
				echo json_encode(array("status"=>false,"message"=>"Something went wrong."));		
			}
			
			// send_admins($cusID);
		}
		exit;
	}
	}

	
	/*********************************************
		Email script for admin
	*********************************************/
	function send_admins($transaction_id){
		
		require_once("vendor/phpmailer/PHPMailerAutoload.php");
		$id = $_POST['form_id'];
		global $wpdb;
		$tablename = $wpdb->prefix . 'stripe_payment_tbl';
		$total = $wpdb->get_row( "SELECT * FROM $tablename where id = $id");	
		$admin_email = get_option('stripe_admin_to_email');
		$sender_email = get_option('stripe_admin_email_from');
		$admin_sub	 = get_option('stripe_admin_email_subject_txt');
		$email_logo	 = get_option('custom_admin_logo_id');
		// $to 		 = 'testing.whizkraft2@gmail.com';
		// $to 		 = ($admin_email) ? $admin_email : 'testing.whizkraft2@gmail.com';
		$to 		 = ($admin_email) ? $admin_email : 'admin@digitalsea.co.uk';
		$subject 	 = $total->name;
		$body = '<html><body>';
		$body .= ($email_logo) ? '<img src="'.wp_get_attachment_url(get_option('custom_admin_logo_id')).'"/>' : 
				'<img src="https://www.writestorybooksforchildren.com/wp-content/themes/WBSC-1.1.1/images/home_logo.png"/>';
		$body .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
		$body .= "<tr style='background: #eee;'><td><strong>Order:</strong> </td><td>".$total->name ."</td></tr>";
		$body .= "<tr style='background: #eee;'><td><strong> Name:</strong> </td><td>".$_POST['firstname'].', '.$_POST['lastname'].'</td></tr>';
		$body .= '<tr><td><strong> Email:</strong> </td><td>'.$_POST['email'].'</td></tr>';
		$body .= '<tr><td><strong> Customer ID:</strong> </td><td>'.$transaction_id .'</td></tr>';
		$body .= ($total->billing_address == "yes") ? '<tr><td><strong> Address:</strong> </td><td>'.$_POST['address'].', '.$_POST['city'].', '.$_POST['state'].', '.$_POST['zip'].', '.$_POST['countrys'].'</td></tr>' : '';
		$body .= '</table>';
		$body .= '</body></html>';
		$from = ($sender_email) ? $sender_email : "support@writestorybooksforchildren.com";
		
		$mail = new PHPMailer();
	
		$mail->IsSMTP(true);
		$mail->SMTPAuth   = true;
		$mail->Mailer = "smtp";
		$mail->Host= get_option('stripe_mail_host_name');
		$mail->Port = get_option('stripe_mail_port');
		$mail->Username = get_option('stripe_mail_username');
		$mail->Password = get_option('stripe_mail_password');
		$mail->SetFrom($from, 'noreply');
		$mail->AddReplyTo($from,'support@writestorybooksforchildren.com');
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$address = $to;
		$mail->AddAddress($address);
		
		if(!$mail->Send())
		return false;
		else
		return true;
		
	}
	
	/*********************************************
		Email script for user
	*********************************************/
	function Send_Reciepts($priceorig,$content,$additional_content,$postage,$totalprice) {
	
	require_once("vendor/phpmailer/PHPMailerAutoload.php");
	require_once("vendor/init.php");
	
	$custom_email_txt = str_replace('https://', '', get_option( 'stripe_email_url' ));
	$maildata .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">

	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />


		<style type="text/css">
			img {
				max-width: 100%;
			}

			body {
				-webkit-font-smoothing: antialiased;
				-webkit-text-size-adjust: none;
				width: 100% !important;
				height: 100%;
				line-height: 1.6em;
			}

			body {
				background-color: #f6f6f6;
			}

			@media only screen and (max-width: 640px) {
				body {
					padding: 0 !important;
				}
				h1 {
					font-weight: 800 !important;
					margin: 20px 0 5px !important;
				}
				h2 {
					font-weight: 800 !important;
					margin: 20px 0 5px !important;
				}
				h3 {
					font-weight: 800 !important;
					margin: 20px 0 5px !important;
				}
				h4 {
					font-weight: 800 !important;
					margin: 20px 0 5px !important;
				}
				h1 {
					font-size: 22px !important;
				}
				h2 {
					font-size: 18px !important;
				}
				h3 {
					font-size: 16px !important;
				}
				.container {
					padding: 0 !important;
					width: 100% !important;
				}
				.content {
					padding: 0 !important;
				}
				.content-wrap {
					padding: 10px !important;
				}
				.invoice {
					width: 100% !important;
				}
			}
		</style>
	</head>

	<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;"
		bgcolor="#f6f6f6">

		<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">
			<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
				<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
				<td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;"
					valign="top">
					<div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
						<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;"
							bgcolor="#fff">
							<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="content-wrap aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 20px;" align="center" valign="top">
									<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">';
									$maildata .= (get_option('custom_logo_id')) ? '<img style="background-color:black" src="'.wp_get_attachment_url(get_option('custom_logo_id')).'">':
										'<img style="background-color:black" src="http://d73wf1flin6eu.cloudfront.net/wp-content/uploads/2015/09/11143407/WSBC_Logo_small.png">';
										$maildata .= '<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
												<h1 class="aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,\'Lucida Grande\',sans-serif; box-sizing: border-box; font-size: 32px; color: #000; line-height: 1.2em; font-weight: 500; text-align: center; margin: 40px 0 0;" align="center">'.$totalprice.' Paid</h1>
											</td>
										</tr>
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
											<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
												<h2 class="aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,\'Lucida Grande\',sans-serif; box-sizing: border-box; font-size: 24px; color: #000; line-height: 1.2em; font-weight: 400; text-align: center; margin: 40px 0 0;" align="center">Thanks for your Order.</h2>
											</td>
										</tr>
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
											<td class="content-block aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
												align="center" valign="top">
												<table class="invoice" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; text-align: left; width: 80%; margin: 40px auto;">
													<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
														<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 5px 0;" valign="top">
															<table class="invoice-items" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; margin: 0;">
																<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">';
																	$maildata .= ($content) ?  '<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
                                                                    valign="top">'.$content.'</td>':'<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
                                                                    valign="top">Write Story Books For Children</td>';
															$maildata .= ($postage) ? '<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																	<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
																		valign="top">Postage</td>
																	<td class="alignright" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 1px; border-top-color: #eee; border-top-style: solid; margin: 0; padding: 5px 0;"
																		align="right" valign="top">'.$postage.'</td>
																</tr>' : '';
															$maildata .=   ' <tr class="total" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
																	<td class="alignright" width="80%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;"
																		align="right" valign="top">Total</td>
																	<td class="alignright" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: right; border-top-width: 2px; border-top-color: #333; border-top-style: solid; border-bottom-color: #333; border-bottom-width: 2px; border-bottom-style: solid; font-weight: 700; margin: 0; padding: 5px 0;"
																		align="right" valign="top">'.$totalprice.'</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">';
										$maildata .= (($additional_content) ?  '<td clas="content-block aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0px 0px 20px;"
										valign="top">'.$additional_content.'</td>' : '').' 
										</tr>
										
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
											<td class="content-block aligncenter" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; text-align: center; margin: 0; padding: 0 0 20px;"
												align="center" valign="top">
												<small>Barn Studios, High Street, Farndon, Chester, CH3 6PT, United Kingdom.</small>
												  <br>';
												$maildata .= (get_option('stripe_email_url')) ?  '<a href="'.get_option('stripe_email_url').'">'.$custom_email_txt.'</a>':'<a href="https://www.writestorybooksforchildren.com/">www.writestorybooksforchildren.com</a>';
											$maildata .= '</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</div>
				</td>
				<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
			</tr>
		</table>
	</body>

	</html>';
	// echo 'there12';
	$form_email = get_option('stripe_email_from');
	$repecit_sub = get_option('stripe_email_subject_txt');
	$message = $maildata;
	$mail = new PHPMailer();
	$mail->IsSMTP(true);
	$mail->SMTPAuth   = true;
	$mail->Mailer = "smtp";
	$mail->Host= get_option('stripe_mail_host_name');
	$mail->Port = get_option('stripe_mail_port');
	$mail->Username = get_option('stripe_mail_username');
	$mail->Password = get_option('stripe_mail_password');
	$mail->SetFrom(($form_email) ? $form_email : 'noreply@writestorybooksforchildren.com');
	$mail->Subject = ($repecit_sub) ? $repecit_sub : 'Receipt - writestorybooksforchildren.com';
	$mail->MsgHTML($message);
	$mail->AddAddress($_POST['email']);
	if(!$mail->Send())
	return false;
	else
	return true;

	}
add_action( 'user_register', 'myplugin_registration_saves', 10, 1 );

function myplugin_registration_saves( $user_id ) {
	
	session_start();
	
	if(isset($_SESSION['paymentgateway']) && $_SESSION['paymentgateway'] == 'stripe'){
		
		global $wpdb;
		
		$chargee_id = $_SESSION['charge_ID'];
		$subscription_IDs = $_SESSION['subss_ID'];
		$lebel_ID = $_SESSION['lebel_ID'];
		$country_name = $_SESSION['country_name'];
		$btn_ids = $_SESSION['btn_ids'];
		$lebel_ID = $_SESSION['lebel_ID'];
		$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	
		if ( isset( $_SESSION['customer_id'] ) && !empty ( $_SESSION['customer_id'] )){
		
			if(get_option('stripe_payment_mode') == 'sandbox'){
			
				update_user_meta($user_id, 'stripe_customer_id_sandbox', $_SESSION['customer_id']);
				$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id, btn_id, country) VALUES ('$user_id', '$chargee_id', '$subscription_IDs', '$lebel_ID', '$btn_ids', '$country_name')"  );
			
			}else{
			
				update_user_meta($user_id, 'stripe_customer_id_production', $_SESSION['customer_id']);
				$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id, btn_id, country) VALUES ('$user_id', '$chargee_id', '$subscription_IDs', '$lebel_ID', '$btn_ids', '$country_name')"  );
			}
		
			session_unset($_SESSION['customer_id']);
			session_unset($_SESSION['lebel_ID']);
			session_unset($_SESSION['subss_ID']);
			session_unset($_SESSION['charge_ID']);
			session_unset($_SESSION['btn_ids']);
			session_unset($_SESSION['country_name']);
		}
		session_unset($_SESSION['paymentgateway']);
	}
}


/******************************
login authenticate function
******************************/
/*function stripe_customcode($user, $data, $mw) {
	
	session_start();
	global $wpdb;
	$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	$chargee_id = $_SESSION['chargee_id'];
	$subscription_IDs = $_SESSION['subss_ID'];
	$lebel_ID = $_SESSION['lebel_ID'];
	
    //$check = WLMAPI:: GetUserLevels($user,$_SESSION['lebel_ID'],'','','',1);
				
	//if($check){
		
		//$check = WLMAPI:: UnCancelLevel($user,$_SESSION['lebel_ID']);
		$wpdb->query("INSERT INTO $histry_table_name (user_id, charge_id, subscription_id, level_id) VALUES ('$user', '$chargee_id', '$subscription_ID', '$lebel_ID')"  );
		
		session_unset($_SESSION['transaction_id']);
		session_unset($_SESSION['subscription_ID']);
		session_unset($_SESSION['lebel_ID']);
		
	//}
}
add_action('wishlistmember_user_registered', 'stripe_customcode',10,3);*/




add_action( 'admin_footer', 'media_selector_print_scripts_' );
function media_selector_print_scripts_() {
	wp_enqueue_media();
	$my_saved_attachment_post_id = get_option( 'custom_logo_id', 0 );
	?><script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
			$('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				if ( file_frame ) {
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					file_frame.open();
					return;
				} else {
					wp.media.model.settings.post.id = set_to_post_id;
				}
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				file_frame.on( 'select', function() {
					attachment = file_frame.state().get('selection').first().toJSON();
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#image_attachment_id' ).val( attachment.id );
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					file_frame.open();
			});
			$( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script><?php
}


add_action( 'admin_footer', 'media_selector_admin_logos' );
function media_selector_admin_logos() {
	wp_enqueue_media();
	$my_saved_attachment_post_ids = get_option( 'custom_admin_logo_id', 0 );
	?><script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_ids; ?>; // Set this
			$('#upload_image_button1').on('click', function( event ){
				event.preventDefault();
				if ( file_frame ) {
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					file_frame.open();
					return;
				} else {
					wp.media.model.settings.post.id = set_to_post_id;
				}
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				file_frame.on( 'select', function() {
					attachment = file_frame.state().get('selection').first().toJSON();
					$( '#image-preview1' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#image_attachment_id1' ).val( attachment.id );
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					file_frame.open();
			});
			$( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
			$(".close").click(function(){
				$(".alert").hide();
			});
		});
	</script><?php
}

/**************************************************************************
Web-hook function for check if card is expired or subscription is canceled
**************************************************************************/
function wporg_shortcodes_my_stripe_webhook_init(){
	
	require 'vendor/init.php';
	global $wpdb;
	$histry_table_name = $wpdb->prefix . 'stripe_payment_history_tbl';
	$user_ID = get_current_user_id();
	$cookie_name = "stripePayment_cookie";
	$cookie_value = "stripePayment_cookie_value";
	
	if(get_option('stripe_payment_mode') == 'sandbox'){
		$stripesk_Key = get_option('sandbox_secret_key');
	}else{
		$stripesk_Key = get_option('live_secret_key');
	}
	
	
	if($user_ID && !isset($_COOKIE[$cookie_name])){
		
		$sql = $wpdb->get_results( "SELECT * FROM $histry_table_name where user_id = $user_ID AND subscription_id != '' AND status = 'active'");
		
		foreach($sql as $_sql){
			
			$id = $_sql->id;
			$user_ids = $_sql->user_id;
			$subscription_id = $_sql->subscription_id;
			$status = $_sql->status;
			$level_id = $_sql->level_id;
			
			\Stripe\Stripe::setApiKey($stripesk_Key);
			$subs = \Stripe\Subscription::retrieve($subscription_id);
			
			if($subs && ($subs->status != 'Active' || $subs->status != 'active')){
				
				$result = WLMAPI:: CancelLevel($user_ids,$level_id);
				$wpdb->query("UPDATE $histry_table_name SET status='cancelled' WHERE id = $id");
				
			}
		}
		
		// check if subscription date is end and equal to the current date,
		$date1 = date("Y-m-d"); // current date
		
		$totalwk = $wpdb->get_results( "SELECT * FROM $histry_table_name where user_id = '$user_ID' AND status = 'cancelled' AND label_status = 'active' AND subs_end_date <= '$date1'");
		
		foreach($totalwk as $_totalwk){
			
			$id = $_totalwk->id;
			$users_id = $_totalwk->user_id;
			$levels_id = $_totalwk->level_id;
			$status = $_totalwk->status;
			$label_status = $_totalwk->label_status;
			$subs_end_date = $_totalwk->subs_end_date;
			WLMAPI::CancelLevel($users_id,$levels_id); // Cancel member level.
			$wpdb->query("UPDATE $histry_table_name SET level_id='$levels_id',status='cancelled',label_status='cancelled' WHERE id = $id");
			
		}
		
		setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 86400 = 1 day
	}
	return;
}
add_action('init', 'wporg_shortcodes_my_stripe_webhook_init');

/*********************************
Add stripe modal script in footer
*********************************/
function immi_footer_stripe(){
?>
	<script>
		jQuery(document).ready(function(){
			
			jQuery(".wz_show_pay_form").each(function(){
				jQuery(this).appendTo('body');
			});
			
		});
	</script>
<?php
}
add_action('wp_footer','immi_footer_stripe');
?>