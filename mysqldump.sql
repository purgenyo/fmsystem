SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `release_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pages_count` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_modify` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `books` (`book_id`, `name`, `author`, `release_date`, `pages_count`, `create_date`, `last_modify`) VALUES
(1, 'test',   '135098135', '2016-10-22 07:59:35', 53, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'test 1', '135098135', '2016-10-22 07:59:39', 53, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Тест 1', '135098135', '2016-10-22 07:59:39', 422, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
