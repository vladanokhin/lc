@extends('app.layout')

@section('title', 'Statistics')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        @include('lead-collector.statistics.filter-form')
        <div>
            @forelse($data['statistics'] as $partner => $statistic)
                <div class="pt-3 w-full">
                    <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                        <h3 class="font-bold pl-2 uppercase">{{ $partner }}</h3>
                    </div>
                </div>
                <div class="flex flex-wrap w-full">
                    @forelse($statistic as $row => $status)
                        @php
                            $color = strtolower($data['colors'][array_rand($data['colors'])]);
                        @endphp
                        <div class="w-1/3 p-6">
                            <!--Metric Card-->
                            <div
                                class="bg-gradient-to-b from-{{ $color }}-300
                                to-{{ $color }}-100
                                border-b-4 border-{{ $color }}-700 rounded-lg shadow-xl p-5">
                                <div class="flex flex-row items-center">
                                    <div class="flex-shrink pr-4">
                                        <div class="rounded-full p-5 bg-{{ $color }}-600">
                                            <h5 class="font-bold uppercase text-white text-xl">{{ $row }}</h5>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-bold text-3xl">{{ $status }}</h3>
                                    </div>
                                </div>
                            </div>
                            <!--/Metric Card-->
                        </div>
                    @empty
                        <div class="w-full text-center m-3">Empty data</div>
                    @endforelse
                    @empty
                        <div class="w-full text-center m-3">Empty data</div>
                    @endforelse
                </div>
        </div>
    </div>
    </div>
@endsection()
