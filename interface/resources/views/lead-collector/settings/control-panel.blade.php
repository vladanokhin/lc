@extends('app.layout')

@section('title', 'Lead Collector Settings')

@section('content')
    <div class="w-full flex items-center content-center justify-center bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="w-8/12 self-center">
            <div class="pt-3">
                <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                    <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
                </div>
            </div>
            <div class="text-center flex justify-center w-full mt-6">
                <div class="p-6 w-1/4 my-4">
                    <a href="{{ route('partner-providers.index') }}"
                       class="px-6 py-4 text-xl bg-white text-gray-800 border border-gray-400 rounded shadow-md
                   hover:bg-blue-100 active:bg-gray-100 hover:shadow-none hover:bg-gray-200 hover:shadow-md">
                        Partners API
                    </a>
                </div>

                <div class="p-6 w-1/4 my-4">
                    <a href="{{ route('statusScheme') }}"
                       class="px-6 py-4 text-xl bg-white text-gray-800 border border-gray-400 rounded shadow-md
                   hover:bg-blue-100 active:bg-gray-100 hover:shadow-none hover:bg-gray-200 hover:shadow-md">
                        Status Scheme
                    </a>
                </div>

                <div class="p-6 w-1/4 my-4">
                    <a href="{{ route('backfixConfigurator') }}"
                       class="px-6 py-4 text-xl bg-white text-gray-800 border border-gray-400 rounded shadow-md
                   hover:bg-blue-100 active:bg-gray-100 hover:shadow-none hover:bg-gray-200 hover:shadow-md">
                        Backfix
                    </a>
                </div>

                <div class="p-6 w-1/4 my-4">
                    <a href="{{ route('trackers.index') }}"
                       class="px-6 py-4 text-xl bg-white text-gray-800 border border-gray-400 rounded shadow-md
                   hover:bg-blue-100 active:bg-gray-100 hover:shadow-none hover:bg-gray-200 hover:shadow-md">
                        Trackers API
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection()
