SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
('1', 'App\\Models\\User', '1'),
('2', 'App\\Models\\User', '2');
COMMIT;
