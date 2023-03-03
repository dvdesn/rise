					<div class="logo">
						<a href="<?php echo esc_url( home_url('/') ); ?>"><?php
							echo !empty($GRACE_CHURCH_GLOBALS['logo'])
								? '<img src="'.esc_url($GRACE_CHURCH_GLOBALS['logo']).'" class="logo_main" alt="'.esc_attr__('img', 'grace-church').'">'
								: ''; 
							grace_church_show_layout( $GRACE_CHURCH_GLOBALS['logo_text']
								? '<div class="logo_text">'.($GRACE_CHURCH_GLOBALS['logo_text']).'</div>'
								: '');
							grace_church_show_layout( $GRACE_CHURCH_GLOBALS['logo_slogan']
								? '<br><div class="logo_slogan">' . esc_html($GRACE_CHURCH_GLOBALS['logo_slogan']) . '</div>'
								: '');
						?></a>
					</div>