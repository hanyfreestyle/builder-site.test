SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `builder_template` (`id`, `slug`, `name`, `des`, `photo`, `photo_thumbnail`, `is_active`) VALUES
('1', 'template', '{\"ar\":\"\\u0642\\u0627\\u0644\\u0628 1\",\"en\":\"Template 1\"}', '{\"ar\":null,\"en\":null}', NULL, NULL, '1'),
('2', 'template-2', '{\"ar\":\"\\u0642\\u0627\\u0644\\u0628 2\",\"en\":\"Template 2\"}', '{\"ar\":null,\"en\":null}', NULL, NULL, '1');
COMMIT;
