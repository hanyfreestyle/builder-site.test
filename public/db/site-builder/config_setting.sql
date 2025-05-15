SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `config_setting` (`id`, `web_url`, `web_status`, `web_status_date`, `switch_lang`, `lang`, `users_login`, `users_register`, `users_forget_password`, `phone_num`, `whatsapp_num`, `phone_call`, `whatsapp_send`, `email`, `def_url`, `facebook`, `youtube`, `twitter`, `instagram`, `linkedin`, `google_api`, `telegram_send`, `telegram_phone`, `telegram_group`, `schema_type`, `schema_lat`, `schema_long`, `schema_postal_code`, `schema_country`) VALUES
('1', 'https://on-fire.test/admin/site-settings', '1', NULL, '0', NULL, '0', NULL, NULL, '01221-000-002', '01221-000-002', '01221000002', '201221000002', 'info@web-site-name.com', 'https://web-site-name.com', 'https://www.facebook.com/', 'https://www.youtube.com', 'https://www.x.com/', 'https://www.Instagram.com/', 'https://www.linkedin.com/', NULL, '0', NULL, NULL, 'Store', NULL, NULL, '21111', 'EG');
COMMIT;
