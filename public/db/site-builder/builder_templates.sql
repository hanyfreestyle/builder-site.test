SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_templates` (`id`, `name`, `slug`, `description`, `thumbnail`, `settings`, `supported_languages`, `is_active`, `is_default`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'Restaurant', 'restaurant', 'Perfect for restaurants, cafes, and food-related businesses', 'templates/restaurant-thumbnail.jpg', '{\"colors\":{\"primary\":\"#d4af37\",\"secondary\":\"#222222\",\"accent\":\"#e74c3c\",\"background\":\"#ffffff\",\"text\":\"#333333\"},\"fonts\":{\"primary\":\"Roboto, sans-serif\",\"heading\":\"Playfair Display, serif\",\"base_size\":\"16px\"},\"spacing\":{\"base\":\"1rem\",\"section\":\"3rem\"}}', '[\"en\",\"ar\"]', '1', '1', '2025-05-20 19:15:16', '2025-05-20 19:15:16', NULL);
COMMIT;
