SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_block_types` (`id`, `name`, `slug`, `description`, `icon`, `category`, `schema`, `is_active`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', '{\"ar\":\"\\u062a\\u062c\\u0631\\u0628\\u0629 \\u0639\\u0627\\u062f\\u064a\\u0629 \",\"en\":\"Test All\"}', 'test-all', NULL, NULL, 'Basic', '[{\"type\":\"text\",\"name\":\"h1\",\"width\":\"full\",\"required\":false,\"translatable\":false,\"label\":{\"ar\":\"\\u0627\\u0644\\u0639\\u0646\\u0648\\u0627\\u0646 \\u0627\\u0644\\u0631\\u0626\\u064a\\u0633\\u064a\",\"en\":\"Main Title\"},\"help\":{\"ar\":null,\"en\":null},\"default\":null,\"options\":[]}]', '1', '0', '2025-05-21 11:15:22', '2025-05-21 11:15:22', NULL);
COMMIT;
