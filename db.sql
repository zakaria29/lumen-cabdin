create table sekolah(
  id_sekolah int auto_increment,
  nama_sekolah varchar(300),
  alamat_sekolah varchar(300),
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  primary key(id_sekolah)
);

create table admin_sekolah(
  id_admin_sekolah int auto_increment,
  nama_admin_sekolah varchar(300),
  kontak varchar(100),
  email varchar(300),
  id_sekolah int,
  username varchar(300),
  password varchar(300),
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  primary key(id_admin_sekolah)
);

create table admin(
  id_admin int auto_increment,
  nama_admin varchar(300),
  kontak varchar(100),
  email varchar(300),
  username varchar(300),
  password varchar(300),
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  primary key(id_admin)
);

create table surat(
  id_surat int auto_increment,
  nomor_surat varchar(300),
  prihal varchar(500),
  id_admin_sekolah int,
  file_surat varchar(300),
  waktu datetime,
  link varchar(500),
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  primary key(id_surat)
);

create table jurnal(
  id_jurnal int auto_increment,
  judul_jurnal text,
  penulis text,
  kategori varchar(300),
  file_jurnal varchar(300),
  id_sekolah int,
  waktu datetime,
  link varchar(500),
  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  primary key(id_jurnal)
);
