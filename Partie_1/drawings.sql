create table if not exists drawings(
    d_id int auto_increment,
	d_commandes blob not null,
    d_image blob not null,
	d_fk_u_id int not null,
  primary key(d_id),
  KEY `fk_drawings_users_idx` (`d_fk_u_id`),
  CONSTRAINT `fk_drawings_users` FOREIGN KEY (`d_fk_u_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);
