SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
('1', 'super_admin', 'web', '2025-03-11 07:25:33', '2025-03-11 07:25:33'),
('2', 'panel_user', 'web', '2025-03-11 07:40:44', '2025-03-11 07:40:44');
COMMIT;
