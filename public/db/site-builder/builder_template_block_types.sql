SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_template_block_types` (`id`, `template_id`, `block_type_id`, `view_versions`, `default_view_version`, `is_enabled`, `sort_order`, `created_at`, `updated_at`) VALUES
('1', '1', '1', '[\"default\",\"centered\",\"with-video\"]', 'default', '1', '10', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('2', '1', '2', '[\"default\",\"boxed\",\"icon-top\"]', 'default', '1', '20', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('3', '1', '3', '[\"default\",\"boxed\",\"full-width\"]', 'default', '1', '30', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('4', '1', '4', '[\"default\",\"masonry\",\"slider\"]', 'default', '1', '40', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('5', '1', '5', '[\"default\",\"full-width\",\"centered\"]', 'default', '1', '50', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('6', '1', '6', '[\"default\"]', 'default', '1', '10', '2025-05-20 19:15:16', '2025-05-20 19:15:16'),
('7', '1', '7', '[\"default\"]', 'default', '1', '10', '2025-05-20 19:15:16', '2025-05-20 19:15:16');
COMMIT;
