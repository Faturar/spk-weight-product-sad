CREATE DATABASE IF NOT EXISTS spk_pramuka_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spk_pramuka_wp;

DROP TABLE IF EXISTS hasil_wp;
DROP TABLE IF EXISTS penilaian;
DROP TABLE IF EXISTS aspek;
DROP TABLE IF EXISTS kriteria;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','pembina','kepala_sekolah') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE siswa (
    id_siswa INT AUTO_INCREMENT PRIMARY KEY,
    kode_siswa VARCHAR(20) NOT NULL UNIQUE,
    nama_siswa VARCHAR(100) NOT NULL,
    nis VARCHAR(30) NOT NULL,
    no_handphone VARCHAR(20) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kriteria (
    id_kriteria INT AUTO_INCREMENT PRIMARY KEY,
    kode_kriteria VARCHAR(10) NOT NULL UNIQUE,
    nama_kriteria VARCHAR(100) NOT NULL,
    bobot FLOAT NOT NULL,
    tipe ENUM('benefit','cost') NOT NULL DEFAULT 'benefit'
) ENGINE=InnoDB;

CREATE TABLE aspek (
    id_aspek INT AUTO_INCREMENT PRIMARY KEY,
    id_kriteria INT NOT NULL,
    nama_aspek VARCHAR(100) NOT NULL,
    nilai INT NOT NULL,
    keterangan TEXT,
    CONSTRAINT fk_aspek_kriteria FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE penilaian (
    id_penilaian INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    id_kriteria INT NOT NULL,
    nilai FLOAT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_penilaian (id_siswa, id_kriteria),
    CONSTRAINT fk_penilaian_siswa FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_penilaian_kriteria FOREIGN KEY (id_kriteria) REFERENCES kriteria(id_kriteria) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE hasil_wp (
    id_hasil INT AUTO_INCREMENT PRIMARY KEY,
    id_siswa INT NOT NULL,
    nilai_s FLOAT NOT NULL,
    nilai_v FLOAT NOT NULL,
    ranking INT NOT NULL,
    tahun_ajaran VARCHAR(20) NOT NULL,
    tanggal_pengumuman DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_hasil_siswa FOREIGN KEY (id_siswa) REFERENCES siswa(id_siswa) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (nama, username, password, role) VALUES
('Admin', 'admin', '$2y$10$yWSqx.c4Q7uyqrcllU/0R.oP6d.jGLvqxed8SFXM7YqdhLrpjIJDm', 'admin'),
('Pembina', 'pembina', '$2y$10$YIPw4N4bdU2V3gddS40w7eAhKSyuJ10eI8DtTJFf5YGxqjcLZju16', 'pembina'),
('Kepala Sekolah', 'kepala', '$2y$10$sTl0hF5fpaPpAZNsPyLB2.SGRUr2gVFr.tmhE8rDJtOtUwsff6n4C', 'kepala_sekolah');

INSERT INTO siswa (id_siswa, kode_siswa, nama_siswa, nis, no_handphone, jenis_kelamin) VALUES
(1, 'A1', 'Alif', '2026001', '081234560001', 'Laki-laki'),
(2, 'A2', 'Bagza', '2026002', '081234560002', 'Laki-laki'),
(3, 'A3', 'Rido', '2026003', '081234560003', 'Laki-laki'),
(4, 'A4', 'Meydha', '2026004', '081234560004', 'Perempuan'),
(5, 'A5', 'Angel', '2026005', '081234560005', 'Perempuan'),
(6, 'A6', 'Jihan', '2026006', '081234560006', 'Perempuan');

INSERT INTO kriteria (id_kriteria, kode_kriteria, nama_kriteria, bobot, tipe) VALUES
(1, 'C1', 'Keterampilan', 5, 'benefit'),
(2, 'C2', 'Kedisiplinan', 4, 'benefit'),
(3, 'C3', 'Kerjasama', 3, 'benefit'),
(4, 'C4', 'Kepemimpinan', 4, 'benefit');

INSERT INTO aspek (id_kriteria, nama_aspek, nilai, keterangan) VALUES
(1, 'Tali Temali', 5, 'Kemampuan menggunakan dan membuat simpul pramuka.'),
(1, 'Semaphore', 5, 'Kemampuan membaca dan mengirim sandi semaphore.'),
(1, 'Mendirikan Tenda', 5, 'Kemampuan mendirikan tenda dengan benar dan rapi.'),
(2, 'Kehadiran', 5, 'Konsistensi hadir dalam kegiatan pramuka.'),
(2, 'Ketepatan Waktu', 5, 'Kedisiplinan datang tepat waktu.'),
(2, 'Kerapian Seragam', 5, 'Kerapian atribut dan seragam pramuka.'),
(3, 'Kemampuan Bekerja Dalam Tim', 5, 'Kemampuan berkolaborasi dalam regu.'),
(3, 'Komunikasi', 5, 'Kemampuan menyampaikan dan menerima informasi.'),
(3, 'Kepedulian Terhadap Sesama', 5, 'Sikap saling membantu antaranggota.'),
(4, 'Tanggung Jawab', 5, 'Kesungguhan menyelesaikan tugas.'),
(4, 'Ketegasan', 5, 'Kemampuan mengambil sikap saat diperlukan.'),
(4, 'Inisiatif', 5, 'Kemampuan memulai tindakan positif tanpa menunggu perintah.');

INSERT INTO penilaian (id_siswa, id_kriteria, nilai) VALUES
(1, 1, 5), (1, 2, 4), (1, 3, 3), (1, 4, 4),
(2, 1, 3), (2, 2, 3), (2, 3, 4), (2, 4, 3),
(3, 1, 3), (3, 2, 4), (3, 3, 3), (3, 4, 4),
(4, 1, 4), (4, 2, 5), (4, 3, 4), (4, 4, 5),
(5, 1, 5), (5, 2, 5), (5, 3, 5), (5, 4, 4),
(6, 1, 4), (6, 2, 4), (6, 3, 5), (6, 4, 3);
