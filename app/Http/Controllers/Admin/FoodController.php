<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller {

    private function storeFoodImage(Request $request, ?string $currentPath = null): ?string
    {
        if (!$request->hasFile('photo')) {
            return $currentPath;
        }

        if ($currentPath) {
            Storage::disk('public')->delete($currentPath);
        }

        return $request->file('photo')->store('foods', 'public');
    }

    public function index(Request $request) {
        $foods = Food::when($request->search, fn($q) => $q->where('name','like',"%{$request->search}%"))
            ->when($request->meal_type, fn($q) => $q->where('meal_type', $request->meal_type))
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(20);
        return view('admin.foods.index', compact('foods'));
    }

    public function create() { return view('admin.foods.create'); }

    public function store(Request $request) {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'calories'     => 'required|numeric|min:0',
            'proteins'     => 'nullable|numeric|min:0',
            'fat'          => 'nullable|numeric|min:0',
            'carbohydrate' => 'nullable|numeric|min:0',
            'composition'  => 'nullable|string',
            'origin'       => 'nullable|string|max:100',
            'region'       => 'nullable|string|max:100',
            'meal_type'    => 'required|in:breakfast,lunch,dinner,snack',
            'photo'        => 'nullable|image|max:4096',
        ]);

        $data['image_url'] = $this->storeFoodImage($request);

        Food::create($data);
        return redirect()->route('admin.foods.index')->with('success', 'Makanan berhasil ditambahkan!');
    }

    public function edit(Food $food) { return view('admin.foods.edit', compact('food')); }

    public function update(Request $request, Food $food) {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'calories'     => 'required|numeric|min:0',
            'proteins'     => 'nullable|numeric|min:0',
            'fat'          => 'nullable|numeric|min:0',
            'carbohydrate' => 'nullable|numeric|min:0',
            'composition'  => 'nullable|string',
            'origin'       => 'nullable|string|max:100',
            'region'       => 'nullable|string|max:100',
            'meal_type'    => 'required|in:breakfast,lunch,dinner,snack',
            'is_active'    => 'boolean',
            'photo'        => 'nullable|image|max:4096',
        ]);

        $data['image_url'] = $this->storeFoodImage($request, $food->image_url);

        $food->update($data);
        return redirect()->route('admin.foods.index')->with('success', 'Data makanan diperbarui!');
    }

    public function destroy(Food $food) {
        $food->delete();
        return back()->with('success', 'Makanan berhasil dihapus.');
    }
}