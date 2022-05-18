
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- authors
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `authors`;

CREATE TABLE `authors`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128),
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- books
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `author_id` INTEGER NOT NULL,
    `title` VARCHAR(128),
    PRIMARY KEY (`id`),
    INDEX `books_FI_1` (`author_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
