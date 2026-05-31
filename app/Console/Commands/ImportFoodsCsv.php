<?php
namespace App\Console\Commands;

use App\Models\Food;
use App\Models\MenuRecommendation;
use Illuminate\Console\Command;

class ImportFoodsCsv extends Command
{
    protected $signature   = 'nutrigo:import-foods {file? : Path ke file CSV} {--replace : Hapus data food lama sebelum import}';
    protected $description = 'Import data makanan dari file CSV';

    public function handle(): void
    {
        $filePath = $this->argument('file') ?? database_path('data/db_final_food_javanese.csv');

        if (!file_exists($filePath)) {
            $this->error("File tidak ditemukan: {$filePath}");
            $this->line("Contoh: php artisan nutrigo:import-foods database/data/db_final_food_javanese.csv --replace");
            return;
        }

        if ($this->option('replace')) {
            MenuRecommendation::query()->delete();
            Food::query()->delete();
            $this->warn('⚠️ Data makanan lama dihapus sebelum import.');
        }

        $handle  = fopen($filePath, 'r');
        $headers = fgetcsv($handle, 0, ';');

        if (!$headers) {
            $this->error('File CSV kosong atau tidak valid.');
            return;
        }

        // Normalize header keys
        $headers = array_map(fn($h) => strtolower(trim($h)), $headers);

        $this->info("📂 Membaca file: {$filePath}");
        $this->info("📋 Kolom ditemukan: " . implode(', ', $headers));

        $imported = 0;
        $skipped  = 0;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            $data = array_combine($headers, array_map('trim', $row));

            $name = $data['name'] ?? '';
            if (empty($name)) { $skipped++; continue; }

            $mealType = $this->detectMealType(strtolower($name), $data['meal_type'] ?? '');

            Food::updateOrCreate(
                ['name' => $name],
                [
                    'calories'     => $this->normalizeNumber($data['calories'] ?? 0),
                    'proteins'     => $this->normalizeNumber($data['proteins'] ?? 0),
                    'fat'          => $this->normalizeNumber($data['fat'] ?? 0),
                    'carbohydrate' => $this->normalizeNumber($data['carbohydrate'] ?? 0),
                    'composition'  => $data['composition'] ?? null,
                    'origin'       => $data['origin'] ?? null,
                    'food_category'=> $data['food_category'] ?? $this->detectFoodCategory($name, $data['composition'] ?? ''),
                    'is_national'  => $this->normalizeBoolean($data['is_national'] ?? false),
                    'region'       => $this->normalizeRegion($data['region'] ?? null, $data['origin'] ?? null, $data['is_national'] ?? false),
                    'meal_type'    => $mealType,
                    'is_active'    => true,
                ]
            );
            $imported++;
        }

        fclose($handle);

        $this->info("✅ Import selesai!");
        $this->table(['Hasil', 'Jumlah'], [
            ['Berhasil diimport', $imported],
            ['Dilewati (kosong)', $skipped],
        ]);
    }

    private function detectMealType(string $name, string $existing = ''): string
    {
        if (!empty($existing) && in_array($existing, ['breakfast','lunch','dinner','snack'])) {
            return $existing;
        }

        $breakfast = ['roti','bubur','oatmeal','sereal','pancake','toast','cornflake'];
        $snack     = ['snack','keripik','biskuit','kue','jus','smoothie','minuman'];

        foreach ($breakfast as $kw) {
            if (str_contains($name, $kw)) return 'breakfast';
        }
        foreach ($snack as $kw) {
            if (str_contains($name, $kw)) return 'snack';
        }

        return 'lunch';
    }

    private function detectFoodCategory(string $name, string $composition): string
    {
        $text = strtolower(trim($name . ' ' . $composition));

        $categories = [
            'fruit' => ['apel','pisang','mangga','jeruk','pepaya','semangka','melon','salak','rambutan','alpukat','nanas','sirsak','manggis','anggur'],
            'vegetable' => ['sayur','bayam','kangkung','sawi','wortel','timun','terong','buncis','kol','daun','labu','selada'],
            'drink' => ['jus','teh','kopi','susu','wedang','es ','minuman','air ', 'sirup'],
            'snack' => ['kue','keripik','biskuit','permen','gorengan','martabak','pastel','risoles','roti','camilan','jajanan','pukis'],
        ];

        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $category;
                }
            }
        }

        return 'main_meal';
    }

    private function normalizeNumber(mixed $value): float
    {
        return (float) str_replace(',', '.', trim((string) $value));
    }

    private function normalizeBoolean(mixed $value): bool
    {
        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'yes', 'y'], true);
    }

    private function normalizeRegion(mixed $region, ?string $origin = null, mixed $isNational = false): ?string
    {
        $region = trim((string) $region);

        if ($this->normalizeBoolean($isNational) || $region === 'Nasional') {
            return 'Nasional';
        }

        return $region !== '' ? $region : $origin;
    }
}