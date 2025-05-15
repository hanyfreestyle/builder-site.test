SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_template_layout` (`id`, `template_id`, `type`, `slug`, `name`, `photo`, `photo_thumbnail`, `is_default`, `is_active`, `position`) VALUES
('1', '1', 'footer', 'footer-1', '{\"ar\":\"\\u0641\\u0648\\u062a\\u0631 1\",\"en\":\"Footer 1\"}', NULL, NULL, '1', '1', '0'),
('2', '1', 'footer', 'footer-2', '{\"ar\":\"\\u0641\\u0648\\u062a\\u0631 2\",\"en\":\"Footer 2\"}', NULL, NULL, '0', '1', '0'),
('3', '2', 'footer', 'footer-1', '{\"ar\":\"\\u0641\\u0648\\u062a\\u0631 1\",\"en\":\"Footer 1\"}', NULL, NULL, '1', '1', '0');
COMMIT;
