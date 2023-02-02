CREATE TABLE IF NOT EXISTS `alp_bancos` (
  `id` int(10) unsigned NOT NULL  AUTO_INCREMENT,
  `nombre_banco` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_banco` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo_banco` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_registro` int(11) NOT NULL DEFAULT 1,
  `id_user` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




INSERT INTO `alp_bancos` (`id`, `nombre_banco`, `descripcion_banco`, `codigo_banco`, `estado_registro`, `id_user`, `created_at`, `updated_at`, `deleted_at`) VALUES
  (1, 'Banco de Venezuela, S.A.C.A.', 'Banco de Venezuela, S.A.C.A.', '0102', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (2, 'Venezolano de Crédito', 'Venezolano de Crédito', '0104', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (3, 'Mercantil', 'Mercantil', '0105', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (4, 'Provincial', 'Provincial', '0108', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (5, 'Bancaribe', 'Bancaribe', '0114', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (6, 'Exterior', 'Exterior', '0115', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (7, 'Occidental de Descuento', 'Occidental de Descuento', '0116', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (8, 'Banco Caroní', 'Banco Caroní', '0128', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (9, 'Banesco', 'Banesco', '0134', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (10, 'Banco Plaza', 'Banco Plaza', '0138', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (11, 'BFC Banco Fondo Común', 'BFC Banco Fondo Común', '0151', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (12, '100% Banco', '100% Banco', '0156', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (13, 'Del Sur', 'Del Sur', '0157', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (14, 'Banco del Tesoro', 'Banco del Tesoro', '0163', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (15, 'Banco Agrícola de Venezuela', 'Banco Agrícola de Venezuela', '0166', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (16, 'Bancrecer', 'Bancrecer', '0168', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (17, 'Mi Banco', 'Mi Banco', '0169', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (18, 'Bancamiga', 'Bancamiga', '0172', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (19, 'Banplus', 'Banplus', '0174', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (20, 'Bicentenario del Pueblo', 'Bicentenario del Pueblo', '0175', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (21, 'Banfanb', 'Banfanb', '0177', 1, 1, '2023-01-28 05:57:37', NULL, NULL),
  (22, 'BNC Nacional de Crédito', 'BNC Nacional de Crédito', '0191', 1, 1, '2023-01-28 05:57:37', NULL, NULL);



  