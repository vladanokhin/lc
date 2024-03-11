@extends('app.layout')

@section('title', 'Partner providers Settings')

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
                            <td class="text-center px-3">Partner</td>
                            <td class="text-center px-3">Actual API Key</td>
                            <td class="text-center px-3">Endpoint</td>
                            <td class="text-center px-3">Last update</td>
                            <td class="text-center px-3">Action</td>
                        </tr>
                        @break
                    @endforeach

                    <tbody class="bg-white">
                    @foreach($data as $datum)
                        <tr class="shadow-sm">
                            <td class="text-center px-3 py-4">{{ $datum['partner_name'] }}</td>
                            <td class="text-center px-3 py-4">{{ \Illuminate\Support\Str::limit($datum['api_key'], 18, $end='...') }}</td>
                            <td class="text-center px-3 py-4">{{ $datum['endpoint'] }}</td>
                            <td class="text-center px-3 py-4">{{ date('d-m H:i', strtotime($datum['updated_at'])) }}</td>
                            <td>
                                <a href="{{ route('partner-providers.edit', ['partner_provider' => $datum['id']]) }}"
                                   class="rounded-md px-4 py-2 m-2 bg-yellow-200 hover:bg-green-200">Edit</a>
                            </td>
                            <td>
                                <a href="{{ route('advertiser-statuses-board', ['name' => $datum['partner_name']]) }}"
                                   class="rounded-md px-4 py-2 m-2 bg-yellow-200 hover:bg-green-200">Status scheme</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
            <div class="mt-6">
                <p>
                    <a href="{{ route('partner-providers.create') }}" class="p-2 mx-2 rounded-md bg-green-300">Add
                        partner</a>
                </p>
            </div>
        </div>
    </div>
@endsection
