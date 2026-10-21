<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Landing page: featured medicines + categories.
     */
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $featuredMedicines = Medicine::with('category')->where('stock', '>', 0)->latest()->take(8)->get();

        return view('customer.home', compact('categories', 'featuredMedicines'));
    }

    /**
     * Medicine catalogue with search, category filter, and sorting.
     */
    public function medicines(Request $request)
    {
        $sort = $request->query('sort', 'latest');

        $query = Medicine::with('category')
            ->search($request->query('q'))
            ->inCategory($request->query('category'));

        $query = match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name' => $query->orderBy('name', 'desc'),
            default => $query->latest(),
        };

        $medicines = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('customer.medicines.index', compact('medicines', 'categories', 'sort'));
    }

    /**
     * Medicine detail page.
     */
    public function show(Medicine $medicine)
    {
        $related = Medicine::where('category_id', $medicine->category_id)
            ->where('id', '!=', $medicine->id)
            ->take(4)
            ->get();

        return view('customer.medicines.show', compact('medicine', 'related'));
    }
}
