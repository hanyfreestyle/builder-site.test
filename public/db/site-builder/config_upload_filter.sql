SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `config_upload_filter` (`id`, `name`, `type`, `convert_state`, `quality_val`, `width`, `height`, `canvas_back`, `crop_aspect_ratio`, `greyscale`, `flip_state`, `flip_v`, `blur`, `blur_size`, `pixelate`, `pixelate_size`, `text_state`, `text_print`, `font_size`, `font_path`, `font_color`, `font_opacity`, `text_position`, `watermark_state`, `watermark_img`, `watermark_position`, `state`, `notes`, `is_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
('1', 'NoEdit', '1', '1', '85', '100', '100', '#ffffff', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', '{\"ar\":null,\"en\":null}', '1', '2023-10-16 04:42:57', '2025-04-06 00:47:41', NULL),
('2', 'DefPhoto', '4', '1', '85', '800', '420', '#ffffff', '16:9', '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2023-10-16 04:42:57', '2025-04-04 05:47:09', NULL),
('3', 'Profile', '4', '1', '99', '400', '400', '#FFFFFF', '1:1', '1', '1', '0', '0', '5', '0', '12', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2024-11-26 02:39:25', '2025-04-05 06:03:12', NULL),
('4', 'icon-512-256', '4', '1', '85', '512', '512', '#FFFFFF', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-01-13 17:45:44', '2025-01-13 18:15:31', NULL),
('5', 'icon-256-128', '4', '1', '85', '256', '256', '#FFFFFF', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-01-13 18:14:03', '2025-01-13 18:15:11', NULL),
('6', 'icon-128-64', '4', '1', '85', '128', '128', '#FFFFFF', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-01-13 18:14:34', '2025-01-13 18:14:34', NULL),
('7', 'icon-64-32', '4', '1', '85', '64', '64', '#FFFFFF', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-01-13 18:14:53', '2025-01-13 18:14:53', NULL),
('8', 'card-800-600', '4', '1', '85', '800', '600', '#FFFFFF', NULL, '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-01-13 18:24:15', '2025-01-13 18:44:19', NULL),
('9', 'تجربة ملونة', '5', '1', '85', '900', '600', '#850c0c', '16:9', '0', '0', '0', '0', '0', '0', '5', '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, '0', NULL, '1', '2025-04-04 05:49:51', '2025-04-04 05:50:14', NULL);
COMMIT;
