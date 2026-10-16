<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ReelController extends Controller
{
    /** หน้าหลัก: Feed + ค้นหา + ฟิลเตอร์ */
    public function index(Request $request)
    {
        $reels = Reel::with(['brand', 'primaryImage', 'user'])
            ->where('status', 'available')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%$v%")
                ->orWhere('model_name', 'like', "%$v%")))
            ->when($request->brand, fn ($q, $v) => $q->where('brand_id', $v))
            ->when($request->type, fn ($q, $v) => $q->where('reel_type', $v))
            ->when($request->province, fn ($q, $v) => $q->where('location_province', 'like', "%$v%"))
            ->when($request->min_price, fn ($q, $v) => $q->where('price', '>=', $v))
            ->when($request->max_price, fn ($q, $v) => $q->where('price', '<=', $v))
            ->when($request->min_condition, fn ($q, $v) => $q->where('condition_percentage', '>=', $v))
            ->when(true, fn ($q) => match ($request->sort) {
                'price_asc' => $q->orderBy('price'),
                'price_desc' => $q->orderByDesc('price'),
                'popular' => $q->orderByDesc('view_count'),
                default => $q->latest(),
            })
            ->paginate(12)
            ->withQueryString();

        return view('reels.index', [
            'reels' => $reels,
            'brands' => Brand::orderBy('name')->get(),
            'favoriteIds' => $request->user()->favorites()->pluck('reels.id')->all(),
        ]);
    }

    /** รอกของฉัน (จัดการ Edit/Delete) */
    public function mine(Request $request)
    {
        $reels = $request->user()->reels()->with(['brand', 'primaryImage'])->latest()->paginate(12);
        return view('reels.mine', compact('reels'));
    }

    public function create()
    {
        return view('reels.create', [
            'brands' => Brand::orderBy('name')->get(),
            'reel' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(true));

        $reel = $request->user()->reels()->create(collect($data)->except('images')->all());
        $this->saveImages($reel, $request->file('images', []), false);

        return redirect()->route('reels.show', $reel)->with('success', 'ลงขายรอกเรียบร้อยแล้ว');
    }

    public function show(Request $request, Reel $reel)
    {
        if ($reel->user_id !== $request->user()->id) {
            $reel->increment('view_count');
        }

        $reel->load(['brand', 'images', 'user.reviewsReceived.reviewer']);

        return view('reels.show', [
            'reel' => $reel,
            'isFavorite' => $request->user()->favorites()->where('reels.id', $reel->id)->exists(),
            'myReview' => $reel->user->reviewsReceived->firstWhere('reviewer_id', $request->user()->id),
        ]);
    }

    public function edit(Request $request, Reel $reel)
    {
        $this->authorizeOwner($request, $reel);
        $reel->load('images');
        return view('reels.edit', [
            'reel' => $reel,
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Reel $reel)
    {
        $this->authorizeOwner($request, $reel);

        $rules = $this->rules(false) + ['status' => ['required', Rule::in(['available', 'sold', 'hidden'])]];
        $data = $request->validate($rules);

        $reel->update(collect($data)->except(['images', 'delete_images'])->all());

        // ลบรูปที่เลือก
        foreach ($reel->images()->whereIn('id', $request->input('delete_images', []))->get() as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        $remaining = $reel->images()->count();
        $newFiles = $request->file('images', []);
        if ($remaining + count($newFiles) > 6) {
            return back()->withErrors(['images' => 'อัปโหลดรูปได้สูงสุด 6 รูปต่อรอก'])->withInput();
        }

        $this->saveImages($reel, $newFiles, $reel->images()->where('is_primary', true)->exists());

        // ถ้าไม่มีรูปปก ให้ตั้งรูปแรกเป็นรูปปก
        if (!$reel->images()->where('is_primary', true)->exists()) {
            $reel->images()->first()?->update(['is_primary' => true]);
        }

        return redirect()->route('reels.show', $reel)->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(Request $request, Reel $reel)
    {
        $this->authorizeOwner($request, $reel);

        foreach ($reel->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $reel->delete();

        return redirect()->route('reels.mine')->with('success', 'ลบรายการเรียบร้อย');
    }

    // ---------- helpers ----------

    private function authorizeOwner(Request $request, Reel $reel): void
    {
        abort_unless($reel->user_id === $request->user()->id || $request->user()->isAdmin(), 403);
    }

    private function rules(bool $imagesRequired): array
    {
        return [
            'brand_id' => ['required', 'exists:brands,id'],
            'title' => ['required', 'string', 'max:150'],
            'model_name' => ['required', 'string', 'max:100'],
            'reel_type' => ['required', Rule::in(array_keys(Reel::TYPES))],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'condition_percentage' => ['required', 'integer', 'between:1,100'],
            'gear_ratio' => ['nullable', 'string', 'max:20'],
            'ball_bearings' => ['nullable', 'integer', 'between:0,30'],
            'max_drag_kg' => ['nullable', 'numeric', 'between:0,200'],
            'weight_g' => ['nullable', 'integer', 'between:1,5000'],
            'description' => ['nullable', 'string', 'max:5000'],
            'location_province' => ['required', 'string', 'max:100'],
            'location_district' => ['nullable', 'string', 'max:100'],
            'images' => [$imagesRequired ? 'required' : 'nullable', 'array', 'max:6'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'delete_images' => ['nullable', 'array'],
        ];
    }

    private function saveImages(Reel $reel, array $files, bool $hasPrimary): void
    {
        foreach ($files as $i => $file) {
            $reel->images()->create([
                'image_path' => $file->store('reels', 'public'),
                'is_primary' => !$hasPrimary && $i === 0,
            ]);
        }
    }
}