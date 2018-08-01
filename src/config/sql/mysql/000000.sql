





# INSERT INTO `user` (`id`, `name`, `email`, `username`, `password`, `role`, `active`, `hash`, `modified`, `created`)
# VALUES
#   (NULL, 'Administrator', 'admin@example.com', 'admin', MD5('password'), 'admin', 1, MD5('1admin'), NOW() , NOW()),
#   (NULL, 'User 1', 'user@example.com', 'user1', MD5('password'), 'user', 1, MD5('2user1'), NOW() , NOW())
# ;


TRUNCATE `user`;
INSERT INTO `user` (`role_id`, `name`, `email`, `username`, `password`, `hash`, `modified`, `created`)
VALUES
  (1, 'Administrator', 'admin@example.com', 'admin', MD5('password'), MD5('1admin'), NOW() , NOW()),
  (2, 'User', 'user@example.com', 'user1', MD5('password'), MD5('2user'), NOW() , NOW())
;
