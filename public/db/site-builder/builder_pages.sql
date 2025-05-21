SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_pages` (`id`, `template_id`, `use_default_template`, `title`, `slug`, `description`, `meta_tags`, `translations`, `is_homepage`, `is_active`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', '1', '0', 'Welcome to Our Restaurant', 'home', 'Our delicious food and cozy atmosphere will make your dining experience unforgettable.', '{\"title\":\"Best Restaurant in Town | Fine Dining Experience\",\"description\":\"Experience the finest cuisine in town with our award-winning chefs and cozy atmosphere.\",\"keywords\":\"restaurant, fine dining, cuisine, food, dinner\",\"robots\":\"index, follow\"}', NULL, '1', '1', '0', '2025-05-20 19:15:16', '2025-05-20 19:15:16', NULL);
COMMIT;
