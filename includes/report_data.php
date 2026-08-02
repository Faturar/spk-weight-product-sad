<?php

function report_options(): array
{
    return [
        [
            'type' => 'siswa',
            'title' => 'Report Data Siswa',
            'description' => 'Daftar kode siswa, nama, NIS, nomor HP, dan jenis kelamin.',
        ],
        [
            'type' => 'kriteria',
            'title' => 'Report Data Kriteria',
            'description' => 'Daftar kode kriteria, nama kriteria, bobot, dan tipe benefit/cost.',
        ],
        [
            'type' => 'penilaian',
            'title' => 'Report Penilaian Siswa',
            'description' => 'Rekap nilai setiap siswa pada seluruh kriteria.',
        ],
        [
            'type' => 'hasil-seleksi',
            'title' => 'Report Hasil Seleksi',
            'description' => 'Ranking akhir Weighted Product beserta nilai V dan tanggal pengumuman.',
        ],
    ];
}

function get_report_data(PDO $pdo, string $type): ?array
{
    switch ($type) {
        case 'siswa':
            $rows = $pdo->query('SELECT kode_siswa, nama_siswa, nis, no_handphone, jenis_kelamin, kelas FROM siswa ORDER BY kode_siswa ASC')->fetchAll();
            foreach ($rows as $index => &$row) {
                $row = array_merge(['no' => (string) ($index + 1)], $row);
            }
            unset($row);

            return [
                'title' => 'Report Data Siswa',
                'filename' => 'report-data-siswa',
                'empty_message' => 'Belum ada data siswa.',
                'columns' => [
                    ['key' => 'no', 'label' => 'No', 'width' => 28],
                    ['key' => 'kode_siswa', 'label' => 'Kode', 'width' => 55],
                    ['key' => 'nama_siswa', 'label' => 'Nama Siswa', 'width' => 160],
                    ['key' => 'nis', 'label' => 'NIS', 'width' => 95],
                    ['key' => 'no_handphone', 'label' => 'No HP', 'width' => 110],
                    ['key' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'width' => 95],
                    ['key' => 'kelas', 'label' => 'Kelas', 'width' => 50],
                ],
                'rows' => $rows,
            ];

        case 'kriteria':
            $rows = $pdo->query('SELECT kode_kriteria, nama_kriteria, bobot, tipe FROM kriteria ORDER BY kode_kriteria ASC')->fetchAll();
            foreach ($rows as $index => &$row) {
                $row = array_merge(['no' => (string) ($index + 1)], $row);
            }
            unset($row);

            return [
                'title' => 'Report Data Kriteria',
                'filename' => 'report-data-kriteria',
                'empty_message' => 'Belum ada data kriteria.',
                'columns' => [
                    ['key' => 'no', 'label' => 'No', 'width' => 32],
                    ['key' => 'kode_kriteria', 'label' => 'Kode', 'width' => 70],
                    ['key' => 'nama_kriteria', 'label' => 'Nama Kriteria', 'width' => 220],
                    ['key' => 'bobot', 'label' => 'Bobot', 'width' => 70],
                    ['key' => 'tipe', 'label' => 'Tipe', 'width' => 90],
                ],
                'rows' => $rows,
            ];

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

            return [
                'title' => 'Report Penilaian Siswa',
                'filename' => 'report-penilaian-siswa',
                'empty_message' => 'Belum ada data penilaian.',
                'columns' => $columns,
                'rows' => $rows,
            ];

        case 'hasil-seleksi':
            $rows = $pdo->query('SELECT h.ranking, s.kode_siswa, s.nama_siswa, h.nilai_v, h.tahun_ajaran, h.tanggal_pengumuman FROM hasil_wp h JOIN siswa s ON s.id_siswa=h.id_siswa ORDER BY h.ranking ASC')->fetchAll();
            foreach ($rows as &$row) {
                $row['nilai_v'] = number_format((float) $row['nilai_v'], 4);
                $row['tanggal_pengumuman'] = date('d-m-Y', strtotime($row['tanggal_pengumuman']));
            }
            unset($row);

            return [
                'title' => 'Report Hasil Seleksi',
                'filename' => 'report-hasil-seleksi',
                'empty_message' => 'Belum ada hasil seleksi.',
                'columns' => [
                    ['key' => 'ranking', 'label' => 'Ranking', 'width' => 55],
                    ['key' => 'kode_siswa', 'label' => 'Kode', 'width' => 65],
                    ['key' => 'nama_siswa', 'label' => 'Nama Siswa', 'width' => 180],
                    ['key' => 'nilai_v', 'label' => 'Nilai V', 'width' => 75],
                    ['key' => 'tahun_ajaran', 'label' => 'Tahun Ajaran', 'width' => 100],
                    ['key' => 'tanggal_pengumuman', 'label' => 'Tanggal Pengumuman', 'width' => 130],
                ],
                'rows' => $rows,
            ];
    }

    return null;
}
