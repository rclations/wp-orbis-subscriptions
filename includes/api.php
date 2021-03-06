<?php

function orbis_subscriptions_api_call() {
	global $wpdb;

	$api_call = get_query_var( 'api_call' );
	
	if ( ! empty( $api_call ) ) {
		$object = get_query_var( 'api_object' );
		$method = get_query_var( 'api_method' );

		// @see http://www.hurl.it/hurls/8e92c1758b0ff0258f5bb4f76ab41b8970b653dd/1ead533aa50f71212b5393e43611b42384e40b0f
		if ( $object == 'licenses' && $method == 'show' ) {
			$type = INPUT_POST;
	
			$key = filter_input( $type, 'key', FILTER_SANITIZE_STRING );
			$url = filter_input( $type, 'url', FILTER_SANITIZE_STRING );
	
			$domain = parse_url( $url, PHP_URL_HOST );
			if ( substr( $domain, 0, 4 ) == 'www.' ) {
				$domain = substr( $domain, 4 );
			}
	
			$query = "
				SELECT
					subscription.id ,
					subscription.name AS subscriptionName ,
					subscription.activation_date AS activationDate ,
					subscription.expiration_date AS expirationDate ,
					subscription.cancel_date AS cancelDate ,
					subscription.update_date AS updateDate ,
					subscription.license_key AS licenseKey ,
					subscription.expiration_date > NOW() AS isValid ,
					company.name AS companyName ,
					type.name AS typeName ,
					type.price AS price ,
					domain_name.domain_name AS domainName
				FROM
					$wpdb->orbis_subscriptions AS subscription
						LEFT JOIN
					$wpdb->orbis_companies AS company
							ON subscription.company_id = company.id
						LEFT JOIN
					$wpdb->orbis_subscription_products AS type
							ON subscription.type_id = type.id
						LEFT JOIN
					orbis_domain_names AS domain_name
							ON subscription.domain_name_id = domain_name.id
				WHERE
					subscription.license_key_md5 = %s
			";
	
			global $wpdb;
	
			$query = $wpdb->prepare( $query, $key );
	
			$subscription = $wpdb->get_row( $query );
	
			if ( $subscription != null ) {
				if ( $subscription->subscriptionName != '*' ) {
					$isValidDomain = $subscription->subscriptionName == $domain;
	
					$subscription->isValid &= $isValidDomain;
				}

				$subscription->isValid = filter_var( $subscription->isValid, FILTER_VALIDATE_BOOLEAN );
			}
	
			header( 'Content-Type: application/json' );
	
			echo json_encode( $subscription );
		}
	
		die();
	}
}

// High priority because the API is public avaiable (members plugin)
add_action( 'template_redirect', 'orbis_subscriptions_api_call', 0 );
