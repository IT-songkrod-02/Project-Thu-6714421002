@php
    $v = fn ($key, $default = null) => old($key, optional($reel)->$key ?? $default);
    $provinces = ['กรุงเทพมหานคร','ระยอง','ชลบุรี','จันทบุรี','ตราด','เชียงใหม่','ขอนแก่น','นครราชสีมา','ภูเก็ต','สงขลา','สุราษฎร์ธานี','ประจวบคีรีขันธ์','เพชรบุรี','นนทบุรี','ปทุมธานี','สมุทรปราการ','นครศรีธรรมราช','อุดรธานี'];
@endphp

<form method="POST" enctype="multipart/form-data"
      action="{{ $reel ? route('reels.update', $reel) : route('reels.store') }}"
      x-data="{ step: {{ $errors->any() ? 1 : 1 }}, previews: [] }" class="mx-auto max-w-3xl">
    @csrf
    @if($reel) @method('PUT') @endif

    {{-- Stepper --}}
    <div class="mb-8 flex items-center justify-between">
        @foreach(['ข้อมูลพื้นฐาน','สเปครอก','สภาพ & สถานที่','รูปภาพ'] as $i => $label)
            <button type="button" @click="step = {{ $i+1 }}" class="flex flex-1 flex-col items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-full border text-sm font-medium transition"
                      :class="step >= {{ $i+1 }} ? 'border-gold bg-gold text-white shadow-md' : 'border-stone-300 text-stone-400'">{{ $i+1 }}</span>
                <span class="text-xs" :class="step == {{ $i+1 }} ? 'text-gold-dark font-medium' : 'text-stone-400'">{{ $label }}</span>
            </button>
        @endforeach
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-600">
            <p class="mb-1 font-medium">กรุณาตรวจสอบข้อมูล:</p>
            <ul class="list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card-lux p-8">
        {{-- STEP 1 --}}
        <div x-show="step === 1" class="space-y-5">
            <h2 class="font-serif text-xl">ข้อมูลพื้นฐาน</h2>
            <div>
                <label class="label-lux">หัวข้อที่ลงขาย *</label>
                <input name="title" value="{{ $v('title') }}" class="input-lux" placeholder="เช่น Shimano Stella SW 8000HG สภาพสวย">
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label-lux">ยี่ห้อ *</label>
                    <select name="brand_id" class="input-lux">
                        <option value="">-- เลือกยี่ห้อ --</option>
                        @foreach($brands as $b)<option value="{{ $b->id }}" @selected($v('brand_id') == $b->id)>{{ $b->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label-lux">ชื่อรุ่น *</label>
                    <input name="model_name" value="{{ $v('model_name') }}" class="input-lux" placeholder="เช่น Stella SW 8000HG">
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label-lux">ประเภทรอก *</label>
                    <select name="reel_type" class="input-lux">
                        @foreach(\App\Models\Reel::TYPES as $k => $t)<option value="{{ $k }}" @selected($v('reel_type') == $k)>{{ $t }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="label-lux">ราคา (บาท) *</label>
                    <input type="number" name="price" value="{{ $v('price') }}" class="input-lux">
                </div>
            </div>
        </div>

        {{-- STEP 2 --}}
        <div x-show="step === 2" x-cloak class="space-y-5">
            <h2 class="font-serif text-xl">สเปครอก</h2>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label class="label-lux">อัตราทด (Gear Ratio)</label><input name="gear_ratio" value="{{ $v('gear_ratio') }}" class="input-lux" placeholder="7.1:1"></div>
                <div><label class="label-lux">จำนวนลูกปืน</label><input type="number" name="ball_bearings" value="{{ $v('ball_bearings') }}" class="input-lux"></div>
                <div><label class="label-lux">แรงเบรกสูงสุด (kg)</label><input type="number" step="0.1" name="max_drag_kg" value="{{ $v('max_drag_kg') }}" class="input-lux"></div>
                <div><label class="label-lux">น้ำหนัก (g)</label><input type="number" name="weight_g" value="{{ $v('weight_g') }}" class="input-lux"></div>
            </div>
        </div>

        {{-- STEP 3 --}}
        <div x-show="step === 3" x-cloak class="space-y-5">
            <h2 class="font-serif text-xl">สภาพ &amp; สถานที่</h2>
            <div x-data="{ c: {{ (int) $v('condition_percentage', 90) }} }">
                <label class="label-lux">สภาพสินค้า: <span class="font-semibold text-gold-dark" x-text="c + '%'"></span></label>
                <input type="range" min="1" max="100" name="condition_percentage" x-model="c" class="w-full accent-[#D4AF37]">
            </div>
            <div>
                <label class="label-lux">รายละเอียดเพิ่มเติม / ตำหนิ</label>
                <textarea name="description" rows="5" class="input-lux" placeholder="ประวัติการใช้งาน ตำหนิ อุปกรณ์ที่แถม ฯลฯ">{{ $v('description') }}</textarea>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="label-lux">จังหวัด *</label>
                    <input name="location_province" list="provinces" value="{{ $v('location_province') }}" class="input-lux">
                    <datalist id="provinces">@foreach($provinces as $p)<option value="{{ $p }}">@endforeach</datalist>
                </div>
                <div><label class="label-lux">อำเภอ / เขต</label><input name="location_district" value="{{ $v('location_district') }}" class="input-lux"></div>
            </div>
            @if($reel)
                <div>
                    <label class="label-lux">สถานะ</label>
                    <select name="status" class="input-lux">
                        <option value="available" @selected($v('status') == 'available')>พร้อมขาย</option>
                        <option value="sold" @selected($v('status') == 'sold')>ขายแล้ว</option>
                        <option value="hidden" @selected($v('status') == 'hidden')>ซ่อนประกาศ</option>
                    </select>
                </div>
            @endif
        </div>

        {{-- STEP 4 --}}
        <div x-show="step === 4" x-cloak class="space-y-5">
            <h2 class="font-serif text-xl">รูปภาพสินค้า <span class="text-sm text-stone-400">(สูงสุด 6 รูป, รูปแรก = รูปปก)</span></h2>

            @if($reel && $reel->images->count())
                <div class="grid grid-cols-3 gap-3">
                    @foreach($reel->images as $img)
                        <label class="relative block overflow-hidden rounded-lg border border-gold/30">
                            <img src="{{ $img->url }}" class="aspect-square w-full object-cover">
                            @if($img->is_primary)<span class="absolute left-1 top-1 rounded bg-gold px-1.5 text-[10px] text-white">ปก</span>@endif
                            <span class="absolute inset-x-0 bottom-0 flex items-center gap-1 bg-white/90 px-2 py-1 text-xs">
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="accent-red-500"> ลบ
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif

            <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-gold/50 bg-gold-champagne/50 px-6 py-10 text-center transition hover:bg-gold-champagne">
                <span class="text-3xl text-gold">⬆</span>
                <span class="mt-2 text-sm text-stone-600">คลิกเพื่อเลือกรูปภาพ (JPG, PNG, WEBP ไม่เกิน 4MB)</span>
                <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                       @change="previews = [...$event.target.files].map(f => URL.createObjectURL(f))">
            </label>
            <div class="grid grid-cols-3 gap-3" x-show="previews.length">
                <template x-for="p in previews"><img :src="p" class="aspect-square w-full rounded-lg object-cover"></template>
            </div>
        </div>
    </div>

    {{-- Controls --}}
    <div class="mt-6 flex items-center justify-between">
        <button type="button" x-show="step > 1" @click="step--" class="btn-outline">← ย้อนกลับ</button>
        <span x-show="step === 1"></span>
        <button type="button" x-show="step < 4" @click="step++" class="btn-gold">ถัดไป →</button>
        <button type="submit" x-show="step === 4" class="btn-gold">{{ $reel ? 'บันทึกการแก้ไข' : 'ลงขายเลย' }}</button>
    </div>
</form>

<style>[x-cloak]{display:none !important}</style>