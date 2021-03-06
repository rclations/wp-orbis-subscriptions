<?php

global $wpdb, $post;

$orbis_id        = get_post_meta( $post->ID, '_orbis_subscription_id', true );
$company_id      = get_post_meta( $post->ID, '_orbis_subscription_company_id', true );
$type_id         = get_post_meta( $post->ID, '_orbis_subscription_type_id', true );
$name            = get_post_meta( $post->ID, '_orbis_subscription_name', true );
$license_key     = get_post_meta( $post->ID, '_orbis_subscription_license_key', true );
$activation_date = get_post_meta( $post->ID, '_orbis_subscription_activation_date', true );
$expiration_date = get_post_meta( $post->ID, '_orbis_subscription_activation_date', true );
$cancel_date     = get_post_meta( $post->ID, '_orbis_subscription_cancel_date', true );
$email           = get_post_meta( $post->ID, '_orbis_subscription_email', true );

if ( true ) { // empty( $orbis_id ) ) {
	$subscription =  $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->orbis_subscriptions WHERE post_id = %d;", $post->ID ) );

	if ( $subscription ) {
		$orbis_id        = $subscription->id;
		$company_id      = $subscription->company_id;
		$type_id         = $subscription->type_id;
		$name            = $subscription->name;
		$license_key     = $subscription->license_key;
		$activation_date = $subscription->activation_date;
		$expiration_date = $subscription->expiration_date;
		$cancel_date     = $subscription->cancel_date;
	}
}

$company_post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->orbis_companies WHERE id = %d;", $company_id ) );

?>
<div class="panel">
	<header>
		<h3><?php _e( 'Subscription Renewal', 'orbis_subscriptions' ); ?></h3>
	</header>

	<div class="content">
		<form method="post" action="">
			<fieldset>
				<?php wp_nonce_field( 'orbis_subscription_renew', 'orbis_subscriptions_nonce' ); ?>
	
				<input name="orbis_subscription_subject" type="hidden" value="<?php echo esc_attr__( 'Pronamic iDEAL License Key Extended', 'orbis_subscriptions' ); ?>" />

				<div class="form-group">
					<label for="orbis_subscription_extend_note"><?php _e( 'Note', 'orbis_subscriptions' ); ?></label>
					<textarea id="orbis_subscription_extend_note" class="form-control" name="orbis_subscription_extend_note" rows="3"></textarea>
				</div>
  
  				<div class="form-group">
					<label for="orbis_subscription_email"><?php _e( 'Email', 'orbis_subscriptions' ); ?></label>
					<input id="orbis_subscription_email" class="form-control" name="orbis_subscription_email" type="email" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php echo esc_attr__( 'Email', 'orbis_subscriptions' ); ?>" />
				</div>

				<div class="checkbox">
					<label>
						<input name="orbis_subscrtion_renew_check" value="true" type="checkbox"><?php  _e( 'Extend this subscription with 1 year.', 'orbis_subscriptions' ); ?>
					</label>
				</div>

				<button name="orbis_subscrtion_renew" type="submit" class="btn btn-default"><?php _e( 'Extend', 'orbis_subscriptions' ); ?></button>
			</fieldset>
		</form>
	</div>
</div>