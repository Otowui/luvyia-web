<?php
/**
 * Theme Functions
 *
 * Custom REST API endpoints, user metadata handling, and admin UI customizations
 * for headless CMS usage with React/mobile app.
 *
 * @package LuviyaTheme
 * 
 * Wp Admin: 
 * 	yoolk132_0ajxchnc
 * 	W!T5wq^E81nvmfrn"
 * 
 * Htpwd:
 * 	preview
 * 	k_v8%LqeaXP2i7xz
 * 	
 * Woocommerce API:
 * Consumer key: ck_42a9b4d7f5ad95d8818584f3ffe1503162b74bd4
 * Consumer secret; cs_2ffd1cc2b0273c882b779445d1142cba096257d5
 */
 
/**
 * Allow CORS for all REST API endpoints.
 * Applies CORS headers dynamically based on the `Origin` request header.
 * This allows cross-origin communication from the headless frontend.
 */
if ( isset( $_SERVER['HTTP_ORIGIN'] ) ) {
	$origin = esc_url_raw( $_SERVER['HTTP_ORIGIN'] );
	header( "Access-Control-Allow-Origin: $origin" );
	header( 'Access-Control-Allow-Credentials: true' );
	header( 'Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE' );
	header( 'Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce' );
}

if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
	status_header(200);
	exit;
}

/**
 * Register custom `/me` endpoint to retrieve current authenticated user.
 * Avoids native `/wp/v2/users/me` to control fields and security.
 */
add_action( 'rest_api_init', function() {
	register_rest_route( 'custom/v1', '/me', [
		'methods'             => 'GET',
		'callback'            => 'luviya_custom_me',
		'permission_callback' => '__return_true',
	] );
});

/**
 * Returns sanitized user profile for the authenticated user.
 *
 * @param \WP_REST_Request $req The REST request instance.
 * @return array|\WP_Error      Array of user data or WP_Error if unauthenticated.
 */
function luviya_custom_me( \WP_REST_Request $req ) {
	$user = wp_get_current_user();
	
	if ( $user->ID === 0 ) {
		return new WP_Error( 'no_user', 'Pas connecté', [ 'status' => 401 ] );
	}
	
	return [
		'id'                    => $user->ID,
		'username'              => $user->user_login,
		'display_name'          => $user->display_name,
		'nickname'              => $user->nickname,
		'email'                 => $user->user_email,
		'first_name'            => $user->user_firstname,
		'last_name'             => $user->user_lastname,
		'roles'                 => $user->roles,
		'avatar_urls'           => get_avatar_url( $user->ID, ['size' => 96] ),
	
		// Core meta
		'birth_date'            => get_user_meta( $user->ID, 'birth_date', true ),
		'accept_cgu'            => ( get_user_meta( $user->ID, 'accept_cgu', true ) === 'yes' ),
		'cgu_accepted_at'       => get_user_meta( $user->ID, 'cgu_accepted_at', true ),
		'email_verified'        => ( get_user_meta( $user->ID, 'email_verified', true ) === '1' ),
		'form_filled'           => ( get_user_meta( $user->ID, 'form_filled', true ) === '1' ),
	
		// Single‑select onboarding fields
		'desc_gender'           => get_user_meta( $user->ID, 'desc_gender', true ),
		'search_gender'         => get_user_meta( $user->ID, 'search_gender', true ),
		'desc_size'             => get_user_meta( $user->ID, 'desc_size', true ),
		'desc_weight'           => get_user_meta( $user->ID, 'desc_weight', true ),
		'desc_style'            => get_user_meta( $user->ID, 'desc_style', true ),
		'desc_tattoo'           => get_user_meta( $user->ID, 'desc_tattoo', true ),
		'desc_smoker'           => get_user_meta( $user->ID, 'desc_smoker', true ),
		'desc_drinker'          => get_user_meta( $user->ID, 'desc_drinker', true ),
	
		// Multi‑select onboarding fields
		'desc_sports'           => (array) get_user_meta( $user->ID, 'desc_sports', true ),
		'desc_music_styles'     => (array) get_user_meta( $user->ID, 'desc_music_styles', true ),
		'desc_play_music'       => (array) get_user_meta( $user->ID, 'desc_play_music', true ),
		'desc_artistic'         => (array) get_user_meta( $user->ID, 'desc_artistic', true ),
		'desc_outings'          => (array) get_user_meta( $user->ID, 'desc_outings', true ),
		'desc_cause'            => (array) get_user_meta( $user->ID, 'desc_cause', true ),
		'desc_origin'           => (array) get_user_meta( $user->ID, 'desc_origin', true ),
		'desc_religion'         => (array) get_user_meta( $user->ID, 'desc_religion', true ),
	
		// Free‑text onboarding field
		'desc_weekend'          => get_user_meta( $user->ID, 'desc_weekend', true ),
	
		// Additional single‑select fields
		'desc_children'         => get_user_meta( $user->ID, 'desc_children', true ),
		'desc_pets'             => get_user_meta( $user->ID, 'desc_pets', true ),
		'desc_living_alone'     => get_user_meta( $user->ID, 'desc_living_alone', true ),
		'desc_housing'          => get_user_meta( $user->ID, 'desc_housing', true ),
		'desc_relocation'       => get_user_meta( $user->ID, 'desc_relocation', true ),
		'desc_city_importance'  => get_user_meta( $user->ID, 'desc_city_importance', true ),
	
		// Dropdown/select field
		'desc_sector'           => get_user_meta( $user->ID, 'desc_sector', true ),
	
		// More single‑select fields
		'desc_time_type'        => get_user_meta( $user->ID, 'desc_time_type', true ),
		'desc_telework'         => get_user_meta( $user->ID, 'desc_telework', true ),
		'desc_weekly_rhythm'    => get_user_meta( $user->ID, 'desc_weekly_rhythm', true ),
		'desc_mobility'         => get_user_meta( $user->ID, 'desc_mobility', true ),
		'desc_career_priority'  => get_user_meta( $user->ID, 'desc_career_priority', true ),
		'desc_lifestyle'        => get_user_meta( $user->ID, 'desc_lifestyle', true ),
		'desc_income_bracket'   => get_user_meta( $user->ID, 'desc_income_bracket', true ),
	];
}

/**
 * Register custom `/verify` endpoint to validate email confirmation token.
 */
add_action('rest_api_init', function() {
	register_rest_route('custom/v1','/verify',[
		'methods'             => 'GET',
		'callback'            => 'luviya_verify_email',
		'permission_callback' => '__return_true',
	]);
});

/**
 * Confirms user email address via token.
 *
 * @param \WP_REST_Request $req REST request containing 'token' parameter.
 * @return array|\WP_Error       Success or error.
 */
function luviya_verify_email(\WP_REST_Request $req) {
	$token = sanitize_text_field($req->get_param('token'));
	
	$users = get_users([
		'meta_key'   => 'email_verification_token',
		'meta_value' => $token,
		'number'     => 1
	]);
	
	if (empty($users)) {
		return new WP_Error('invalid_token','Token invalide ou expiré',['status'=>400]);
	}
	
	$user = $users[0];
	update_user_meta($user->ID, 'email_verified', true);
	delete_user_meta($user->ID, 'email_verification_token');
	
	return [
		'success' => true,
		'message' => 'E-mail vérifié ! Vous pouvez maintenant vous connecter.',
	];
}

/**
 * Register custom `/register` endpoint for user registration.
 */
add_action( 'rest_api_init', function() {
	register_rest_route( 'custom/v1', '/register', [
		'methods'             => 'POST',
		'callback'            => 'luviya_custom_register',
		'permission_callback' => '__return_true',
	] );
});

/**
 * Registers a new user with basic info and sends verification email.
 *
 * @param \WP_REST_Request $req REST request with required fields:
 *                              username, email, password, birth_date, accept_cgu.
 * @return array|\WP_Error       New user object or error.
 */
function luviya_custom_register( \WP_REST_Request $req ) {
	$username   = sanitize_user( $req->get_param( 'username' ) );
	$email      = sanitize_email( $req->get_param( 'email' ) );
	$password   = $req->get_param( 'password' );
	$birth_date = $req->get_param( 'birth_date' );   // attendu au format YYYY-MM-DD
	$accept_cgu = $req->get_param( 'accept_cgu' );   // booléen ou 'yes'

	if ( empty( $birth_date ) ) {
		return new WP_Error( 'no_birth_date', __( 'Le champ birth_date est requis.', 'luviya' ), [ 'status' => 400 ] );
	}
	$d = DateTime::createFromFormat( 'Y-m-d', $birth_date );
	if ( ! $d || $d->format( 'Y-m-d' ) !== $birth_date ) {
		return new WP_Error( 'invalid_birth_date', __( 'Le format de birth_date est invalide (YYYY-MM-DD).', 'luviya' ), [ 'status' => 400 ] );
	}
	
	if ( ! $accept_cgu ) {
		return new WP_Error( 'no_cgu', __( 'Vous devez accepter les CGU.', 'luviya' ), [ 'status' => 400 ] );
	}
	
	if ( username_exists( $username ) || email_exists( $email ) ) {
		return new WP_Error( 'user_exists', __( 'Utilisateur déjà existant.', 'luviya' ), [ 'status' => 400 ] );
	}
	
	$user_id = wp_create_user( $username, $password, $email );
	
	if ( is_wp_error( $user_id ) ) {
		return $user_id;
	}
	
	$verif_token = wp_generate_password(20, false);
	
	// Meta storage
	update_user_meta( $user_id, 'email_verification_token', $verif_token);
	update_user_meta( $user_id, 'email_verified', false);
	update_user_meta( $user_id, 'form_filled', false);
	update_user_meta( $user_id, 'birth_date', sanitize_text_field( $birth_date ) );
	update_user_meta( $user_id, 'accept_cgu', 'yes' );
	update_user_meta( $user_id, 'cgu_accepted_at', current_time( 'mysql' ) );
	
	// Email notification
	$front_url    = 'https://luvyia.com';
	$verify_link  = $front_url . '/verify?token=' . rawurlencode( $verif_token );
	$subject      = 'Merci de confirmer votre adresse e-mail';
	$message      = "Cliquez sur ce lien pour activer votre compte : {$verify_link}";
	wp_mail($email, $subject, $message);
	
	return [
		'user' => [
			'id'          => $user_id,
			'username'    => $username,
			'email'       => $email
		],
	];
}

/**
 * Display custom user meta fields in the WordPress admin user profile.
 *
 * @param WP_User $user The user object for the profile being edited.
 */
function luviya_profile_fields( $user ) {
	// Récupère les meta existantes
	$birth_date      = get_user_meta( $user->ID, 'birth_date',         true ); // input date : Format YYYY-MM-DD
	$accept_cgu      = get_user_meta( $user->ID, 'accept_cgu',         true );
	$cgu_accepted_at = get_user_meta( $user->ID, 'cgu_accepted_at',    true );
	$email_verified  = get_user_meta( $user->ID, 'email_verified',     true );
	$form_filled     = get_user_meta( $user->ID, 'form_filled',        true );
	?>
	<h3 style="margin-top:2rem;"><?php _e( 'Mon Profil Luvyia', 'luviya' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="birth_date"><?php _e( 'Date de naissance', 'luviya' ); ?></label></th>
			<td>
				<input
					type="date"
					name="birth_date"
					id="birth_date"
					value="<?php echo esc_attr( $birth_date ); ?>"
					class="regular-text"
				/>
				<p class="description"><?php _e( 'Format YYYY-MM-DD', 'luviya' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'CGU acceptées', 'luviya' ); ?></th>
			<td>
				<label>
					<input
						type="checkbox"
						name="accept_cgu"
						value="yes"
						<?php checked( $accept_cgu, 'yes' ); ?>
					/>
					<?php _e( 'L’utilisateur a accepté les CGU', 'luviya' ); ?>
				</label>
				<?php if ( $accept_cgu && $cgu_accepted_at ) : ?>
					<p class="description">
						<?php
						/* traduit en FR : */
						echo sprintf(
							__( 'Accepté le %s', 'luviya' ),
							date_i18n( 'd/m/Y H:i', strtotime( $cgu_accepted_at ) )
						);
						?>
					</p>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th><?php _e( 'Email vérifié', 'luviya' ); ?></th>
			<td>
				<label>
				<input
					type="checkbox"
					name="email_verified"
					value="yes"
					<?php checked( $email_verified, '1' ); ?>
				/> <?php _e( 'L’utilisateur a vérifié son email', 'luviya' ); ?>
				</label>
			</td>
			</tr>
		
			<tr>
			<th><?php _e( 'Formulaire rempli', 'luviya' ); ?></th>
			<td>
				<label>
				<input
					type="checkbox"
					name="form_filled"
					value="yes"
					<?php checked( $form_filled, '1' ); ?>
				/> <?php _e( 'Le formulaire d’onboarding est complété', 'luviya' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile',   'luviya_profile_fields' );
add_action( 'edit_user_profile',   'luviya_profile_fields' );
 
/**
 * Save custom profile fields from admin user profile.
 *
 * @param int $user_id The user ID being saved.
 * @return false|void
 */
function luviya_save_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	// Validate and save birth_date
	if ( isset( $_POST['birth_date'] ) ) {
		$date = sanitize_text_field( $_POST['birth_date'] );
		$d = DateTime::createFromFormat( 'Y-m-d', $date );
		if ( $d && $d->format( 'Y-m-d' ) === $date ) {
			update_user_meta( $user_id, 'birth_date', $date );
		}
	}

	// Accept CGU
	if ( isset( $_POST['accept_cgu'] ) && $_POST['accept_cgu'] === 'yes' ) {
		if ( get_user_meta( $user_id, 'accept_cgu', true ) !== 'yes' ) {
			update_user_meta( $user_id, 'cgu_accepted_at', current_time( 'mysql' ) );
		}
		update_user_meta( $user_id, 'accept_cgu', 'yes' );
	} else {
		update_user_meta( $user_id, 'accept_cgu', 'no' );
	}
	
	// Email verified
	if ( isset( $_POST['email_verified'] ) && $_POST['email_verified'] === 'yes' ) {
		update_user_meta( $user_id, 'email_verified', '1' );
	} else {
		update_user_meta( $user_id, 'email_verified', '0' );
	}
	
	// Onboarding filled
	if ( isset( $_POST['form_filled'] ) && $_POST['form_filled'] === 'yes' ) {
		update_user_meta( $user_id, 'form_filled', '1' );
	} else {
		update_user_meta( $user_id, 'form_filled', '0' );
	}
}
add_action( 'personal_options_update', 'luviya_save_profile_fields' );
add_action( 'edit_user_profile_update','luviya_save_profile_fields' );

/**
 * Display onboarding fields (desc_*, search_gender, and new interests) in admin user profile.
 *
 * @param WP_User $user The user being edited.
 */
function luviya_onboarding_profile_fields( $user ) {
	$fields = [
		'desc_gender'                => get_user_meta( $user->ID, 'desc_gender',                true ),
		'search_gender'              => get_user_meta( $user->ID, 'search_gender',              true ),
		'desc_size'                  => get_user_meta( $user->ID, 'desc_size',                  true ),
		'desc_weight'                => get_user_meta( $user->ID, 'desc_weight',                true ),
		'desc_style'                 => get_user_meta( $user->ID, 'desc_style',                 true ),
		'desc_tattoo'                => get_user_meta( $user->ID, 'desc_tattoo',                true ),
		'desc_smoker'                => get_user_meta( $user->ID, 'desc_smoker',                true ),
		'desc_drinker'               => get_user_meta( $user->ID, 'desc_drinker',               true ),
		'desc_sports'                => (array) get_user_meta( $user->ID, 'desc_sports',        true ),
		'desc_music_styles'          => (array) get_user_meta( $user->ID, 'desc_music_styles',  true ),
		'desc_play_music'            => (array) get_user_meta( $user->ID, 'desc_play_music',    true ),
		'desc_artistic'              => (array) get_user_meta( $user->ID, 'desc_artistic',      true ),
		'desc_outings'               => (array) get_user_meta( $user->ID, 'desc_outings',       true ),
		'desc_cause'                 => (array) get_user_meta( $user->ID, 'desc_cause',         true ),
		'desc_origin'                => (array) get_user_meta( $user->ID, 'desc_origin',        true ),
		'desc_religion'              => (array) get_user_meta( $user->ID, 'desc_religion',      true ),
		'desc_weekend'               => get_user_meta( $user->ID, 'desc_weekend',               true ),
		'desc_children'              => get_user_meta( $user->ID, 'desc_children',              true ),
		'desc_pets'                  => get_user_meta( $user->ID, 'desc_pets',                  true ),
		'desc_living_alone'          => get_user_meta( $user->ID, 'desc_living_alone',          true ),
		'desc_housing'               => get_user_meta( $user->ID, 'desc_housing',               true ),
		'desc_relocation'            => get_user_meta( $user->ID, 'desc_relocation',            true ),
		'desc_city_importance'       => get_user_meta( $user->ID, 'desc_city_importance',       true ),
		'desc_sector'                => get_user_meta( $user->ID, 'desc_sector',                true ),
		'desc_time_type'             => get_user_meta( $user->ID, 'desc_time_type',             true ),
		'desc_telework'              => get_user_meta( $user->ID, 'desc_telework',              true ),
		'desc_weekly_rhythm'         => get_user_meta( $user->ID, 'desc_weekly_rhythm',         true ),
		'desc_mobility'              => get_user_meta( $user->ID, 'desc_mobility',              true ),
		'desc_career_priority'       => get_user_meta( $user->ID, 'desc_career_priority',       true ),
		'desc_lifestyle'             => get_user_meta( $user->ID, 'desc_lifestyle',             true ),
		'desc_income_bracket'        => get_user_meta( $user->ID, 'desc_income_bracket',        true ),
	];
 
	// Définitions des options
	$genders       = ['male'=>'Homme','female'=>'Femme'];
	$weights       = [
		'mince'      => 'Mince',
		'athletique' => 'Athlétique',
		'normale'    => 'Normale',
		'pulpeuse'   => 'Pulpeuse',
		'corpulente' => 'Corpulente',
	];
	$styles        = [
		'moderne'     => 'Moderne',
		'classique'   => 'Classique',
		'alternatif'  => 'Alternatif',
		'boheme'      => 'Bohème',
		'urbain'      => 'Urbain',
		'sportif'     => 'Sportif',
		'elegant'     => 'Élégant',
		'decontracte' => 'Décontracté',
	];
	$yes_no       = ['yes'=>'Oui','no'=>'Non'];
	$yes_no_occ   = ['yes'=>'Oui','no'=>'Non','occasionnellement'=>'Occasionnellement'];
	
	$sports_opts = [
		'football'    => 'Football',
		'basketball'  => 'Basketball',
		'tennis'      => 'Tennis',
		'natation'    => 'Natation',
		'running'     => 'Course à pied',
		'cyclisme'    => 'Cyclisme',
		'yoga'        => 'Yoga',
		'crossfit'    => 'Crossfit',
		'autre_sport' => 'Autre'
	];
	
	$music_styles_opts = [
		'rock'       => 'Rock',
		'pop'        => 'Pop',
		'jazz'       => 'Jazz',
		'classical'  => 'Classique',
		'hiphop'     => 'Hip-hop',
		'electronic' => 'Électronique',
		'metal'      => 'Metal',
		'folk'       => 'Folk',
		'blues'      => 'Blues',
		'autre_musique' => 'Autre'
	];
	
	$play_music_opts = [
		'guitar'   => 'Guitare',
		'piano'    => 'Piano',
		'violin'   => 'Violon',
		'drums'    => 'Batterie',
		'vocals'   => 'Chant',
		'dj'       => 'DJ',
		'bass'     => 'Basse',
		'autre_instr' => 'Autre',
		'none'     => 'Ne joue pas'
	];
	
	$artistic_opts = [
		'photo'      => 'Photographie',
		'drawing'    => 'Dessin',
		'painting'   => 'Peinture',
		'theatre'    => 'Théâtre',
		'dance'      => 'Danse',
		'sculpture'  => 'Sculpture',
		'writing'    => 'Écriture',
		'cinema'     => 'Cinéma',
		'autre_art'  => 'Autre'
	];
	
	$outings_opts = [
		'concerts'        => 'Concerts',
		'nature'          => 'Nature',
		'museums'         => 'Musées',
		'restaurants'     => 'Restaurants',
		'cinema'          => 'Cinéma',
		'bars'            => 'Bars',
		'sports_events'   => 'Événements sportifs',
		'exhibitions'     => 'Expositions',
		'autre_sortie'    => 'Autre'
	];
	
	$cause_opts = [
		'ecology'           => 'Écologie',
		'volunteering'      => 'Bénévolat',
		'animal_rights'     => 'Droits des animaux',
		'humanitarian'      => 'Humanitaire',
		'spirituality'      => 'Spiritualité',
		'lgbtq'             => 'LGBTQ+',
		'politics'          => 'Politique',
		'health'            => 'Santé',
		'education'         => 'Éducation',
		'autre_cause'       => 'Autre'
	];
	
	$origin_opts = [
		'europe'   => 'Européenne',
		'africa'   => 'Africaine',
		'asia'     => 'Asiatique',
		'arab'     => 'Arabe',
		'americas' => 'Amérindienne',
		'oceania'  => 'Océanienne',
		'autre_origine' => 'Autre'
	];
	
	$religion_opts = [
		'croyant_non_pratiquant' => 'Croyant non pratiquant',
		'atheist'                => 'Ni religieux, ni croyant',
		'protestant'             => 'Protestant',
		'muslim'                 => 'Musulman·e',
		'christian'              => 'Chrétien·ne',
		'jewish'                 => 'Juif·ve',
		'buddhist'               => 'Bouddhiste',
		'orthodox'               => 'Orthodox',
		'hindu'                  => 'Hindou',
		'autre_religion'         => 'Autre'
	];
	
	// 2) Option definitions
	$genders    = ['male'=>'Homme','female'=>'Femme'];
	$yes_no_occ = ['yes'=>'Oui','no'=>'Non','occasionnellement'=>'Occasionnellement'];
	
	// Multi‑select options (examples—adjust as needed)
	$sports_opts = ['football'=>'Football','basketball'=>'Basketball','tennis'=>'Tennis','natation'=>'Natation','running'=>'Course à pied','cyclisme'=>'Cyclisme','yoga'=>'Yoga','crossfit'=>'Crossfit','autre_sport'=>'Autre'];
	$music_opts  = ['rock'=>'Rock','pop'=>'Pop','jazz'=>'Jazz','classical'=>'Classique','hiphop'=>'Hip-hop','electronic'=>'Électronique','metal'=>'Metal','folk'=>'Folk','blues'=>'Blues','autre_musique'=>'Autre'];
	$play_opts   = ['guitar'=>'Guitare','piano'=>'Piano','violin'=>'Violon','drums'=>'Batterie','vocals'=>'Chant','dj'=>'DJ','bass'=>'Basse','autre_instr'=>'Autre','none'=>'Ne joue pas'];
	$art_opts    = ['photo'=>'Photographie','drawing'=>'Dessin','painting'=>'Peinture','theatre'=>'Théâtre','dance'=>'Danse','sculpture'=>'Sculpture','writing'=>'Écriture','cinema'=>'Cinéma','autre_art'=>'Autre'];
	$outings    = ['concerts'=>'Concerts','nature'=>'Nature','museums'=>'Musées','restaurants'=>'Restaurants','cinema'=>'Cinéma','bars'=>'Bars','sports_events'=>'Événements sportifs','exhibitions'=>'Expositions','autre_sortie'=>'Autre'];
	$cause_opts = ['ecology'=>'Écologie','volunteering'=>'Bénévolat','animal_rights'=>'Droits des animaux','humanitarian'=>'Humanitaire','spirituality'=>'Spiritualité','lgbtq'=>'LGBTQ+','politics'=>'Politique','health'=>'Santé','education'=>'Éducation','autre_cause'=>'Autre'];
	$origin_opts= ['europe'=>'Européenne','africa'=>'Africaine','asia'=>'Asiatique','arab'=>'Arabe','americas'=>'Amérique','oceania'=>'Océanienne','autre_origine'=>'Autre'];
	$religion  = ['croyant_non_pratiquant'=>'Croyant non pratiquant','atheist'=>'Athée','muslim'=>'Musulman·e','christian'=>'Chrétien·ne','jewish'=>'Juif·ve','buddhist'=>'Bouddhiste','hindu'=>'Hindou','autre_religion'=>'Autre'];
	
	// New single‑select options
	$child_opts = ['yes'=>'Oui','no'=>'Non','shared_custody'=>'En garde partagée','planning_only'=>'En projet uniquement'];
	$pet_opts   = ['yes'=>'Oui','no'=>'Non','allergic'=>'Je suis allergique','would_like'=>'J’en voudrais'];
	$living     = ['alone'=>'Seul·e','coloc'=>'En colocation','family'=>'En famille','couple'=>'En couple'];
	$house_opts = ['owner'=>'Propriétaire','tenant'=>'Locataire','hosted'=>'Hébergé·e'];
	$reloc_opts = ['yes'=>'Oui','maybe'=>'Peut‑être','no'=>'Non'];
	$city_imp   = ['yes'=>'Oui','no'=>'Non','indifferent'=>'Indifférent·e'];
	$sector     = [''=>'— Sélectionnez —','it'=>'Tech','finance'=>'Finance','health'=>'Santé','education'=>'Enseignement','retail'=>'Commerce','hospitality'=>'Hôtellerie','manufacture'=>'Industrie','public'=>'Public','other'=>'Autre'];
	$time_type  = ['office'=>'Bureaux','catering'=>'Restauration','remote'=>'Télétravail','shift'=>'Horaires décalés','industrial'=>'Industriel','health'=>'Santé','services'=>'Services'];
	$telework   = ['yes'=>'Oui','partial'=>'Partiellement','no'=>'Non'];
	$rhythm     = ['35'=>'35h','40_50'=>'40–50h','over_50'=>'>50h','variable'=>'Variable'];
	$mobility   = ['local'=>'Locale','national'=>'Nationale','international'=>'Internationale'];
	$career_prio= ['1'=>'1 — Faible','2'=>'2','3'=>'3','4'=>'4','5'=>'5 — Très élevé'];
	$lifestyle  = ['minimal'=>'Minimaliste','moderate'=>'Modéré','comfort'=>'Confort','luxury'=>'Luxe'];
	$income     = ['under_20'=>'<20 000€','20_40'=>'20–40 000€','40_60'=>'40–60 000€','60_80'=>'60–80 000€','over_80'=>'>80 000€'];
	
	?>
	<table class="form-table">

		<!-- desc_gender -->
		<tr>
			<th><label><?php _e( 'Votre genre', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $genders as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio" name="desc_gender" value="<?php echo esc_attr($value); ?>" <?php checked( $fields['desc_gender'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- search_gender -->
		<tr>
			<th><label><?php _e( 'Vous cherchez un·e partenaire de sexe :', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $genders as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="search_gender"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['search_gender'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- desc_size -->
		<tr>
			<th><label for="desc_size"><?php _e( 'Votre taille (en cm)', 'luviya' ); ?></label></th>
			<td>
			<select name="desc_size" id="desc_size">
				<option value=""><?php _e( '— Sélectionnez —', 'luviya' ); ?></option>
				<?php
				// Génère des options de 140cm à 210cm tous les 1cm
				for ( $h = 140; $h <= 220; $h += 1 ) {
				$val = $h;
				printf(
					'<option value="%1$s"%2$s>%3$s</option>',
					esc_attr( $val ),
					selected( $fields['desc_size'], $val, false ),
					esc_html( $h )
				);
				}
				?>
			</select>
			</td>
		</tr>
	
		<!-- desc_weight -->
		<tr>
			<th><label><?php _e( 'Votre morphologie', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $weights as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="desc_weight"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['desc_weight'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- desc_style -->
		<tr>
			<th><label><?php _e( 'Style vestimentaire', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $styles as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="desc_style"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['desc_style'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- desc_tattoo -->
		<tr>
			<th><label><?php _e( 'Tatouages / piercings ?', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $yes_no as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="desc_tattoo"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['desc_tattoo'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- desc_smoker -->
		<tr>
			<th><label><?php _e( 'Fumez-vous ?', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $yes_no_occ as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="desc_smoker"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['desc_smoker'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
	
		<!-- desc_drinker -->
		<tr>
			<th><label><?php _e( 'Consommez-vous de l’alcool ?', 'luviya' ); ?></label></th>
			<td>
			<?php foreach( $yes_no_occ as $value => $label ): ?>
				<label style="margin-right:1em">
				<input type="radio"
						name="desc_drinker"
						value="<?php echo esc_attr($value); ?>"
						<?php checked( $fields['desc_drinker'], $value ); ?> />
				<?php echo esc_html($label); ?>
				</label>
			<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Sports (checkboxes) -->
		<tr>
			<th><label><?php _e( 'Quels sports pratiques-tu ou apprécies-tu ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $sports_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_sports[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_sports'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Music Styles -->
		<tr>
			<th><label><?php _e( 'Quels styles de musique écoutes-tu ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $music_styles_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_music_styles[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_music_styles'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Play Music -->
		<tr>
			<th><label><?php _e( 'Joues-tu de la musique ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $play_music_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_play_music[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_play_music'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Artistic Activities -->
		<tr>
			<th><label><?php _e( 'Pratiques-tu une activité artistique ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $artistic_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_artistic[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_artistic'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Outings -->
		<tr>
			<th><label><?php _e( 'Quelles sorties apprécies-tu ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $outings_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_outings[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_outings'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Cause Engagement -->
		<tr>
			<th><label><?php _e( 'Es-tu engagé·e dans une cause ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $cause_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_cause[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_cause'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Origin -->
		<tr>
			<th><label><?php _e( 'Origine', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $origin_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_origin[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_origin'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Religion -->
		<tr>
			<th><label><?php _e( 'Religion', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $religion_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input
							type="checkbox"
							name="desc_religion[]"
							value="<?php echo esc_attr( $key ); ?>"
							<?php checked( in_array( $key, $fields['desc_religion'], true ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Weekend Free Text -->
		<tr>
			<th><label for="desc_weekend"><?php _e( 'Décris en quelques mots ce que tu aimes faire le week‑end.', 'luviya' ); ?></label></th>
			<td>
				<textarea
					name="desc_weekend"
					id="desc_weekend"
					rows="4"
					class="large-text"
				><?php echo esc_textarea( $fields['desc_weekend'] ); ?></textarea>
			</td>
		</tr>
		
		<!-- As-tu des enfants ? -->
		<tr>
			<th><label><?php _e( 'As‑tu des enfants ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $child_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_children" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_children'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- As-tu des animaux de compagnie ? -->
		<tr>
			<th><label><?php _e( 'As‑tu des animaux de compagnie ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $pet_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_pets" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_pets'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Vis-tu seul·e ? -->
		<tr>
			<th><label><?php _e( 'Vis‑tu seul·e ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $living as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_living_alone" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_living_alone'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Situation de logement -->
		<tr>
			<th><label><?php _e( 'Situation de logement', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $house_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_housing" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_housing'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Prêt·e à déménager pour une relation sérieuse ? -->
		<tr>
			<th><label><?php _e( 'Es‑tu prêt·e à déménager pour une relation sérieuse ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $reloc_opts as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_relocation" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_relocation'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Importance de la ville/région -->
		<tr>
			<th><label><?php _e( 'Ta ville ou ta région actuelle a‑t‑elle une importance capitale pour toi ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $city_imp as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_city_importance" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_city_importance'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Métier ou secteur d’activité -->
		<tr>
			<th><label for="desc_sector"><?php _e( 'Quel est ton métier ou secteur d’activité ?', 'luviya' ); ?></label></th>
			<td>
				<select name="desc_sector" id="desc_sector">
					<?php foreach ( $sector as $key => $label ) : ?>
						<option value="<?php echo esc_attr($key); ?>" <?php selected( $fields['desc_sector'], $key ); ?>>
							<?php echo esc_html($label); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		
		<!-- Type d’amplitude horaire professionnelle -->
		<tr>
			<th><label><?php _e( 'Quel est ton type d’amplitude horaire professionnelle ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $time_type as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_time_type" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_time_type'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Télétravail possible -->
		<tr>
			<th><label><?php _e( 'Ton travail permet-il le télétravail ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $telework as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_telework" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_telework'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Rythme hebdomadaire de travail -->
		<tr>
			<th><label><?php _e( 'Quel est ton rythme hebdomadaire de travail ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $rhythm as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_weekly_rhythm" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_weekly_rhythm'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Mobilité -->
		<tr>
			<th><label><?php _e( 'Quel est ton niveau de mobilité professionnelle ou personnelle ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $mobility as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_mobility" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_mobility'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Priorité carrière -->
		<tr>
			<th><label><?php _e( 'Sur une échelle de 1 à 5, quelle priorité donnes-tu à ta carrière ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $career_prio as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_career_priority" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_career_priority'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Style de vie général -->
		<tr>
			<th><label><?php _e( 'Quel est ton style de vie général ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $lifestyle as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_lifestyle" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_lifestyle'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>
		
		<!-- Revenu annuel -->
		<tr>
			<th><label><?php _e( 'Quelle est ta tranche de revenu annuel ?', 'luviya' ); ?></label></th>
			<td>
				<?php foreach ( $income as $key => $label ) : ?>
					<label style="margin-right:1em">
						<input type="radio" name="desc_income_bracket" value="<?php echo esc_attr($key); ?>" <?php checked( $fields['desc_income_bracket'], $key ); ?> />
						<?php echo esc_html($label); ?>
					</label>
				<?php endforeach; ?>
			</td>
		</tr>

	</table>
	<?php
 }
add_action( 'show_user_profile', 'luviya_onboarding_profile_fields' );
add_action( 'edit_user_profile', 'luviya_onboarding_profile_fields' );
 
/**
 * Save all onboarding fields (single, multi, free‑text).
 *
 * @param int $user_id User ID being updated.
 * @return false|void  False if unauthorized.
 */
function luviya_save_onboarding_fields( $user_id ) {
	// Capability check
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	// 1) Single‑value
	$single = [
		'desc_gender','search_gender','desc_size','desc_weight','desc_style','desc_tattoo','desc_smoker','desc_drinker',
		'desc_children','desc_pets','desc_living_alone','desc_housing','desc_relocation','desc_city_importance',
		'desc_sector','desc_time_type','desc_telework','desc_weekly_rhythm','desc_mobility','desc_career_priority','desc_lifestyle','desc_income_bracket',
	];
	foreach ( $single as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_user_meta( $user_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		} else {
			delete_user_meta( $user_id, $key );
		}
	}

	// 2) Multi‑select arrays
	$multi = [ 'desc_sports','desc_music_styles','desc_play_music','desc_artistic','desc_outings','desc_cause','desc_origin','desc_religion' ];
	foreach ( $multi as $key ) {
		if ( ! empty( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ) {
			$clean = array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) );
			update_user_meta( $user_id, $key, $clean );
		} else {
			delete_user_meta( $user_id, $key );
		}
	}

	// 3) Free‑text weekend
	if ( isset( $_POST['desc_weekend'] ) ) {
		update_user_meta( $user_id, 'desc_weekend', sanitize_textarea_field( wp_unslash( $_POST['desc_weekend'] ) ) );
	} else {
		delete_user_meta( $user_id, 'desc_weekend' );
	}
}
add_action( 'personal_options_update',   'luviya_save_onboarding_fields' );
add_action( 'edit_user_profile_update',  'luviya_save_onboarding_fields' );