@extends('app.layout')

@section('title', 'Scheduled leads')

@section('content')

<div class="flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>

        <div class="mb-6 text-center">
            <a href="{{ route('leads') }}" class="rounded bg-gray-700 text-white px-2 py-1">To all leads</a>
        </div>

        <table class="w-max-content m-auto bg-white shadow-md bg-gray-200">
    @if(empty($leads))
        <p class="text-center text-md py-2">Queue is empty</p>
    @else
        @foreach($leads as $item => $element)
            <tr class="shadow-sm text-center justify-between py-1">
                @if(
        $element == 'country_code'   ||
        $element == 'is_deleted'     ||
        $element == 'updated_at'     ||
        $element == 'product'        ||
        $element == 'data_1'         ||
        $element == 'data_2'         ||
        $element == 'data_3'         ||
        $element == 't_id') @continue
                @endif
                <td class="text-center px-3">ID</td>
                <td class="text-center px-3">Partner</td>
                <td class="text-center px-3">Click ID</td>
                <td class="text-center px-3">User name</td>
                <td class="text-center px-3">Phone</td>

                <td class="text-center px-3">Second phone</td>
                <td class="text-center px-3">Status</td>
                <td class="text-center px-3">Tracker</td>

                <td class="text-center px-3">Offer ID</td>
                <td class="text-center px-3">Offer Name</td>
                <td class="text-center px-3">Created</td>
            </tr>
            @break
        @endforeach
        <tbody class="bg-white">
        @foreach($leads as $lead)
            <tr class="shadow-sm">
                @if(
    $lead == 'country_code' ||
    $lead == 'is_deleted'   ||
    $lead == 'updated_at'   ||
    $lead == 'product'      ||
    $lead == 'data_1'       ||
    $lead == 'data_2'       ||
    $lead == 'data_3'       ||
    $lead == 't_id'
                    ) @continue
                @endif
                <td class="text-center py-3 px-4">{{ $lead->id }}</td>
                <td class="text-center px-3">
                    {{ \Illuminate\Support\Str::limit($lead->aff_network_name, 22, $end='...') }}
                </td>
                <td class="text-center px-3">
                    <span class="bg-red-50 px-3 rounded-md">
                        {{ $lead->click_id }}
                    </span>
                </td>
                <td class="text-center px-3">
                    {{ \Illuminate\Support\Str::limit($lead->name, 18, $end='...') }}</td>
                <td class="text-center px-3">
                    {{ \Illuminate\Support\Str::limit($lead->phone, 22, $end='...') }}</td>
                    <td class="text-center px-3">
                        {{ \Illuminate\Support\Str::limit($lead->second_phone, 22, $end='...') }}</td>
                    <td class="text-center px-3">In queue</td>
                    <td class="text-center px-3"> {{ $lead->t_id }}</td>
                <td class="text-center px-3">{{ $lead->offer_id }}</td>
                <td class="text-center px-3">
                    {{ \Illuminate\Support\Str::limit($lead->offer_name, 46, $end='...') }}
                </td>
                <td class="text-center px-3">{{ date('d-m H:i', strtotime($lead->created_at)) }}</td>
            </tr>
        @endforeach
        </tbody>
</table>
@endif
</div>

</div>

@endsection
