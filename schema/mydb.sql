CREATE TABLE `devices` (
	`uuid` varchar(36) NOT NULL,
	`owner_uuid` varchar(36) NOT NULL,
	`hostname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
	`operating_system` ENUM('android', 'iOS', 'lin', 'macOS', 'win') NOT NULL,
	`type` ENUM('laptop', 'mobile', 'pc') NOT NULL,
	PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `owners` (
	`uuid` varchar(36) NOT NULL,
	`firstname` varchar(50) NOT NULL,
	`lastname` varchar(50) NOT NULL,
	PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
