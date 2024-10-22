DROP DATABASE IF EXISTS linkinpurry;
CREATE DATABASE linkinpurry;
\c linkinpurry;

CREATE TABLE Users (
    user_id SERIAL PRIMARY KEY,
    role VARCHAR(255) CHECK (role IN ('company', 'jobseeker')) NOT NULL,
    email VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Company_Detail (
    user_id INT PRIMARY KEY,
    lokasi VARCHAR(255) NOT NULL,
    about TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

CREATE TABLE Lowongan (
    lowongan_id SERIAL PRIMARY KEY,
    company_id INT NOT NULL,
    posisi VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    jenis_pekerjaan VARCHAR(255) NOT NULL,
    jenis_lokasi VARCHAR(255) CHECK (jenis_lokasi IN ('on-site', 'remote', 'hybrid')) NOT NULL,
    is_open BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES Users(user_id)
);

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

CREATE TABLE Attachment_Lowongan (
    attachment_id SERIAL PRIMARY KEY,
    lowongan_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (lowongan_id) REFERENCES Lowongan(lowongan_id)
);

CREATE TABLE Lamaran (
    lamaran_id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    lowongan_id INT NOT NULL,
    cv_path VARCHAR(255) NOT NULL,
    video_path VARCHAR(255) DEFAULT NULL,
    note TEXT DEFAULT NULL,
    status VARCHAR(255) CHECK (status IN ('accepted', 'rejected', 'waiting')),
    status_reason TEXT DEFAULT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (lowongan_id) REFERENCES Lowongan(lowongan_id)
);
