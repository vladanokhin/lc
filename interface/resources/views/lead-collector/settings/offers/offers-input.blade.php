@php $offerTrackers = isset($offer) ? $offer->trackers->pluck('id')->toArray() : []  @endphp

<div class="flex flex-col md:flex-row md:space-x-4">
    <input type="hidden" value="{{ route('offers.index') }}" id="js-offers-index-url">
    <!-- Start First Column -->
    <div class="md:w-1/2 space-y-4">
        <div class="h-20">
            <label for="url">Url</label>
            <input value="{{ $offer->url ?? old('url') }}" type="url" name="url" id="offer-url" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('url') ring-1 ring-red-400 @enderror">
            @error('url')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20" id="js-select-tracker">
            <label for="trackers">Trackers</label>
            <select name="trackers[]" id="offer-select-trackers" multiple="multiple" class="multi-select shadow appearance-none border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('trackers') ring-1 ring-red-400 @enderror">
                @foreach($trackers as $tracker)
                    <option {{ in_array($tracker->id,  $offerTrackers ?? old('trackers') ?? []) ? 'selected' : '' }} value="{{ $tracker->id }}">{{ $tracker->t_url }}</option>
                @endforeach
            </select>
            @error('trackers')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="geo">Geo</label>
            <input value="{{ $offer->geo ?? old('geo') }}" type="text" name="geo" id="offer-geo" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('geo') ring-1 ring-red-400 @enderror">
            @error('geo')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="language">Language</label>
            <input value="{{ $offer->language ?? old('language') }}" type="text" name="language" id="offer-language" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('language') ring-1 ring-red-400 @enderror">
            @error('language')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="type">Type</label>
            <input value="{{ $offer->type ?? old('type') }}" type="text" name="type" id="offer-type-first" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('type') ring-1 ring-red-400 @enderror">
            @error('type')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="category">Category</label>
            <input value="{{ $offer->category ?? old('category') }}" type="text" name="category" id="offer-category" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('category') ring-1 ring-red-400 @enderror">
            @error('category')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <!-- End First Column -->

    <!-- Start Second Column -->
    <div class="md:w-1/2 space-y-4">
        <div class="h-20">
            <label for="form-factor">Form factor</label>
            <input value="{{ $offer->form_factor ?? old('form_factor') }}" type="text" name="form_factor" id="offer-form-factor" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('form_factor') ring-1 ring-red-400 @enderror">
            @error('form_factor')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="lp-numbering">LP numbering</label>
            <input value="{{ $offer->lp_numbering ?? old('lp_numbering') }}" type="number" name="lp_numbering" id="offer-lp-numbering" min="0" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('lp_numbering') ring-1 ring-red-400 @enderror">
            @error('lp_numbering')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="name">Name</label>
            <input value="{{ $offer->name ?? old('name') }}" type="text" name="name" id="offer-name" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('name') ring-1 ring-red-400 @enderror">
            @error('name')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="aff-network">Aff. network</label>
            <input value="{{ $offer->aff_network ?? old('aff_network') }}" type="text" name="aff_network" id="offer-aff-network" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('aff_network') ring-1 ring-red-400 @enderror">
            @error('aff_network')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="price">Price</label>
            <input value="{{ $offer->price ?? old('price') }}" type="number" name="price" id="offer-price" min="0" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('price') ring-1 ring-red-400 @enderror">
            @error('price')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="h-20">
            <label for="offer-type">Type of offer</label>
            <input value="{{ $offer->offer_type ?? old('offer_type') }}" type="text" name="offer_type" id="offer-type" class="shadow appearance-none text-center border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:ring-0 focus:outline-none focus:shadow-outline @error('offer_type') ring-1 ring-red-400 @enderror">
            @error('offer_type')
            <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <!-- End Second Column -->
</div>
