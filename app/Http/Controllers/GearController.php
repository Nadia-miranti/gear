<?php

namespace App\Http\Controllers;

use App\Models\Gear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gears = Gear::latest()->paginate(10);
        return view('admin.gears.index', compact('gears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.gears.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|in:Tenda,Alat Masak,Penerangan,Sleeping Gear,Aksesoris',
            'rental_price' => 'required|numeric',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('gears', 'public');
        }

        Gear::create([
            'name'         => $request->name,
            'category'     => $request->category,
            'rental_price' => $request->rental_price,
            'description'  => $request->description,
            'image'        => $imagePath,
        ]);

        return redirect()->route('gears.index')->with('success', 'Data perlengkapan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gear $gear)
    {
        return redirect()->route('gears.edit', $gear->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gear $gear)
    {
        return view('admin.gears.edit', compact('gear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gear $gear)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|in:Tenda,Alat Masak,Penerangan,Sleeping Gear,Aksesoris',
            'rental_price' => 'required|numeric',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $gear->image;

        if ($request->hasFile('image')) {
            if ($gear->image && Storage::disk('public')->exists($gear->image)) {
                Storage::disk('public')->delete($gear->image);
            }
            $imagePath = $request->file('image')->store('gears', 'public');
        }

        $gear->update([
            'name'         => $request->name,
            'category'     => $request->category,
            'rental_price' => $request->rental_price,
            'description'  => $request->description,
            'image'        => $imagePath,
        ]);

        return redirect()->route('gears.index')->with('success', 'Data perlengkapan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gear $gear)
    {
        if ($gear->image && Storage::disk('public')->exists($gear->image)) {
            Storage::disk('public')->delete($gear->image);
        }

        $gear->delete();
        return redirect()->route('gears.index')->with('success', 'Data perlengkapan berhasil dihapus!');
    }
}
