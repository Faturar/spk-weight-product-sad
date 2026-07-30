<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/pdf_report.php';
require_roles(['admin']);

$type = $_GET['type'] ?? '';

switch ($type) {
    case 'siswa':
        $rows = $pdo->query('SELECT kode_siswa, nama_siswa, nis, no_handphone, jenis_kelamin FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
        foreach ($rows as $index => &$row) {
            $row = array_merge(['no' => (string) ($index + 1)], $row);
        }
        unset($row);
        send_pdf_report('Report Data Siswa', [
            ['key' => 'no', 'label' => 'No', 'width' => 28],
            ['key' => 'kode_siswa', 'label' => 'Kode', 'width' => 55],
            ['key' => 'nama_siswa', 'label' => 'Nama Siswa', 'width' => 160],
            ['key' => 'nis', 'label' => 'NIS', 'width' => 95],
            ['key' => 'no_handphone', 'label' => 'No HP', 'width' => 110],
            ['key' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'width' => 95],
        ], $rows, 'report-data-siswa');
        break;

    case 'kriteria':
        $rows = $pdo->query('SELECT kode_kriteria, nama_kriteria, bobot, tipe FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
        foreach ($rows as $index => &$row) {
            $row = array_merge(['no' => (string) ($index + 1)], $row);
        }
        unset($row);
        send_pdf_report('Report Data Kriteria', [
            ['key' => 'no', 'label' => 'No', 'width' => 32],
            ['key' => 'kode_kriteria', 'label' => 'Kode', 'width' => 70],
            ['key' => 'nama_kriteria', 'label' => 'Nama Kriteria', 'width' => 220],
            ['key' => 'bobot', 'label' => 'Bobot', 'width' => 70],
            ['key' => 'tipe', 'label' => 'Tipe', 'width' => 90],
        ], $rows, 'report-data-kriteria');
        break;

    case 'penilaian':
        $kriteria = $pdo->query('SELECT id_kriteria, kode_kriteria FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
        $siswa = $pdo->query('SELECT id_siswa, kode_siswa, nama_siswa FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
        $nilaiStmt = $pdo->query('SELECT id_siswa, id_kriteria, nilai FROM penilaian');
        $nilai = [];
        foreach ($nilaiStmt as $n) {
            $nilai[$n['id_siswa']][$n['id_kriteria']] = $n['nilai'];
        }

        $rows = [];
        foreach ($siswa as $index => $item) {
            $row = [
                'no' => (string) ($index + 1),
                'kode_siswa' => $item['kode_siswa'],
                'nama_siswa' => $item['nama_siswa'],
            ];
            foreach ($kriteria as $k) {
                $row['kriteria_' . $k['id_kriteria']] = (string) ($nilai[$item['id_siswa']][$k['id_kriteria']] ?? '-');
            }
            $rows[] = $row;
        }

        $columns = [
            ['key' => 'no', 'label' => 'No', 'width' => 30],
            ['key' => 'kode_siswa', 'label' => 'Kode', 'width' => 55],
            ['key' => 'nama_siswa', 'label' => 'Nama Siswa', 'width' => 165],
        ];
        foreach ($kriteria as $k) {
            $columns[] = ['key' => 'kriteria_' . $k['id_kriteria'], 'label' => $k['kode_kriteria'], 'width' => 48];
        }
        send_pdf_report('Report Penilaian Siswa', $columns, $rows, 'report-penilaian-siswa');
        break;

    case 'hasil-seleksi':
        $rows = $pdo->query('SELECT h.ranking, s.kode_siswa, s.nama_siswa, h.nilai_v, h.tahun_ajaran, h.tanggal_pengumuman FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
        foreach ($rows as &$row) {
            $row['nilai_v'] = number_format((float) $row['nilai_v'], 4);
            $row['tanggal_pengumuman'] = date('d-m-Y', strtotime($row['tanggal_pengumuman']));
        }
        unset($row);
        send_pdf_report('Report Hasil Seleksi', [
            ['key' => 'ranking', 'label' => 'Ranking', 'width' => 55],
            ['key' => 'kode_siswa', 'label' => 'Kode', 'width' => 65],
            ['key' => 'nama_siswa', 'label' => 'Nama Siswa', 'width' => 180],
            ['key' => 'nilai_v', 'label' => 'Nilai V', 'width' => 75],
            ['key' => 'tahun_ajaran', 'label' => 'Tahun Ajaran', 'width' => 100],
            ['key' => 'tanggal_pengumuman', 'label' => 'Tanggal Pengumuman', 'width' => 130],
        ], $rows, 'report-hasil-seleksi');
        break;

    default:
        http_response_code(404);
        exit('Report tidak ditemukan.');
}
