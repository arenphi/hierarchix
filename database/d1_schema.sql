-- 1. Tabel Kriterias
CREATE TABLE IF NOT EXISTS kriterias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kode_kriteria TEXT NOT NULL UNIQUE,
    nama_kriteria TEXT NOT NULL,
    bobot_prioritas REAL DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel Alternatifs
CREATE TABLE IF NOT EXISTS alternatifs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    kode_alternatif TEXT NOT NULL UNIQUE,
    nama_alternatif TEXT NOT NULL,
    skor_global REAL DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabel Nilai Matriks Perbandingan Berpasangan
CREATE TABLE IF NOT EXISTS nilai_matriks (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tipe_matriks TEXT NOT NULL,
    elemen_baris_id INTEGER NOT NULL,
    elemen_kolom_id INTEGER NOT NULL,
    nilai_skala REAL NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_matriks_search 
ON nilai_matriks(tipe_matriks, elemen_baris_id, elemen_kolom_id);

-- 4. Dataset Awal (Kriteria & Alternatif Default)
INSERT OR IGNORE INTO kriterias (kode_kriteria, nama_kriteria, bobot_prioritas) VALUES
('C1', 'Prestasi Akademik (IPK)', 0.262),
('C2', 'Karya Ilmiah & Inovasi', 0.540),
('C3', 'Aktivitas Organisasi & Kepemimpinan', 0.134),
('C4', 'Keahlian Bahasa & Komunikasi', 0.064);

INSERT OR IGNORE INTO alternatifs (kode_alternatif, nama_alternatif, skor_global) VALUES
('A1', 'Ahmad Fauzi', 0.412),
('A2', 'Budi Santoso', 0.328),
('A3', 'Citra Dewi', 0.260);