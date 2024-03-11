@extends('app.layout')

@section('title', 'Statistics')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        @include('lead-collector.statistics-ajax.filter-form')
        <div class="w-full text-center justify-center flex py-12">
            <h2 class="p-2 rounded-md bg-red-400 text-xl w-1/6">All leads for the previous 24 hours</h2>
        </div>
        <div class="text-center justify-center w-full" id="statistics-table">
            <div class="w-full" id="leads-stats">
                @forelse($data['statistics'] as $partner => $statistic)
                    <div class="py-3">
                        <span class="w-full text-center font-bold text-xl">{{ $partner }}</span>
                    </div>
                    <div class="w-full mb-4">
                        @forelse($statistic as $status => $count)
                            @php
                                $linkFrom = app('request')->input('from');
                                $linkTo = app('request')->input('to');
                                $linkTId = app('request')->input('tracker');
                            @endphp
                            <div class="w-1/6 mr-2 mb-2 inline-block rounded-md bg-gray-200 p-2 text-xl text-center">
                                <a href="{{ route('leads') ."?aff_network_name={$partner}&conversion_status={$status}&created_at_from={$linkFrom}&created_at_to={$linkTo}&t_id={$linkTId}" }}"
                                   class="px-1 py-1 mx-1 rounded-md hover:bg-orange-100">
                                    <u class="uppercase">{{ $status }}</u></a> : <span
                                    class="p-2 rounded-md bg-green-300">{{ $count }}</span>
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <hr>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    @include('lead-collector.statistics-ajax.support-page-with-js')
@endsection()
