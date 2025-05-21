SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_templates` (`id`, `slug`, `name`, `description`, `photo`, `photo_thumbnail`, `settings`, `supported_languages`, `is_active`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'default', '{\"ar\":\"\\u0627\\u0644\\u0642\\u0627\\u0644\\u0628 \\u0627\\u0644\\u0627\\u0641\\u062a\\u0631\\u0627\\u0636\\u0649\",\"en\":\"Default Template\"}', '{\"ar\":\"\\u0627\\u0644\\u0642\\u0627\\u0644\\u0628 \\u0627\\u0644\\u0627\\u0641\\u062a\\u0631\\u0627\\u0636\\u0649\",\"en\":\"Default Template\"}', 'builder-template/2025-05/img-682d7f64a9278.webp', 'builder-template/2025-05/img-682d7f64a9278_thumb.webp', '{\"fonts\":{\"primary_ar\":\"Tajawal, sans-serif\",\"heading_ar\":\"Tajawal, sans-serif\",\"base_size_ar\":\"16px\"},\"spacing\":{\"base_ar\":\"1rem\",\"section_ar\":\"1rem\"},\"colors\":{\"primary\":\"#007bff\",\"secondary\":\"#6c757d\",\"accent\":\"#fd7e14\",\"background\":\"#ffffff\",\"text\":\"#212529\"}}', '[\"ar\"]', '1', '0', '2025-05-21 10:23:17', '2025-05-21 10:23:17', NULL);
COMMIT;
