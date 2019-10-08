--
-- Table structure for table `auth_attempts`
--

CREATE TABLE `auth_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_log`
--

CREATE TABLE `auth_log` (
  `id` int(5) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `enc_key` varchar(192) NOT NULL,
  `browser` varchar(32) NOT NULL,
  `browser_version` char(16) NOT NULL,
  `ip_address` char(16) NOT NULL,
  `platform` varchar(32) NOT NULL,
  `authorized_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `authorize_using` enum('session','cookie') NOT NULL DEFAULT 'session',
  `expires_at` datetime DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` char(16) DEFAULT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email_address` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `enc_key` varchar(32) DEFAULT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `key_generated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `registered_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_attempts`
--
ALTER TABLE `auth_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_attempts_user_id` (`user_id`);

--
-- Indexes for table `auth_log`
--
ALTER TABLE `auth_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_log_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auth_attempts`
--
ALTER TABLE `auth_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_log`
--
ALTER TABLE `auth_log`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_attempts`
--
ALTER TABLE `auth_attempts`
  ADD CONSTRAINT `auth_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `auth_log`
--
ALTER TABLE `auth_log`
  ADD CONSTRAINT `auth_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;
