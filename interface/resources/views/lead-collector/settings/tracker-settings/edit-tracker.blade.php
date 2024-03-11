@extends('app.layout')

@section('title', 'Change tracker`s data')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <form action="{{ route('trackers.update', ['tracker' => $tracker['id']]) }}" method="post">
            @method('PUT')
            @include('lead-collector.settings.tracker-settings.tracker-table')
            <div class="w-full text-center pt-6">
                <button class=" px-4 py-1 m-2 bg-black text-white text-xl tracking-wider uppercase">
                    <i class="fas fa-skull-crossbones"></i> Update tracker <i class="fas fa-skull-crossbones"></i>
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
