@extends('app.layout')

@section('title', 'Trackers Settings')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <div class="text-center my-6">
            <table class="w-max-content m-auto bg-white shadow-md bg-gray-200">
                @if(empty($data))
                    <p class="text-center text-md py-2">Any data required</p>
                @else
                    @foreach($data as $item => $element)
                        <tr class="shadow-sm text-center justify-between py-1">
                            <td class="text-center px-3">Tracker ID</td>
                            <td class="text-center px-3">Tracker endpoint URL</td>
                            <td class="text-center px-3">Tracker API key</td>
                            <td class="text-center px-3">Last update</td>
                            <td class="text-center px-3">Action</td>
                        </tr>
                        @break
                    @endforeach

                    <tbody class="bg-white">
                    @foreach($data as $datum)
                        <tr class="shadow-sm">
                            <td class="text-center px-3 py-4">{{ $datum['t_id'] }}</td>
                            <td class="text-center px-3 py-4">{{ $datum['t_url'] }}</td>
                            <td class="text-center px-3 py-4">{{ $datum['t_api_key'] }}</td>
                            <td class="text-center px-3 py-4">{{ date('d-m H:i', strtotime($datum['updated_at'])) }}</td>
                            <td>
                                <a href="{{ route('trackers.edit', ['tracker' => $datum['t_id']]) }}"
                                   class="rounded-md px-4 py-2 m-2 bg-yellow-200">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
            <div class="mt-6">
                <p class="">
                    <a href="{{ route('trackers.create') }}" class="p-2 mx-2 rounded-md bg-green-300">Add tracker</a>
                </p>
            </div>
        </div>
    </div>
@endsection
