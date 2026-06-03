<?php
namespace Database\Seeders;

use App\Models\Food;
use App\Models\MenuRecommendation;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // ── Replace data with db_final_food_javanese.csv ───────────
        MenuRecommendation::query()->delete();
        Food::query()->delete();

        $this->importFoodFinal();

        $this->command->info('✅ Total makanan: ' . Food::count());
    }

    private function importFoodFinal(): void
    {
        $path = database_path('data/db_final_food_javanese.csv');
        if (!file_exists($path)) {
            $this->command->error("❌ File tidak ditemukan: {$path}");
            return;
        }

        $handle  = fopen($path, 'r');
        $headers = fgetcsv($handle, 0, ';'); // separator titik koma
        $headers = array_map(fn($h) => strtolower(trim($h)), $headers);

        $count = 0;
        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < count($headers)) continue;
            $data = array_combine($headers, array_map('trim', $row));

            $name = $data['name'] ?? '';
            if (empty($name)) continue;

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
                    'meal_type'    => $this->detectMealType($data),
                    'is_active'    => true,
                ]
            );
            $count++;
        }
        fclose($handle);
        $this->command->info("✅ Import db_final_food_javanese: {$count} makanan");
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

    private function detectIsNational(string $name, string $composition, ?string $origin = null): bool
    {
        $text = strtolower(trim($name . ' ' . $composition));

        $nationalKeywords = [
            'apel', 'pisang', 'alpukat', 'roti', 'telur', 'tempe', 'tahu',
            'bubur', 'bakso', 'soto', 'mie', 'bihun', 'sup', 'sayur', 'ayam goreng',
            'teh', 'kopi', 'jus', 'susu', 'nasi', 'goreng', 'kentang', 'jagung',
        ];

        $regionalKeywords = [
            'rawon', 'gudeg', 'rujak cingur', 'bakpia', 'brem', 'brongkos', 'buntil',
            'pempek', 'pindang', 'coto', 'sego', 'lontong balap', 'pecel', 'serabi',
            'sate klathak', 'soto lamongan', 'soto kudus', 'ayam goreng kalasan',
        ];

        foreach ($regionalKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return false;
            }
        }

        foreach ($nationalKeywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }

        return $origin === null;
    }

    private function detectMealType(array $data): string
    {
        $name        = strtolower(trim($data['name'] ?? ''));
        $composition = strtolower(trim($data['composition'] ?? ''));
        $calories    = (float)($data['calories'] ?? 0);

        // Sarapan: makanan ringan < 300 kalori atau nama mengandung kata sarapan
        $breakfastWords = ['roti','bubur','oatmeal','sereal','pancake','nasi uduk','ketupat','lontong'];
        foreach ($breakfastWords as $w) {
            if (str_contains($name, $w)) return 'breakfast';
        }

        // Snack: kalori sangat rendah < 100 atau kata snack
        $snackWords = ['keripik','biskuit','kue','agar','jelly','permen','minuman','jus','teh','kopi','susu'];
        foreach ($snackWords as $w) {
            if (str_contains($name, $w) || str_contains($composition, $w)) return 'snack';
        }
        if ($calories > 0 && $calories < 100) return 'snack';

        // Makan malam: makanan berat
        $dinnerWords = ['sup','soto','rawon','gulai','rendang','opor','semur'];
        foreach ($dinnerWords as $w) {
            if (str_contains($name, $w)) return 'dinner';
        }

        // Default lunch
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
}