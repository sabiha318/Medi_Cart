<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $medicines = Medicine::with('category')
            ->search($request->query('q'))
            ->inCategory($request->query('category'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.medicines.index', compact('medicines', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.medicines.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateMedicine($request);

        $medicine = DB::transaction(function () use ($request, $validated) {
            $existing = Medicine::query()
                ->where('category_id', $validated['category_id'])
                ->where('name', $validated['name'])
                ->where('brand', $validated['brand'])
                ->where('price', $validated['price'])
                ->where('unit', $validated['unit'])
                ->where('tablets_per_strip', $validated['tablets_per_strip'] ?? null)
                ->where('description', $validated['description'] ?? null)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                $existing->increment('stock', $validated['stock']);

                return $existing;
            }

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('medicines', 'public');
            }

            $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(6);

            return Medicine::create($validated);
        });

        $message = $medicine->wasRecentlyCreated
            ? 'Medicine added successfully.'
            : 'Existing medicine found. Stock increased by '.$validated['stock'].'.';

        return redirect()->route('admin.medicines.index')
            ->with('success', $message);
    }

    public function edit(Medicine $medicine)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.medicines.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $this->validateMedicine($request, $medicine->id);

        if ($request->hasFile('image')) {
            if ($medicine->image) {
                Storage::disk('public')->delete($medicine->image);
            }
            $validated['image'] = $request->file('image')->store('medicines', 'public');
        }

        $medicine->update($validated);

        return redirect()->route('admin.medicines.index')
            ->with('success', 'Medicine updated successfully.');
    }

    public function destroy(Medicine $medicine)
    {
        if ($medicine->image) {
            Storage::disk('public')->delete($medicine->image);
        }

        $medicine->delete();

        return back()->with('success', 'Medicine deleted successfully.');
    }

    private function validateMedicine(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'in:strip,bottle,box,others'],
            'tablets_per_strip' => ['nullable', 'required_if:unit,strip', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
