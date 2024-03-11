@extends('app.layout')

@section('title', 'Change partner`s data')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <form action="{{ route('partner-providers.update', ['partner_provider' => $partner['id']]) }}" method="post">
            @method('PUT')
            <div class="flex justify-center">
                <div
                    class="grid grid-cols-6 gap-6 w-5/6 text-center sm:w-1/2 px-2 py-6 shadow-md bg-gray-50 rounded-md">
                    @csrf
                    <div class="col-span-6 sm:col-span-3 justify-center">
                        <label for="partner_name" class="block text-sm font-medium text-gray-700 text-xl">
                            Partner name
                        </label>
                        <input name="partner_name" type="text" id="partner_name"
                               value="{{ old('partner_name', optional($partner)['partner_name'] ) }}"
                               placeholder="Partner.name GEO"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                    <div class="col-span-6 sm:col-span-3 sm:w-full">
                        <label for="partner_provider" class="block text-sm font-medium text-gray-700 text-xl">
                            Partner name in LC
                        </label>
                        <input name="partner_provider" type="text" id="partner_provider"
                               value="{{ old('partner_provider', optional($partner)['partner_provider']) }}"
                               placeholder="Partnernamegeo"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                    <div class="col-span-6 sm:col-span-6 sm:w-full">
                        <label for="provider_class" class="block text-sm font-medium text-gray-700 text-xl">
                            Partner provider class name
                        </label>
                        <input name="provider_class" type="text" id="provider_class"
                               value="{{ old('provider_class', optional($partner)['provider_class']) }}"
                               placeholder="Partnername"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>

                    <div class="col-span-6 sm:col-span-6 sm:w-full">
                        <label for="api_key" class="block text-sm font-medium text-gray-700 text-xl">
                            Partner`s API key
                        </label>
                        <input name="api_key" type="text" id="api_key"
                               placeholder="aca4335a777c45efdd43"
                               value="{{ old('api_key', optional($partner)['api_key']) }}"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                    <div class="col-span-6 sm:col-span-6 sm:w-full">
                        <label for="endpoint" class="block text-sm font-medium text-gray-700 text-xl">
                            Partner`s API endpoint
                        </label>
                        <input name="endpoint" type="text" id="endpoint"
                               value="{{ old('endpoint', optional($partner)['endpoint']) }}"
                               placeholder="https://partner-name.com/api/new-lead"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                </div>
            </div>

            <div class="w-full text-center pt-6">
                <button class=" px-4 py-1 m-2 bg-black text-white text-xl tracking-wider uppercase">
                    <i class="fas fa-skull-crossbones"></i> Update partner <i class="fas fa-skull-crossbones"></i>
                </button>
            </div>
        </form>
        @if($errors->any())
            <div>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
