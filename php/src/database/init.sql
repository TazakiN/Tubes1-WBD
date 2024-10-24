\c postgres;
DROP DATABASE IF EXISTS linkinpurry;
CREATE DATABASE linkinpurry;
\c linkinpurry;

-- Create ENUM types for role, jenis_pekerjaan, jenis_lokasi, and status
CREATE TYPE user_role AS ENUM ('company', 'jobseeker');
CREATE TYPE pekerjaan_type AS ENUM ('Internship', 'Full-time', 'Part-time');
CREATE TYPE lokasi_type AS ENUM ('On-site', 'Remote', 'Hybrid');
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

-- Data Dummy Users (20 users: 8 companies, 12 jobseekers)
INSERT INTO Users (role, email, nama, password) VALUES
-- Companies
('company', 'techforge@example.com', 'TechForge Solutions', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'innovatech@example.com', 'InnovaTech Industries', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'digitalminds@example.com', 'Digital Minds Corp', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'futurelabs@example.com', 'Future Labs Indonesia', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'codemaster@example.com', 'CodeMaster Technologies', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'datadriven@example.com', 'Data Driven Enterprise', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'cloudpeak@example.com', 'Cloud Peak Solutions', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('company', 'smartdev@example.com', 'Smart Development ID', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
-- Jobseekers
('jobseeker', 'ahmad@example.com', 'Ahmad Firdaus', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'siti@example.com', 'Siti Nurhaliza', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'budi@example.com', 'Budi Santoso', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'dewi@example.com', 'Dewi Lestari', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'rudi@example.com', 'Rudi Hermawan', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'nina@example.com', 'Nina Kartika', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'farhan@example.com', 'Farhan Rahman', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'lisa@example.com', 'Lisa Permata', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'dani@example.com', 'Dani Pratama', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'maya@example.com', 'Maya Sari', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'rizki@example.com', 'Rizki Ramadhan', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W'),
('jobseeker', 'anisa@example.com', 'Anisa Wijaya', '$2y$10$hc4bVMLA7KD2txVB1pKTgOo/vD8AMh2k2aqmNd2vJYT.itnIRlj4W');

-- Data Dummy Company_Detail (8 companies)
INSERT INTO Company_Detail (user_id, lokasi, about) VALUES
(1, 'Jakarta Selatan', 'Perusahaan teknologi yang berfokus pada pengembangan software enterprise dan solusi cloud computing. Berdiri sejak 2015 dengan lebih dari 200 karyawan.'),
(2, 'Bandung', 'Startup inovatif yang mengembangkan solusi AI dan IoT untuk industri manufaktur. Tim yang dinamis dengan kultur kerja yang modern.'),
(3, 'Jakarta Pusat', 'Perusahaan konsultan IT yang menyediakan layanan transformasi digital untuk berbagai sektor industri. Berpengalaman lebih dari 8 tahun.'),
(4, 'Surabaya', 'Startup fintech yang sedang berkembang pesat dengan fokus pada inklusi keuangan di Indonesia. Didukung oleh investor internasional.'),
(5, 'Yogyakarta', 'Software house spesialis dalam pengembangan aplikasi mobile dan web. Mengutamakan kualitas dan kepuasan klien.'),
(6, 'Medan', 'Perusahaan analitika data yang membantu bisnis mengoptimalkan operasional menggunakan machine learning dan AI.'),
(7, 'Semarang', 'Perusahaan IT yang fokus pada pengembangan sistem terintegrasi dan solusi cloud untuk UKM.'),
(8, 'Bali', 'Digital agency yang mengkhususkan diri dalam pengembangan produk digital kreatif dan innovative.');

-- Data Dummy Lowongan (16 job postings)
INSERT INTO Lowongan (company_id, posisi, deskripsi, jenis_pekerjaan, jenis_lokasi, is_open) VALUES
(1, 'Senior Software Engineer', 'Memimpin pengembangan microservices menggunakan Java Spring Boot dan Docker. Minimal 5 tahun pengalaman.', 'Full-time', 'Hybrid', true),
(1, 'DevOps Engineer', 'Mengelola infrastruktur cloud dan CI/CD pipeline. Pengalaman dengan AWS/GCP.', 'Full-time', 'Remote', true),
(2, 'Machine Learning Engineer', 'Mengembangkan model AI untuk prediksi maintenance mesin industri.', 'Full-time', 'On-site', true),
(2, 'Frontend Developer', 'Mengembangkan dashboard analytics menggunakan React dan TypeScript.', 'Full-time', 'Hybrid', true),
(3, 'IT Business Analyst', 'Menjembatani kebutuhan bisnis dengan solusi teknologi.', 'Full-time', 'On-site', true),
(3, 'QA Engineer', 'Memastikan kualitas produk melalui automated testing.', 'Full-time', 'Remote', false),
(4, 'Mobile Developer', 'Pengembangan aplikasi Android/iOS menggunakan Flutter.', 'Full-time', 'Hybrid', true),
(4, 'UI/UX Designer', 'Merancang pengalaman pengguna untuk aplikasi fintech.', 'Full-time', 'On-site', true),
(5, 'Backend Developer', 'Pengembangan API dan sistem backend menggunakan Node.js.', 'Full-time', 'Remote', true),
(5, 'Product Manager', 'Mengelola roadmap produk digital dan koordinasi dengan tim development.', 'Full-time', 'Hybrid', true),
(6, 'Data Scientist', 'Analisis data dan pengembangan model prediktif.', 'Full-time', 'Remote', true),
(6, 'Business Intelligence Analyst', 'Membuat dashboard dan laporan analitik menggunakan PowerBI.', 'Part-time', 'Hybrid', true),
(7, 'Cloud Solutions Architect', 'Merancang arsitektur cloud untuk klien enterprise.', 'Full-time', 'On-site', true),
(7, 'Technical Support', 'Memberikan dukungan teknis untuk produk cloud.', 'Part-time', 'Remote', true),
(8, 'Creative Developer', 'Mengembangkan solusi web kreatif menggunakan teknologi modern.', 'Full-time', 'Hybrid', true),
(8, 'Digital Marketing Intern', 'Membantu pelaksanaan kampanye digital marketing.', 'Internship', 'On-site', true);

-- Data Dummy Attachment_Lowongan (8 attachments)
INSERT INTO Attachment_Lowongan (lowongan_id, file_path) VALUES
(1, '/attachments/lowongan1/job_description.pdf'),
(1, '/attachments/lowongan1/requirements.pdf'),
(2, '/attachments/lowongan2/technical_requirements.pdf'),
(4, '/attachments/lowongan4/designer_test.pdf'),
(7, '/attachments/lowongan7/mobile_dev_test.pdf'),
(10, '/attachments/lowongan10/product_case_study.pdf'),
(13, '/attachments/lowongan13/architecture_test.pdf'),
(16, '/attachments/lowongan16/Internship_details.pdf');

-- Data Dummy Lamaran (20 applications)
INSERT INTO Lamaran (user_id, lowongan_id, cv_path, video_path, note, status, status_reason) VALUES
(9, 1, '/cvs/ahmad/cv.pdf', '/videos/ahmad/intro.mp4', 'Memiliki pengalaman 6 tahun dalam Java development.', 'accepted', 'Pengalaman dan skills match dengan kebutuhan tim.'),
(10, 1, '/cvs/siti/cv.pdf', NULL, 'Berpengalaman dengan Spring Boot dan microservices.', 'rejected', 'Kurang pengalaman dalam skala enterprise.'),
(11, 2, '/cvs/budi/cv.pdf', '/videos/budi/profile.mp4', 'Familiar dengan AWS dan Docker.', 'waiting', NULL),
(12, 3, '/cvs/dewi/cv.pdf', NULL, 'Passionate dalam AI dan machine learning.', 'accepted', 'Background akademis yang kuat dan pengalaman relevant.'),
(13, 4, '/cvs/rudi/cv.pdf', NULL, 'Berpengalaman dengan React dan TypeScript.', 'waiting', NULL),
(14, 5, '/cvs/nina/cv.pdf', '/videos/nina/intro.mp4', 'Background dalam business analysis.', 'rejected', 'Kurang pengalaman dalam proyek teknologi.'),
(15, 6, '/cvs/farhan/cv.pdf', NULL, 'Berpengalaman dalam automated testing.', 'accepted', 'Skills teknis yang sesuai dengan kebutuhan.'),
(16, 7, '/cvs/lisa/cv.pdf', NULL, 'Portfolio aplikasi Flutter yang impressive.', 'waiting', NULL),
(17, 8, '/cvs/dani/cv.pdf', '/videos/dani/portfolio.mp4', 'Portfolio desain UI/UX yang kreatif.', 'accepted', 'Portfolio menunjukkan kreativitas dan kemampuan teknis yang baik.'),
(18, 9, '/cvs/maya/cv.pdf', NULL, 'Familiar dengan Node.js dan Express.', 'waiting', NULL),
(19, 10, '/cvs/rizki/cv.pdf', NULL, 'Pengalaman product management 3 tahun.', 'rejected', 'Kurang pengalaman dalam produk teknologi.'),
(20, 11, '/cvs/anisa/cv.pdf', '/videos/anisa/intro.mp4', 'Background dalam data science dan statistik.', 'waiting', NULL),
(9, 12, '/cvs/ahmad/cv_2.pdf', NULL, 'Familiar dengan PowerBI dan SQL.', 'waiting', NULL),
(11, 13, '/cvs/budi/cv_2.pdf', NULL, 'Berpengalaman dengan AWS architecture.', 'accepted', 'Pengalaman yang relevan dan sertifikasi AWS.'),
(13, 14, '/cvs/rudi/cv_2.pdf', NULL, 'Pengalaman support di perusahaan IT.', 'waiting', NULL),
(15, 15, '/cvs/farhan/cv_2.pdf', '/videos/farhan/portfolio.mp4', 'Portfolio web development yang kreatif.', 'waiting', NULL),
(17, 16, '/cvs/dani/cv_2.pdf', NULL, 'Antusias belajar digital marketing.', 'accepted', 'Motivasi tinggi dan background marketing yang sesuai.'),
(19, 3, '/cvs/rizki/cv_2.pdf', NULL, 'Pengalaman dengan Python dan ML.', 'waiting', NULL),
(20, 5, '/cvs/anisa/cv_2.pdf', '/videos/anisa/profile.mp4', 'Portfolio data analysis yang menarik.', 'waiting', NULL);