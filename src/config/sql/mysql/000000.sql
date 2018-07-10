





INSERT INTO `user` (`id`, `name`, `email`, `username`, `password`, `role`, `active`, `hash`, `modified`, `created`)
VALUES
  (NULL, 'Administrator', 'admin@example.com', 'admin', MD5('password'), 'admin', 1, MD5('1admin'), NOW() , NOW()),
  (NULL, 'User 1', 'user@example.com', 'user1', MD5('password'), 'user', 1, MD5('2user1'), NOW() , NOW())
;

