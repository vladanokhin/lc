@extends('app.layout')

@section('title', 'Register new tracker')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <form action="{{ route('trackers.store') }}" method="post">
            <div class="flex justify-center">
                <div class="grid grid-cols-6 gap-6 w-5/6 text-center sm:w-1/2 px-2 py-6 shadow-md bg-gray-50 rounded-md">
                    @csrf
                    <div class="col-span-6 sm:col-span-1 justify-center">
                        <label for="t_id" class="block text-sm font-medium text-gray-700 text-xl">Tracker ID</label>
                        <input name="t_id" type="text" id="tracker-id" placeholder="10"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                    <div class="col-span-6 sm:col-span-5 sm:w-full">
                        <label for="t_url" class="block text-sm font-medium text-gray-700 text-xl">Tracker endpoint
                            URL</label>
                        <input name="t_url" type="text" id="tracker-id" placeholder="domain.com"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                    <div class="col-span-6 sm:col-span-6 sm:w-full">
                        <label for="t_api_key" class="block text-sm font-medium text-gray-700 text-xl">Tracker API
                            key</label>
                        <input name="t_api_key" type="text" id="tracker-id"
                               placeholder="1000001a768088356b266709102e84d50bcf4c6"
                               class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
                    </div>
                </div>
            </div>
            <div class="w-full text-center pt-6">
                <button class=" px-4 py-1 m-2 bg-black text-white text-xl tracking-wider uppercase">
                    <i class="fas fa-skull-crossbones"></i> Connect tracker <i class="fas fa-skull-crossbones"></i>
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
