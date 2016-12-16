-- Create syntax for TABLE 'team_member_content'
CREATE TABLE `team_member_content` (
  `team_member_id` bigint(20) NOT NULL,
  `language` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `function` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `seo_url_overwrite` enum('N','Y') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description_overwrite` enum('N','Y') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_title_overwrite` enum('N','Y') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team_member_images'
CREATE TABLE `team_member_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_member_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sequence` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  `edited_on` datetime NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `hidden` enum('N','Y') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team_member_images_content'
CREATE TABLE `team_member_images_content` (
  `image_id` bigint(20) NOT NULL,
  `language` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team'
CREATE TABLE `team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hidden` enum('N','Y') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `instagram_site_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `twitter_site_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `pinterest_site_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `facebook_site_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_on` timestamp NULL DEFAULT NULL,
  `edited_on` timestamp NULL DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `status` enum('active','draft') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish_on` timestamp NULL DEFAULT NULL,
  `size` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team_categories'
CREATE TABLE `team_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_on` timestamp NULL DEFAULT NULL,
  `edited_on` timestamp NULL DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `hidden` enum('N','Y') COLLATE utf8mb4_unicode_ci DEFAULT 'N',
  `parent_id` int(11) DEFAULT NULL,
  `path` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team_category_content'
CREATE TABLE `team_category_content` (
  `category_id` bigint(20) NOT NULL,
  `language` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `extra_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- Create syntax for TABLE 'team_linked_catgories'
CREATE TABLE `team_linked_catgories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `team_member_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_member_id` (`team_member_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
