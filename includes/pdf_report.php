<?php

if (!defined('REPORT_PEMBINA_NAME')) {
    define('REPORT_PEMBINA_NAME', 'Muhammad Irfan, S.Ag.');
}

class SimplePdfReport
{
    private float $width;
    private float $height;
    private array $pages = [];
    private array $current = [];
    private float $y = 36;
    private string $title;
    private string $generatedAt;
    private ?array $logo = null;

    public function __construct(string $title, string $generatedAt, string $orientation = 'L')
    {
        $this->title = $title;
        $this->generatedAt = $generatedAt;

        if ($orientation === 'P') {
            $this->width = 595.28;
            $this->height = 841.89;
        } else {
            $this->width = 841.89;
            $this->height = 595.28;
        }

        $this->logo = $this->loadLogo();
        $this->addPage();
    }

    public function table(array $columns, array $rows): void
    {
        $x = 36;
        $tableWidth = $this->width - 72;
        $totalWidth = array_sum(array_column($columns, 'width'));
        $scale = $totalWidth > 0 ? $tableWidth / $totalWidth : 1;

        foreach ($columns as &$column) {
            $column['actual_width'] = $column['width'] * $scale;
        }
        unset($column);

        $this->drawTableHeader($columns, $x);

        if (!$rows) {
            $firstKey = $columns[0]['key'] ?? 'empty';
            $this->drawTableRow($columns, [$firstKey => 'Data tidak tersedia.'], $x);
            return;
        }

        foreach ($rows as $row) {
            $this->drawTableRow($columns, $row, $x);
        }
    }

    public function signature(string $city = 'Kota Depok', string $name = REPORT_PEMBINA_NAME): void
    {
        if ($this->y > $this->height - 155) {
            $this->addPage();
        }

        $x = $this->width - 260;
        $this->y += 28;
        $this->text($x, $this->y, $city . ', ' . format_tanggal_indonesia(), 10);
        $this->text($x + 25, $this->y + 18, 'Mengetahui,', 10);
        $this->text($x + 42, $this->y + 36, 'Pembina', 10);
        $this->text($x + 15, $this->y + 92, '________________________', 10);
        $this->text($x + 22, $this->y + 110, $name, 10);
    }

    public function output(): string
    {
        if ($this->current) {
            $this->pages[] = implode("\n", $this->current);
            $this->current = [];
        }

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';

        $logoObjectNumber = null;
        if ($this->logo) {
            $logoObjectNumber = 4;
        }

        $pageObjectNumbers = [];
        $contentObjectNumbers = [];
        $nextObjectNumber = $logoObjectNumber ? 5 : 4;
        foreach ($this->pages as $_) {
            $pageObjectNumbers[] = $nextObjectNumber++;
            $contentObjectNumbers[] = $nextObjectNumber++;
        }

        $kids = implode(' ', array_map(fn($number) => $number . ' 0 R', $pageObjectNumbers));
        $objects[] = '<< /Type /Pages /Kids [' . $kids . '] /Count ' . count($this->pages) . ' >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

        if ($this->logo) {
            $objects[] = '<< /Type /XObject /Subtype /Image /Width ' . $this->logo['width'] . ' /Height ' . $this->logo['height'] . ' /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length ' . strlen($this->logo['data']) . " >>\nstream\n" . $this->logo['data'] . "\nendstream";
        }

        $resources = '/Font << /F1 3 0 R >>';
        if ($logoObjectNumber) {
            $resources .= ' /XObject << /Im1 ' . $logoObjectNumber . ' 0 R >>';
        }

        foreach ($this->pages as $index => $content) {
            $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 ' . $this->formatNumber($this->width) . ' ' . $this->formatNumber($this->height) . '] /Resources << ' . $resources . ' >> /Contents ' . $contentObjectNumbers[$index] . ' 0 R >>';
            $objects[] = '<< /Length ' . strlen($content) . " >>\nstream\n" . $content . "\nendstream";
        }

        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xrefPosition = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";
        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xrefPosition . "\n%%EOF";

        return $pdf;
    }

    private function addPage(): void
    {
        if ($this->current) {
            $this->pages[] = implode("\n", $this->current);
        }

        $this->current = [];
        $this->y = 34;
        $this->reportHeader();
    }

    private function reportHeader(): void
    {
        if ($this->logo) {
            $this->image(50, $this->y - 5, 58, 58);
        }

        $this->text(0, $this->y + 4, 'MTs Nurul Falah Areman', 15, true, $this->width);
        $this->text(0, $this->y + 24, 'Jl. Menpor Palsigunung No.89 RT 1 / RW.7 Tugu, Kec. Cimanggis, Kota Depok, Jawa Barat 16451', 9, false, $this->width);
        $this->line(36, $this->y + 66, $this->width - 36, $this->y + 66);
        $this->line(36, $this->y + 69, $this->width - 36, $this->y + 69);
        $this->text(0, $this->y + 90, strtoupper($this->title), 13, true, $this->width);
        $this->text(36, $this->y + 114, 'Tanggal Generate: ' . $this->generatedAt, 10);
        $this->y += 137;
    }

    private function drawTableHeader(array $columns, float $x): void
    {
        $height = 24;
        $this->rect($x, $this->y, $this->width - 72, $height, [255, 237, 213]);
        $currentX = $x;
        foreach ($columns as $column) {
            $this->rect($currentX, $this->y, $column['actual_width'], $height);
            $this->text($currentX + 4, $this->y + 15, $column['label'], 8, true);
            $currentX += $column['actual_width'];
        }
        $this->y += $height;
    }

    private function drawTableRow(array $columns, array $row, float $x): void
    {
        $linesByColumn = [];
        $maxLines = 1;
        foreach ($columns as $column) {
            $value = (string) ($row[$column['key']] ?? '');
            if (($column['key'] ?? '') === 'empty') {
                $value = $row['empty'] ?? '';
            }
            $lines = $this->wrapText($value, max(8, (int) floor($column['actual_width'] / 4.4)));
            $linesByColumn[] = $lines;
            $maxLines = max($maxLines, count($lines));
        }

        $height = max(22, 10 + ($maxLines * 10));
        if ($this->y + $height > $this->height - 38) {
            $this->addPage();
            $this->drawTableHeader($columns, $x);
        }

        $currentX = $x;
        foreach ($columns as $index => $column) {
            $this->rect($currentX, $this->y, $column['actual_width'], $height);
            foreach ($linesByColumn[$index] as $lineIndex => $line) {
                $this->text($currentX + 4, $this->y + 14 + ($lineIndex * 10), $line, 8);
            }
            $currentX += $column['actual_width'];
        }

        $this->y += $height;
    }

    private function wrapText(string $text, int $maxChars): array
    {
        $text = trim(preg_replace('/\s+/', ' ', $text));
        if ($text === '') {
            return [''];
        }

        $words = explode(' ', $text);
        $lines = [];
        $line = '';
        foreach ($words as $word) {
            if ($line === '') {
                $line = $word;
                continue;
            }

            if (strlen($line . ' ' . $word) <= $maxChars) {
                $line .= ' ' . $word;
            } else {
                $lines[] = $line;
                $line = $word;
            }
        }
        $lines[] = $line;

        return $lines;
    }

    private function text(float $x, float $y, string $text, int $size = 10, bool $bold = false, ?float $containerWidth = null): void
    {
        $displayText = $this->cleanText($text);
        if ($containerWidth !== null) {
            $estimatedWidth = strlen($displayText) * $size * 0.52;
            $x = max(0, ($containerWidth - $estimatedWidth) / 2);
        }

        $pdfY = $this->height - $y;
        $this->current[] = 'BT /F1 ' . $size . ' Tf ' . $this->formatNumber($x) . ' ' . $this->formatNumber($pdfY) . ' Td (' . $this->escape($displayText) . ') Tj ET';
        if ($bold) {
            $this->current[] = 'BT /F1 ' . $size . ' Tf ' . $this->formatNumber($x + .35) . ' ' . $this->formatNumber($pdfY) . ' Td (' . $this->escape($displayText) . ') Tj ET';
        }
    }

    private function line(float $x1, float $y1, float $x2, float $y2): void
    {
        $this->current[] = $this->formatNumber($x1) . ' ' . $this->formatNumber($this->height - $y1) . ' m ' . $this->formatNumber($x2) . ' ' . $this->formatNumber($this->height - $y2) . ' l S';
    }

    private function rect(float $x, float $y, float $width, float $height, ?array $fill = null): void
    {
        $pdfY = $this->height - $y - $height;
        if ($fill) {
            $this->current[] = sprintf('q %.3F %.3F %.3F rg %.2F %.2F %.2F %.2F re f Q', $fill[0] / 255, $fill[1] / 255, $fill[2] / 255, $x, $pdfY, $width, $height);
        }
        $this->current[] = $this->formatNumber($x) . ' ' . $this->formatNumber($pdfY) . ' ' . $this->formatNumber($width) . ' ' . $this->formatNumber($height) . ' re S';
    }

    private function image(float $x, float $y, float $width, float $height): void
    {
        if (!$this->logo) {
            return;
        }

        $pdfY = $this->height - $y - $height;
        $this->current[] = 'q ' . $this->formatNumber($width) . ' 0 0 ' . $this->formatNumber($height) . ' ' . $this->formatNumber($x) . ' ' . $this->formatNumber($pdfY) . ' cm /Im1 Do Q';
    }

    private function loadLogo(): ?array
    {
        $path = dirname(__DIR__) . '/logo.png';
        if (!is_file($path)) {
            return null;
        }

        $info = getimagesize($path);
        if (!$info) {
            return null;
        }

        $mime = $info['mime'] ?? '';
        $data = $mime === 'image/jpeg' ? file_get_contents($path) : $this->convertLogoToJpeg($path, $mime);
        if ($data === false || $data === null) {
            return null;
        }

        return [
            'width' => (int) $info[0],
            'height' => (int) $info[1],
            'data' => $data,
        ];
    }

    private function convertLogoToJpeg(string $path, string $mime): ?string
    {
        if (!function_exists('imagecreatetruecolor')) {
            return null;
        }

        if ($mime === 'image/png' && function_exists('imagecreatefrompng')) {
            $source = imagecreatefrompng($path);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $source = imagecreatefromwebp($path);
        } else {
            return null;
        }

        if (!$source) {
            return null;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($width, $height);
        if (!$canvas) {
            imagedestroy($source);
            return null;
        }

        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);

        ob_start();
        imagejpeg($canvas, null, 90);
        $data = ob_get_clean();

        imagedestroy($canvas);
        imagedestroy($source);

        return $data === false ? null : $data;
    }

    private function cleanText(string $text): string
    {
        $converted = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
        return $converted === false ? $text : $converted;
    }

    private function escape(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }

    private function formatNumber(float $number): string
    {
        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}

function send_pdf_report(string $title, array $columns, array $rows, string $filename): void
{
    date_default_timezone_set('Asia/Jakarta');
    $generatedAt = format_tanggal_indonesia();
    $pdf = new SimplePdfReport($title, $generatedAt);
    $pdf->table($columns, $rows);
    $pdf->signature();

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '-' . date('Ymd-His') . '.pdf"');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo $pdf->output();
    exit;
}

function format_tanggal_indonesia(?int $timestamp = null): string
{
    $timestamp ??= time();
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember',
    ];

    return $hari[date('l', $timestamp)] . ', ' . date('j', $timestamp) . ' ' . $bulan[date('F', $timestamp)] . ' ' . date('Y', $timestamp);
}
