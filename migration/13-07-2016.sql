-----------------------------------------------------------

--
-- Modification in table `employee`
--
ALTER TABLE  `employee` ADD UNIQUE (`email`);

ALTER TABLE  `employee` ADD  `role_id` INT NOT NULL DEFAULT  '2' COMMENT  '1=admin, 2=member';

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `permission_name`) VALUES
(1, 'view'),
(2, 'edit'),
(3, 'delete');

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE IF NOT EXISTS `resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`id`, `resource_name`) VALUES
(1, 'details'),
(2, 'user_home'),
(3, 'sign_up');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'member');

-- --------------------------------------------------------

--
-- Table structure for table `role_resource_permission`
--

CREATE TABLE IF NOT EXISTS `role_resource_permission` (
  `table_id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_role` int(11) NOT NULL,
  `fk_resource` int(11) NOT NULL,
  `fk_permission` int(11) NOT NULL,
  PRIMARY KEY (`table_id`),
  KEY `fk_role` (`fk_role`),
  KEY `fk_resource` (`fk_resource`),
  KEY `fk_permission` (`fk_permission`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `role_resource_permission`
--

INSERT INTO `role_resource_permission` (`table_id`, `fk_role`, `fk_resource`, `fk_permission`) VALUES
(5, 1, 1, 1),
(6, 1, 1, 2),
(7, 1, 1, 3),
(8, 1, 2, 1),
(9, 1, 2, 2),
(10, 1, 2, 3),
(29, 1, 3, 1),
(30, 1, 3, 2),
(31, 1, 3, 3),
(45, 2, 3, 1),
(47, 2, 3, 2),
(48, 2, 3, 3),
(49, 2, 1, 1),
(50, 2, 1, 2),
(51, 2, 1, 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_resource_permission`
--
ALTER TABLE `role_resource_permission`
  ADD CONSTRAINT `role_resource_permission_ibfk_2` FOREIGN KEY (`fk_role`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_resource_permission_ibfk_3` FOREIGN KEY (`fk_resource`) REFERENCES `resource` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_resource_permission_ibfk_4` FOREIGN KEY (`fk_permission`) REFERENCES `permission` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
