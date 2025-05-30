<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\BestSeller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // CRUD Slide
    public function indexSlides()
    {
        $slides = Slide::all();
        return view('admin.slides.index', compact('slides'));
    }

    public function createSlide()
    {
        return view('admin.slides.create');
    }

    public function storeSlide(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('slides', 'public');
        }

        Slide::create($data);
        return redirect()->route('slides.index')->with('success', 'Slide created successfully.');
    }

    public function editSlide(Slide $slide)
    {
        return view('admin.slides.edit', compact('slide'));
    }

    public function updateSlide(Request $request, Slide $slide)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }
            $data['image'] = $request->file('image')->store('slides', 'public');
        }

        $slide->update($data);
        return redirect()->route('slides.index')->with('success', 'Slide updated successfully.');
    }

    public function destroySlide(Slide $slide)
    {
        if ($slide->image) {
            Storage::disk('public')->delete($slide->image);
        }

        $slide->delete();
        return redirect()->route('slides.index')->with('success', 'Slide deleted.');
    }

    // CRUD Best Seller
    public function indexBestSellers()
    {
        $bestSellers = BestSeller::all();
        return view('admin.bestSellers.index', compact('bestSellers'));
    }

    public function createBestSeller()
    {
        return view('admin.bestSellers.create');
    }

    public function storeBestSeller(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('bestSellers', 'public');
        }

        BestSeller::create($data);
        return redirect()->route('bestSellers.index')->with('success', 'Best Seller created successfully.');
    }

    public function editBestSeller(BestSeller $bestSeller)
    {
        return view('admin.bestSellers.edit', compact('bestSeller'));
    }

    public function updateBestSeller(Request $request, BestSeller $bestSeller)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($bestSeller->image) {
                Storage::disk('public')->delete($bestSeller->image);
            }
            $data['image'] = $request->file('image')->store('bestSellers', 'public');
        }

        $bestSeller->update($data);
        return redirect()->route('bestSellers.index')->with('success', 'Best Seller updated successfully.');
    }

    public function destroyBestSeller(BestSeller $bestSeller)
    {
        if ($bestSeller->image) {
            Storage::disk('public')->delete($bestSeller->image);
        }

        $bestSeller->delete();
        return redirect()->route('bestSellers.index')->with('success', 'Best Seller deleted.');
    }
}
