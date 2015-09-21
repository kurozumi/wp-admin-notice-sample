<?php
/*
Plugin Name: WP Admin Notice Sample
Version: 0.1-alpha
Description: WP Admin Notice Sample
Author: kurozumi
Author URI: http://a-zumi.net
Plugin URI: http://a-zumi.net
Text Domain: wp-admin-notice-sample
Domain Path: /languages
*/

$wp_ans = new WP_Admin_Notice_Sample;
$wp_ans->register();

class WP_Admin_Notice_Sample
{
	const NAME = "管理画面通知サンプル";
	
	public function register()
	{
		add_action('plugins_loaded', array($this, 'plugins_loaded'));
	}
	
	public function plugins_loaded()
	{
		add_action('admin_menu', array($this, 'add_menu_page'));
		add_action('admin_init', array($this, 'admin_init'));
		add_action('admin_notices', array($this, 'admin_notices'));
	}
	public function add_menu_page()
	{
		add_menu_page(
			__(self::NAME, 'wp-admin-notice-sample'),
			__(self::NAME, 'wp-admin-notice-sample'),
			'manage_options', 
			__FILE__,
			array($this, 'print_options_page')
		);
	}
	
	public function print_options_page()
	{		
		?>
		<div class="wrap">
			<h2><?php echo esc_html(self::NAME);?></h2>
			<form action="" method="post">
				<?php wp_nonce_field('message_sample'); ?>
				<table class="form-table" id="post-type">
					<tr valign="top">
						<th scope="row"><label for="inputtext">通知タイプを選択</label></th>
						<td>
							<select name="notice"　class="regular-text">
								<option value="updated"><?php _e('Update'); ?></option>
								<option value="error"><?php _e('Error'); ?></option>
							</select>
						</td>
					</tr>
				</table>
				<p class="submit"><input type="submit" class="button-primary" value="実行" /></p>
			</form>
			
		</div>
		<?php
	}
	
	public function admin_init()
	{
		if (isset($_REQUEST['notice']))
		{
			if(check_admin_referer('message_sample'))
			{
				set_transient('wp-admin-notice-sample', $_REQUEST['notice'], 10);
				
				wp_safe_redirect(menu_page_url(__FILE__, false));	
			}
		}
	}
	
	public function admin_notices()
	{
		if ($notice = get_transient('wp-admin-notice-sample'))
		{			
			?>
			<div id="message" class="<?php echo esc_html($notice); ?> notice is-dismissible">
				<p>ここにメッセージが表示されます。</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">この通知を非表示にする</span>
				</button>
			</div>
			<?php
		}
	}
}

