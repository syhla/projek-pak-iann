<?php

namespace App\Http\Controllers;

use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
        ]);

        $path = $request->file('image')->store('slides', 'public');

        Slide::create([
            'image_path' => $path,
        ]);

        return redirect()->route('welcome')->with('success', 'Slide berhasil di-upload.');
    }

    public function edit(Slide $slide)
    {
        return redirect()->route('welcome')->with('editSlide', $slide);
    }

    public function update(Request $request, Slide $slide)
    {
        $request->validate([
            'image' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }

            $path = $request->file('image')->store('slides', 'public');
            $slide->image_path = $path;
        }

        $slide->save();

        return redirect()->route('welcome')->with('success', 'Slide berhasil diperbarui.');
    }

    public function destroy(Slide $slide)
    {
        if (Storage::disk('public')->exists($slide->image_path)) {
            Storage::disk('public')->delete($slide->image_path);
        }

        $slide->delete();

        return redirect()->route('welcome')->with('success', 'Slide berhasil dihapus.');
    }
}
