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
        <div class="text-center justify-center w-full my-4">
            <span>
                <span class="text-2xl px-1">Table</span>
                <label class="switch">
                    <input type="checkbox" id="switcher">
                    <span class="slider round"></span>
                </label>
                <span class="text-2xl px-1">Canvas</span>
            </span>
        </div>
        <div class="w-full text-center justify-center flex">
            <h2 class="p-2 rounded-md bg-red-400 text-xl w-1/6">All leads for the past 24 hours</h2>
        </div>
        <div class="text-center justify-center w-full" id="statistics-table">
            <div class="w-full">
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
        <div id="statistics-canvas" class="hidden">
            @forelse($data['statistics'] as $partner => $statistic)
                <div class="pt-3 w-full">
                    <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                        <h3 class="font-bold pl-2 uppercase">{{ $partner }}</h3>
                    </div>
                </div>
                <div class="flex flex-wrap w-full">
                    @forelse($statistic as $status => $count)
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
                                            <h5 class="font-bold uppercase text-white text-xl"
                                                id="status">{{ $status }}</h5>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h3 class="font-bold text-3xl" id="count">{{ $count }}</h3>
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

    <script>
        let canvasOption = document.getElementById('statistics-canvas'),
            tableOption = document.getElementById('statistics-table'),
            switcher = document.getElementById('switcher')
        ;

        switcher.addEventListener('change' , () => {
            canvasOption.classList.toggle('hidden');
            tableOption.classList.toggle('hidden');
        });
    </script>
@endsection()
