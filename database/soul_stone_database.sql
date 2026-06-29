-- Soul Stone database schema for MySQL / phpMyAdmin
-- Import this file in phpMyAdmin to create the first backend database.
-- Recommended MySQL version: 5.7+ / 8.0+

CREATE DATABASE IF NOT EXISTS `soul_stone`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `soul_stone`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `custom_design_items`;
DROP TABLE IF EXISTS `custom_designs`;
DROP TABLE IF EXISTS `cart_items`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `product_stones`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `product_variants`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `custom_bead_options`;
DROP TABLE IF EXISTS `stones`;
DROP TABLE IF EXISTS `product_categories`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `password_resets`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `first_name` VARCHAR(80) DEFAULT NULL,
  `last_name` VARCHAR(80) DEFAULT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `role` ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
  `status` ENUM('active', 'inactive', 'blocked') NOT NULL DEFAULT 'active',
  `email_verified_at` DATETIME DEFAULT NULL,
  `last_login_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_users_username` (`username`),
  UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `token_hash` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_password_resets_user_id` (`user_id`),
  CONSTRAINT `fk_password_resets_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `label` VARCHAR(60) DEFAULT 'Shipping',
  `recipient_name` VARCHAR(160) NOT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `address_line1` VARCHAR(190) NOT NULL,
  `address_line2` VARCHAR(190) DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state_region` VARCHAR(100) DEFAULT NULL,
  `postal_code` VARCHAR(30) DEFAULT NULL,
  `country` VARCHAR(100) NOT NULL DEFAULT 'Australia',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_addresses_user_id` (`user_id`),
  CONSTRAINT `fk_user_addresses_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_product_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_en` VARCHAR(120) NOT NULL,
  `name_zh` VARCHAR(120) DEFAULT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `color_group` VARCHAR(60) DEFAULT NULL,
  `hex_color` VARCHAR(20) DEFAULT NULL,
  `meaning` TEXT DEFAULT NULL,
  `care_note` TEXT DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_stones_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `custom_bead_options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `stone_id` BIGINT UNSIGNED NOT NULL,
  `size_mm` TINYINT UNSIGNED NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock_qty` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_custom_bead_stone_size` (`stone_id`, `size_mm`),
  CONSTRAINT `fk_custom_bead_options_stone`
    FOREIGN KEY (`stone_id`) REFERENCES `stones` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` BIGINT UNSIGNED DEFAULT NULL,
  `main_stone_id` BIGINT UNSIGNED DEFAULT NULL,
  `sku` VARCHAR(80) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `name` VARCHAR(180) NOT NULL,
  `theme` VARCHAR(100) DEFAULT NULL,
  `short_description` TEXT DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `base_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('draft', 'active', 'archived') NOT NULL DEFAULT 'active',
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_products_sku` (`sku`),
  UNIQUE KEY `uniq_products_slug` (`slug`),
  KEY `idx_products_category_id` (`category_id`),
  KEY `idx_products_main_stone_id` (`main_stone_id`),
  CONSTRAINT `fk_products_category`
    FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_products_main_stone`
    FOREIGN KEY (`main_stone_id`) REFERENCES `stones` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_variants` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `sku` VARCHAR(100) NOT NULL,
  `bead_size_mm` TINYINT UNSIGNED DEFAULT NULL,
  `bracelet_length_cm` DECIMAL(4,1) DEFAULT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock_qty` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_product_variants_sku` (`sku`),
  KEY `idx_product_variants_product_id` (`product_id`),
  CONSTRAINT `fk_product_variants_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(180) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_images_product_id` (`product_id`),
  CONSTRAINT `fk_product_images_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_stones` (
  `product_id` BIGINT UNSIGNED NOT NULL,
  `stone_id` BIGINT UNSIGNED NOT NULL,
  `role` ENUM('main', 'accent') NOT NULL DEFAULT 'accent',
  PRIMARY KEY (`product_id`, `stone_id`),
  CONSTRAINT `fk_product_stones_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_product_stones_stone`
    FOREIGN KEY (`stone_id`) REFERENCES `stones` (`id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `carts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `session_token` VARCHAR(190) DEFAULT NULL,
  `status` ENUM('active', 'converted', 'abandoned') NOT NULL DEFAULT 'active',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_carts_user_id` (`user_id`),
  KEY `idx_carts_session_token` (`session_token`),
  CONSTRAINT `fk_carts_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `custom_designs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `name` VARCHAR(180) NOT NULL DEFAULT 'Custom Bracelet',
  `bracelet_length_cm` DECIMAL(4,1) NOT NULL DEFAULT 18.0,
  `used_length_cm` DECIMAL(4,1) NOT NULL DEFAULT 0.0,
  `base_price` DECIMAL(10,2) NOT NULL DEFAULT 28.00,
  `total_price` DECIMAL(10,2) NOT NULL DEFAULT 28.00,
  `preview_json` JSON DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_custom_designs_user_id` (`user_id`),
  CONSTRAINT `fk_custom_designs_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `custom_design_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `custom_design_id` BIGINT UNSIGNED NOT NULL,
  `stone_id` BIGINT UNSIGNED DEFAULT NULL,
  `item_type` ENUM('stone', 'charm', 'spacer') NOT NULL DEFAULT 'stone',
  `name` VARCHAR(150) NOT NULL,
  `name_zh` VARCHAR(150) DEFAULT NULL,
  `size_mm` TINYINT UNSIGNED DEFAULT NULL,
  `color_hex` VARCHAR(20) DEFAULT NULL,
  `symbol` VARCHAR(20) DEFAULT NULL,
  `position_angle` DECIMAL(7,2) DEFAULT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `sort_order` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_custom_design_items_design_id` (`custom_design_id`),
  KEY `idx_custom_design_items_stone_id` (`stone_id`),
  CONSTRAINT `fk_custom_design_items_design`
    FOREIGN KEY (`custom_design_id`) REFERENCES `custom_designs` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_custom_design_items_stone`
    FOREIGN KEY (`stone_id`) REFERENCES `stones` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cart_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cart_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED DEFAULT NULL,
  `variant_id` BIGINT UNSIGNED DEFAULT NULL,
  `custom_design_id` BIGINT UNSIGNED DEFAULT NULL,
  `item_name` VARCHAR(180) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `item_snapshot` JSON DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cart_items_cart_id` (`cart_id`),
  KEY `idx_cart_items_product_id` (`product_id`),
  KEY `idx_cart_items_variant_id` (`variant_id`),
  KEY `idx_cart_items_custom_design_id` (`custom_design_id`),
  CONSTRAINT `fk_cart_items_cart`
    FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_cart_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_cart_items_variant`
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_cart_items_custom_design`
    FOREIGN KEY (`custom_design_id`) REFERENCES `custom_designs` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED DEFAULT NULL,
  `order_number` VARCHAR(80) NOT NULL,
  `status` ENUM('pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled', 'refunded') NOT NULL DEFAULT 'pending',
  `customer_email` VARCHAR(190) NOT NULL,
  `customer_name` VARCHAR(180) DEFAULT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `shipping_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tax_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` CHAR(3) NOT NULL DEFAULT 'AUD',
  `shipping_address_json` JSON DEFAULT NULL,
  `payment_provider` VARCHAR(80) DEFAULT NULL,
  `payment_reference` VARCHAR(190) DEFAULT NULL,
  `paid_at` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_orders_order_number` (`order_number`),
  KEY `idx_orders_user_id` (`user_id`),
  CONSTRAINT `fk_orders_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED DEFAULT NULL,
  `variant_id` BIGINT UNSIGNED DEFAULT NULL,
  `custom_design_id` BIGINT UNSIGNED DEFAULT NULL,
  `item_name` VARCHAR(180) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `line_total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `item_snapshot` JSON DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order_id` (`order_id`),
  CONSTRAINT `fk_order_items_order`
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_order_items_variant`
    FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
    ON DELETE SET NULL,
  CONSTRAINT `fk_order_items_custom_design`
    FOREIGN KEY (`custom_design_id`) REFERENCES `custom_designs` (`id`)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_categories` (`id`, `name`, `slug`, `description`, `sort_order`) VALUES
  (1, 'Collection Gallery', 'collection-gallery', 'Signature intention pieces and handmade drops.', 1),
  (2, 'New Arrivals', 'new-arrivals', 'Newly added bracelet designs.', 2),
  (3, 'Gift Ideas', 'gift-ideas', 'Gift-ready bracelet sets and custom options.', 3);

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `first_name`, `role`, `status`, `email_verified_at`) VALUES
  (1, 'leo', 'leohu890@gmail.com', '$2y$12$KCGv5hh6pVt3ZeIzEChwWOicWz/oswTULqc3ROMfXoPnjkig1UThi', 'leo', 'admin', 'active', NOW()),
  (2, 'Sylvia', 'guo040129@163.com', '$2y$12$DeCHsi3dIl7YMkMFIzwvwO7aiQ3no9M8KSO/1LeGNUdZptsLKj38y', 'Sylvia', 'admin', 'active', NOW());

INSERT INTO `stones` (`id`, `name_en`, `name_zh`, `slug`, `color_group`, `hex_color`, `meaning`, `care_note`, `image_path`) VALUES
  (1, 'Amethyst', '紫水晶', 'amethyst', 'purple', '#8c73b4', 'Calm, clarity, focus and wisdom.', 'Keep dry and clean gently with a soft cloth.', 'assets/stone-amethyst.png'),
  (2, 'Rose Quartz', '粉晶', 'rose-quartz', 'pink', '#e8b8bd', 'Self-love, tenderness and compassion.', 'Avoid chemicals, perfume and long water exposure.', 'assets/stone-rose-quartz.png'),
  (3, 'Obsidian', '黑曜石', 'obsidian', 'black', '#151313', 'Protection, grounding and boundaries.', 'Store separately to avoid scratches.', 'assets/stone-obsidian.png'),
  (4, 'Moonstone', '月光石', 'moonstone', 'white', '#dfe8f2', 'Intuition, balance and new beginnings.', 'Avoid impact and strong chemicals.', 'assets/stone-moonstone.png'),
  (5, 'Clear Quartz', '白水晶', 'clear-quartz', 'white', '#f4f4ef', 'Clarity, amplification and clean energy.', 'Wipe with a soft dry cloth after wear.', 'assets/stone-clear-quartz.png'),
  (6, 'Pink Cat''s Eye', '粉猫眼', 'pink-catseye', 'pink', '#e8b8bd', 'Charm, confidence and soft glow.', 'Keep dry and avoid lotions.', 'assets/stone-pink-catseye.png'),
  (7, 'White Cat''s Eye', '白猫眼', 'white-catseye', 'white', '#f1efe8', 'Purity, direction and daily protection.', 'Store in a pouch when not worn.', 'assets/stone-white-catseye.png'),
  (8, 'Aquamarine', '海蓝宝', 'aquamarine', 'blue', '#b9dfe6', 'Calm communication, courage and fresh mindset.', 'Avoid heat and harsh cleaners.', 'assets/stone-aquamarine.png'),
  (9, 'Silver Obsidian', '银曜石', 'silver-obsidian', 'black', '#4a4648', 'Insight, protection and inner strength.', 'Clean gently and keep away from abrasive surfaces.', 'assets/stone-silver-obsidian.png'),
  (10, 'White Rutilated Quartz', '白发晶', 'white-rutilated-quartz', 'white', '#f5f3ed', 'Clarity, renewal and focused confidence.', 'Use white quartz image as a placeholder until real photos are ready.', 'assets/stone-clear-quartz.png');

INSERT INTO `custom_bead_options` (`stone_id`, `size_mm`, `price`, `stock_qty`) VALUES
  (1, 6, 6.00, 100), (1, 8, 8.00, 100), (1, 10, 11.00, 100),
  (2, 6, 5.00, 100), (2, 8, 7.00, 100), (2, 10, 10.00, 100),
  (3, 6, 6.00, 100), (3, 8, 8.00, 100), (3, 10, 11.00, 100),
  (4, 6, 6.00, 100), (4, 8, 8.00, 100), (4, 10, 11.00, 100),
  (5, 6, 5.00, 100), (5, 8, 7.00, 100), (5, 10, 10.00, 100),
  (6, 6, 5.00, 100), (6, 8, 7.00, 100), (6, 10, 10.00, 100),
  (7, 6, 5.00, 100), (7, 8, 7.00, 100), (7, 10, 10.00, 100),
  (8, 6, 6.00, 100), (8, 8, 8.00, 100), (8, 10, 11.00, 100),
  (9, 6, 6.00, 100), (9, 8, 8.00, 100), (9, 10, 11.00, 100),
  (10, 6, 5.00, 100), (10, 8, 7.00, 100), (10, 10, 10.00, 100);

INSERT INTO `products` (`id`, `category_id`, `main_stone_id`, `sku`, `slug`, `name`, `theme`, `short_description`, `description`, `base_price`, `featured_image`, `is_featured`) VALUES
  (1, 1, 4, 'SS-TMB-001', 'transformation-moonstone-bracelet', 'Transformation Moonstone Bracelet', 'Transformation', 'Moonstone, amethyst and clear quartz with a celestial charm.', 'A soft luminous bracelet designed for personal growth and inner renewal.', 49.00, 'assets/collection-bracelets.png', 1),
  (2, 1, 2, 'SS-SLRQ-001', 'self-love-rose-quartz-bracelet', 'Self Love Rose Quartz Bracelet', 'Self Love', 'Soft rose quartz and moonstone tones for tenderness.', 'A gentle everyday bracelet for heart-led confidence and softness.', 45.00, 'assets/collection-bracelets.png', 1),
  (3, 1, 3, 'SS-POB-001', 'protection-obsidian-bracelet', 'Protection Obsidian Bracelet', 'Protection', 'Obsidian and clear quartz for grounding and boundaries.', 'A darker protection-led piece with obsidian as the central stone.', 48.00, 'assets/obsidian-preview.png', 1),
  (4, 1, 1, 'SS-FAB-001', 'focus-amethyst-bracelet', 'Focus Amethyst Bracelet', 'Focus', 'Amethyst and clear quartz for study and clarity.', 'A calm purple-toned bracelet for focus, studying and mental quiet.', 46.00, 'assets/collection-bracelets.png', 0),
  (5, 1, 4, 'SS-NBMB-001', 'new-beginning-moonstone-bracelet', 'New Beginning Moonstone Bracelet', 'New Beginning', 'A luminous piece for graduation, moving and fresh starts.', 'A bright moonstone-led bracelet for new work, moving home or any fresh chapter.', 49.00, 'assets/hero-bracelet.png', 0),
  (6, 1, 5, 'SS-CQCB-001', 'clear-quartz-charm-bracelet', 'Clear Quartz Charm Bracelet', 'Focus', 'A minimal bracelet for clarity and intention stacking.', 'A clean clear quartz design for simple daily wear.', 42.00, 'assets/stone-clear-quartz.png', 0),
  (7, 2, 8, 'SS-ACB-001', 'aquamarine-calm-bracelet', 'Aquamarine Calm Bracelet', 'New Beginning', 'Pale aquamarine for quiet courage and communication.', 'A blue-toned bracelet direction for calm communication and gentle courage.', 52.00, 'assets/stone-aquamarine.png', 0),
  (8, 2, 6, 'SS-PCGB-001', 'pink-catseye-glow-bracelet', 'Pink Cat''s Eye Glow Bracelet', 'Self Love', 'Pink cat''s eye beads with a silky confident glow.', 'A luminous pink cat''s eye bracelet for softness with a little shine.', 44.00, 'assets/stone-pink-catseye.png', 0),
  (9, 2, 9, 'SS-SOIB-001', 'silver-obsidian-insight-bracelet', 'Silver Obsidian Insight Bracelet', 'Protection', 'Silver obsidian for reflection and inner strength.', 'A reflective silver obsidian piece for grounding, insight and steadiness.', 54.00, 'assets/stone-silver-obsidian.png', 0),
  (10, 3, 4, 'SS-NCGS-001', 'new-chapter-gift-set', 'New Chapter Gift Set', 'Gift', 'A moonstone bracelet with a default intention card.', 'A gift-ready set for birthdays, graduations and fresh starts.', 68.00, 'assets/hero-bracelet.png', 0),
  (11, 3, 5, 'SS-CIGC-001', 'custom-intention-gift-card', 'Custom Intention Gift Card', 'Gift', 'A custom design option for a personal intention-led gift.', 'A flexible custom design option for customers who want a more personal piece.', 75.00, 'assets/about-studio.png', 0),
  (12, 3, 2, 'SS-FIP-001', 'friendship-intention-pair', 'Friendship Intention Pair', 'Gift', 'Two coordinated bracelets for friendship or shared milestones.', 'A paired bracelet set for friends, sisters, partners or shared intentions.', 88.00, 'assets/collection-bracelets.png', 0);

INSERT INTO `product_variants` (`product_id`, `sku`, `bead_size_mm`, `bracelet_length_cm`, `price`, `stock_qty`) VALUES
  (1, 'SS-TMB-001-8MM-18', 8, 18.0, 49.00, 20),
  (2, 'SS-SLRQ-001-8MM-18', 8, 18.0, 45.00, 20),
  (3, 'SS-POB-001-8MM-18', 8, 18.0, 48.00, 20),
  (4, 'SS-FAB-001-8MM-18', 8, 18.0, 46.00, 20),
  (5, 'SS-NBMB-001-8MM-18', 8, 18.0, 49.00, 20),
  (6, 'SS-CQCB-001-8MM-18', 8, 18.0, 42.00, 20),
  (7, 'SS-ACB-001-8MM-18', 8, 18.0, 52.00, 20),
  (8, 'SS-PCGB-001-8MM-18', 8, 18.0, 44.00, 20),
  (9, 'SS-SOIB-001-8MM-18', 8, 18.0, 54.00, 20),
  (10, 'SS-NCGS-001-8MM-18', 8, 18.0, 68.00, 10),
  (11, 'SS-CIGC-001-CUSTOM', NULL, NULL, 75.00, 999),
  (12, 'SS-FIP-001-8MM-18', 8, 18.0, 88.00, 10);

INSERT INTO `product_images` (`product_id`, `image_path`, `alt_text`, `sort_order`) VALUES
  (1, 'assets/collection-bracelets.png', 'Transformation Moonstone Bracelet', 1),
  (2, 'assets/collection-bracelets.png', 'Self Love Rose Quartz Bracelet', 1),
  (3, 'assets/obsidian-preview.png', 'Protection Obsidian Bracelet', 1),
  (4, 'assets/collection-bracelets.png', 'Focus Amethyst Bracelet', 1),
  (5, 'assets/hero-bracelet.png', 'New Beginning Moonstone Bracelet', 1),
  (6, 'assets/stone-clear-quartz.png', 'Clear Quartz Charm Bracelet', 1),
  (7, 'assets/stone-aquamarine.png', 'Aquamarine Calm Bracelet', 1),
  (8, 'assets/stone-pink-catseye.png', 'Pink Cat''s Eye Glow Bracelet', 1),
  (9, 'assets/stone-silver-obsidian.png', 'Silver Obsidian Insight Bracelet', 1),
  (10, 'assets/hero-bracelet.png', 'New Chapter Gift Set', 1),
  (11, 'assets/about-studio.png', 'Custom Intention Gift Card', 1),
  (12, 'assets/collection-bracelets.png', 'Friendship Intention Pair', 1);

INSERT INTO `product_stones` (`product_id`, `stone_id`, `role`) VALUES
  (1, 4, 'main'), (1, 1, 'accent'), (1, 5, 'accent'),
  (2, 2, 'main'), (2, 4, 'accent'),
  (3, 3, 'main'), (3, 5, 'accent'),
  (4, 1, 'main'), (4, 5, 'accent'),
  (5, 4, 'main'), (5, 5, 'accent'),
  (6, 5, 'main'),
  (7, 8, 'main'),
  (8, 6, 'main'),
  (9, 9, 'main'),
  (10, 4, 'main'),
  (11, 5, 'main'),
  (12, 2, 'main');

-- IMPORTANT:
-- For real registration, create users through PHP using password_hash($password, PASSWORD_DEFAULT).
-- Do not store plaintext passwords.
-- Registration password policy:
-- 1. 8 to 10 characters.
-- 2. Must contain at least one letter and at least one number.
-- Suggested PHP regex: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,10}$/
-- The two seeded admin accounts use hashed versions of the passwords provided by the site owner.
