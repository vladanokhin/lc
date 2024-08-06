@extends('app.layout')

@section('title', 'Create Offer')

@section('content')
    <div class="flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5" id="offer-form-wrapper">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <form action="{{ route('offers.store') }}" method="POST">
            @csrf

            <div class="mt-6 container mx-auto shadow-md px-4 py-2 bg-gray-50">
                @if (session()->has('message'))
                    @include('flash-messages.success-flash', ['message' => session('message')])
                @endif
                @include('lead-collector/settings/offers/offers-input')
                <div class="flex justify-center mt-4 md:justify-start md:ml-3.5">
                    <button type="submit"
                            id="js-btn-submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1 ml-0 mx-2 my-1 border-b-4 border-green-500 hover:border-green-400 rounded-md"
                    >
                        Create
                    </button>
                </div>
            </div>
        </form>
        @include('lead-collector/settings/offers/offers-table')
    </div>
@endsection()
