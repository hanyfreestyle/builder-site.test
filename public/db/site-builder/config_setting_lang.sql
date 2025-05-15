SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `config_setting_lang` (`id`, `setting_id`, `locale`, `name`, `closed_mass`, `meta_des`, `whatsapp_des`, `schema_address`, `schema_city`) VALUES
('1', '1', 'ar', 'اسم الموقع', 'عذرا جارى اجراء بعض التحديثات 
سنعود قريبا', 'اسم الموقع', 'اريد الاستفسار عن', 'التجمع الخامس', 'القاهرة الجديدة'),
('2', '1', 'en', 'Web Site Name', 'Sorry, some updates are being made
We will be back soon', 'Web Site Name', 'I want to inquire about', 'streetAddress', 'streetAddress');
COMMIT;
