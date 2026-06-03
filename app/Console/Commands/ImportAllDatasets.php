<?php
namespace App\Console\Commands;

use App\Models\Food;
use App\Models\MenuRecommendation;
use Illuminate\Console\Command;

class ImportAllDatasets extends Command
{
    protected $signature   = 'nutrigo:import-all {--replace : Hapus data food lama sebelum import}';
    protected $description = 'Import semua dataset CSV ke database';

    public function handle(): void
    {
        $this->info('🚀 Mulai import semua dataset NutriGo...');
        $this->newLine();

        if ($this->option('replace')) {
            MenuRecommendation::query()->delete();
            Food::query()->delete();
            $this->warn('⚠️ Data food lama dihapus sebelum import.');
            $this->newLine();
        }

        // 1. Import makanan utama
        $this->importFoodFinal();

        // 3. Tampilkan ringkasan
        $this->newLine();
        $this->table(
            ['Dataset', 'Status', 'Jumlah'],
            [
                ['db_final_food_javanese.csv', '✅ Imported', Food::count().' makanan'],
                ['bodyfat_cleaned.csv',    'ℹ️ Tidak diimport', 'Rumus BMI dihitung di PHP langsung'],
                ['train_data.csv',         'ℹ️ Tidak diimport', 'Logika rekomendasi ada di PHP service'],
            ]
        );

        $this->newLine();
        $this->info('✅ Import selesai! Sistem siap digunakan.');
    }

    private function importFoodFinal(): void
    {
        $this->info('📂 Import db_final_food_javanese.csv...');
        $path = database_path('data/db_final_food_javanese.csv');

        if (!file_exists($path)) {
            $this->error("File tidak ada: {$path}");
            $this->line("Salin file CSV ke: database/data/db_final_food_javanese.csv");
            return;
        }

        $handle  = fopen($path, 'r');
        $headers = fgetcsv($handle, 0, ';');
        $headers = array_map(fn($h) => strtolower(trim($h)), $headers);

        $bar = $this->output->createProgressBar();
        $bar->start();

        $count = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 3) continue;
            $data = array_combine($headers, array_pad(array_map('trim', $row), count($headers), ''));
            $name = $data['name'] ?? '';
            if (empty($name)) continue;

            $mealType = $this->detectMealType($name, $data['composition'] ?? '', (float)($data['calories'] ?? 0));

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
            $count++;
            $bar->advance();
        }

        fclose($handle);
        $bar->finish();
        $this->newLine();
        $this->info("   → {$count} makanan berhasil diimport");
    }

    private function detectMealType(string $name, string $composition, float $calories): string
    {
        $n = strtolower($name);
        $c = strtolower($composition);

        $breakfast = ['roti','bubur','oatmeal','sereal','lontong','ketupat','nasi uduk'];
        foreach ($breakfast as $w) { if (str_contains($n, $w)) return 'breakfast'; }

        $snack = ['keripik','biskuit','kue','agar','jelly','permen','jus','teh ','kopi ','susu','minuman'];
        foreach ($snack as $w) { if (str_contains($n, $w) || str_contains($c, $w)) return 'snack'; }
        if ($calories > 0 && $calories < 80) return 'snack';

        $dinner = ['sup ','soto','rawon','gulai','rendang','opor','semur','pindang'];
        foreach ($dinner as $w) { if (str_contains($n, $w)) return 'dinner'; }

        return 'lunch';
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

}