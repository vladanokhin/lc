@extends('app.layout')

@section('title', 'Messages')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <div class="flex-row justify-center m-auto w-1/3">
            @foreach($messageFlow as $id => $message)
                <div class="p-6 bg-gray-200 shadow-md hover:bg-gray-100 hover:shadow-sm mb-3">
                    <div>

                        <div class="text-xl text-center py-2 bg-gray-200 rounded-md">
                            {!! $message['title'] !!}
                        </div>

                        <div class="py-4">
                            <p class="text-xl">{!! $message['content'] !!}</p>
                        </div>

                        <div class="justify-end">
                            <p class="text-right">{{ date('d-m-Y H:i', strtotime($message['created_at'])) }}</p>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
