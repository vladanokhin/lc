@extends('app.layout')

@section('title', 'Lead history')

@section('content')
    <div class="pt-5 mt-3 text-center w-full mb-10 justify-center">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Click ID <span class="px-4 text-xl bg-indigo-200 px-3 py-1 rounded-md">{{ $clickid }}</span>
        </h3>
        <div class="m-auto mt-2 text-center grid grid-cols-2 pl-6 mb-12">
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Username</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Status</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">
                <span class="bg-green-50 py-1 px-2 rounded-md">{{ $lead['name'] }}</span>
            </div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">
                <span class="bg-green-50 py-1 px-2 rounded-md">{{ $lead['conversion_status'] }}</span>
            </div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Phone</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Last update</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">
                <span class="bg-green-50 py-1 px-2 rounded-md">{{ $lead['phone'] }}</span>
            </div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">
                <span
                    class="bg-green-50 py-1 px-2 rounded-md">{{ date('d-m H:i', strtotime($lead['updated_at'])) }}</span>
            </div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Second phone</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Offer name</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">{{ $lead['second_phone'] }}</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">{{ $lead['offer_name'] }}</div>

        </div>
        <div class="m-auto mt-2 text-center grid grid-cols-2 pl-6">

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Offer ID</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Partner</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">{{ $lead['offer_id'] }}</div>
            <div
                class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">{{ $lead['aff_network_name'] }}</div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">User email</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Country code</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">{{ $lead['user_email'] }}</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">{{ $lead['country_code'] }}</div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Tracker ID</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Product</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">{{ $lead['t_id'] }}</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">{{ $lead['product'] }}</div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Data 1</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Data 2</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">
                {{ $lead['data_1'] ?: 'Empty' }}
            </div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">
                {{ $lead['data_2'] ?: 'Empty'  }}
            </div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">Data 3</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Is sent</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">
                {{ $lead['data_3'] ?: 'Empty'  }}
            </div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">
                {{ ($lead['is_sent'] == 0) ? 'Not yet' : 'Already' }}
            </div>

            <div class="text-center px-3 w-auto bg-gray-300 rounded-tl-md">ID</div>
            <div class="text-center px-3 w-auto bg-gray-300 rounded-tr-md">Creation date</div>
            <div class="mb-4 text-center px-3 py-2 w-auto rounded-bl-md bg-indigo-100">{{ $lead['id'] }}</div>
            <div
                class="mb-4 text-center px-3 py-2 w-auto rounded-br-md bg-indigo-100">{{ date('d-m H:i', strtotime($lead['created_at'])) }}</div>
        </div>
    </div>

    <div class="pt-5 mt-3 text-center text-center justify-between w-full mb-10">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Click ID <span class="px-4 text-xl bg-indigo-200 px-3 py-1 rounded-md">{{ $clickid }}</span> history
        </h3>
        <div class="w-3/4 m-auto mt-2 text-center">
            <div class="shadow-sm py-1 rounded-t-md bg-gray-300 justify-between flex">
                <div class="text-center px-3 w-1/4 tracking-wide text-md font-bold">Name</div>
                <div class="text-center px-3 w-1/4 tracking-wide text-md font-bold">Phone</div>
                <div class="text-center px-3 w-1/4 tracking-wide text-md font-bold">Status</div>
                <div class="text-center px-3 w-1/4 tracking-wide text-md font-bold">Update time</div>
                <div class="text-center px-3 w-1/4 tracking-wide text-md font-bold">Timestamp</div>
            </div>
            <div class="h-1/2">
                @foreach($history as $id => $story)
                    @if(empty($story))
                        <div class="shadow-sm py-2 text-gray-800 bg-gray-200 justify-between text-center">
                            <span class="text-xl text-black">Any changes found...</span>
                        </div>
                    @endif
                    @foreach($story as $key => $lead)
                        <div class="shadow-sm py-2 text-gray-800 bg-gray-200 justify-between flex">
                            <div class="text-center px-3 w-1/4">{{ $lead['name'] }}</div>
                            <div class="text-center px-3 w-1/4">{{ $lead['phone'] }}</div>
                            <div class="text-center px-3 w-1/4">{{ $lead['conversion_status'] }}</div>
                            <div class="text-center px-3 w-1/4">{{ $lead['lead_action_timestamp'] }}</div>
                            <div
                                class="text-center px-3 w-1/4">{{ date('d-m H:i', strtotime($lead['updated_at'])) }}</div>
                        </div>
                    @endforeach
                @endforeach
            </div>
            {{--        Partner response    --}}
            <div class="w-3/4 m-auto mt-12 text-center w-full">
                <div class="text-center shadow-sm py-1 rounded-t-md bg-gray-300 font-bold">
                    Partner response
                </div>
                <div class="shadow-sm py-2 text-gray-800 bg-gray-200 text-center">
                    @if(!empty($response) && isset($response[0]))
                        {{ $response[0]['response_text'] }}
                    @else
                        Waiting for response
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
