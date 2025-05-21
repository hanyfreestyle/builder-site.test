SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
('1', 'web_site_settings_web::setting::web::site::settings', 'web', '2025-05-16 06:38:56', '2025-05-16 06:38:56'),
('2', 'web_models_settings_web::setting::web::site::settings', 'web', '2025-05-16 06:38:56', '2025-05-16 06:38:56'),
('3', 'default_photo_web::setting::web::site::settings', 'web', '2025-05-16 06:38:56', '2025-05-16 06:38:56'),
('4', 'web_privacy_web::setting::web::site::settings', 'web', '2025-05-16 06:38:56', '2025-05-16 06:38:56'),
('5', 'upload_filter_web::setting::web::site::settings', 'web', '2025-05-16 06:38:56', '2025-05-16 06:38:56'),
('6', 'meta_tag_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('7', 'create_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('8', 'update_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('9', 'update_slug_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('10', 'delete_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('11', 'delete_any_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('12', 'restore_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('13', 'restore_any_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('14', 'reorder_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('15', 'force_delete_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53'),
('16', 'force_delete_any_web::setting::web::site::settings', 'web', '2025-05-16 07:07:53', '2025-05-16 07:07:53');
COMMIT;
