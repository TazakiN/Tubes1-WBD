\c postgres;
DROP DATABASE IF EXISTS linkinpurry;
CREATE DATABASE linkinpurry;
\c linkinpurry;

-- Create ENUM types for role, jenis_pekerjaan, jenis_lokasi, and status
CREATE TYPE user_role AS ENUM ('company', 'jobseeker');
CREATE TYPE pekerjaan_type AS ENUM ('intern', 'full-time', 'part-time');
CREATE TYPE lokasi_type AS ENUM ('on-site', 'remote', 'hybrid');
CREATE TYPE lamaran_status AS ENUM ('accepted', 'rejected', 'waiting');

-- Tabel Users
CREATE TABLE Users (
    user_id SERIAL PRIMARY KEY,
    role user_role NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Tabel Company_Detail
CREATE TABLE Company_Detail (
    user_id INT PRIMARY KEY,
    lokasi VARCHAR(255) NOT NULL,
    about TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Tabel Lowongan
CREATE TABLE Lowongan (
    lowongan_id SERIAL PRIMARY KEY,
    company_id INT NOT NULL,
    posisi VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    jenis_pekerjaan pekerjaan_type NOT NULL,
    jenis_lokasi lokasi_type NOT NULL,
    is_open BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

-- Fungsi dan Trigger untuk Update Timestamp
CREATE OR REPLACE FUNCTION update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_timestamp
BEFORE UPDATE ON Lowongan
FOR EACH ROW
EXECUTE PROCEDURE update_timestamp();

-- Tabel Attachment_Lowongan
CREATE TABLE Attachment_Lowongan (
    attachment_id SERIAL PRIMARY KEY,
    lowongan_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (lowongan_id) REFERENCES Lowongan(lowongan_id) ON DELETE CASCADE
);

-- Tabel Lamaran
CREATE TABLE Lamaran (
    lamaran_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    lowongan_id INT NOT NULL,
    cv_path VARCHAR(255) NOT NULL,
    video_path VARCHAR(255) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    status lamaran_status NOT NULL,
    status_reason TEXT DEFAULT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (lowongan_id) REFERENCES Lowongan(lowongan_id) ON DELETE CASCADE
);

-- Data Dummy Users (8 users)
INSERT INTO Users (role, email, nama, password) VALUES
('company', 'company1@example.com', 'Company One', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'company2@example.com', 'Company Two', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'jobseeker1@example.com', 'Job Seeker One', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'jobseeker2@example.com', 'Job Seeker Two', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'company3@example.com', 'Company Three', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'jobseeker3@example.com', 'Job Seeker Three', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'company4@example.com', 'Company Four', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'jobseeker4@example.com', 'Job Seeker Four', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W');

-- Data Dummy Company_Detail (4 companies)
INSERT INTO Company_Detail (user_id, lokasi, about) VALUES
(1, 'Jakarta', 'Perusahaan teknologi terkemuka di Indonesia.'),
(2, 'Bandung', 'Startup dengan fokus pada AI dan IoT.'),
(5, 'Surabaya', 'Perusahaan software development.'),
(7, 'Yogyakarta', 'Creative agency dengan tim inovatif.');

-- Data Dummy Lowongan (6 job postings)
INSERT INTO Lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi) VALUES
(1, 'Software Engineer', 'Membuat aplikasi web.', 'full-time', 'on-site'),
(1, 'Data Scientist', 'Analisis data besar.', 'full-time', 'remote'),
(2, 'Product Manager', 'Mengelola produk digital.', 'part-time', 'hybrid'),
(5, 'UX Designer', 'Merancang pengalaman pengguna.', 'intern', 'remote'),
(7, 'Backend Developer', 'Mengembangkan API dan sistem backend.', 'full-time', 'on-site'),
(5, 'Marketing Specialist', 'Mengelola kampanye pemasaran.', 'part-time', 'hybrid');

-- Data Dummy Attachment_Lowongan (3 attachments)
INSERT INTO Attachment_Lowongan (lowongan_id, file_path) VALUES
(1, '/attachments/lowongan1/file1.pdf'),
(2, '/attachments/lowongan2/file2.pdf'),
(4, '/attachments/lowongan4/file3.pdf');

-- Data Dummy Lamaran (8 applications)
INSERT INTO Lamaran (user_id, lowongan_id, cv_path, video_path, note, status) VALUES
(3, 1, '/cvs/jobseeker1/cv.pdf', NULL, 'Sangat tertarik dengan posisi ini.', 'waiting'),
(4, 2, '/cvs/jobseeker2/cv.pdf', '/videos/jobseeker2/video.mp4', 'Berpengalaman dalam bidang terkait.', 'waiting'),
(6, 3, '/cvs/jobseeker3/cv.pdf', NULL, 'Pengalaman manajerial.', 'waiting'),
(8, 4, '/cvs/jobseeker4/cv.pdf', NULL, 'Minat tinggi dalam desain UX.', 'rejected'),
(3, 5, '/cvs/jobseeker1/cv.pdf', NULL, 'Tertarik pada backend development.', 'accepted'),
(4, 6, '/cvs/jobseeker2/cv.pdf', NULL, 'Berpengalaman di pemasaran.', 'waiting'),
(6, 2, '/cvs/jobseeker3/cv.pdf', NULL, 'Tertarik dalam analisis data.', 'accepted'),
(8, 1, '/cvs/jobseeker4/cv.pdf', NULL, 'Berharap berkontribusi di engineering.', 'waiting');
