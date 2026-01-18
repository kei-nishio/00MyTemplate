<?php
// ! 管理画面のユーザープロフィール項目をカスタマイズする
function custom_user_profile_labels($user)
{
?>
	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function() {
			// "名"のラベルを"ふりがな"に変更
			const firstNameLabel = document.querySelector('label[for="first_name"]');
			if (firstNameLabel) {
				firstNameLabel.textContent = "ふりがな (必須)";
			}
			// "姓"のラベルを"会社名"に変更
			const lastNameLabel = document.querySelector('label[for="last_name"]');
			if (lastNameLabel) {
				lastNameLabel.textContent = "会社名 (必須)";
			}
		});
	</script>
<?php
}
add_action('admin_footer-user-edit.php', 'custom_user_profile_labels');
add_action('admin_footer-profile.php', 'custom_user_profile_labels');
add_action('admin_footer-user-new.php', 'custom_user_profile_labels');
