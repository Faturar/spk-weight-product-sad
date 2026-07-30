USE spk_pramuka_wp;

DELETE FROM users WHERE role <> 'admin';
ALTER TABLE users MODIFY role ENUM('admin') NOT NULL DEFAULT 'admin';
