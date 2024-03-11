<div class="text-center my-6">
    <table class="w-8/12 m-auto bg-white shadow-md bg-gray-200 border-separate">
        @if(empty($data))
            <p class="text-center text-md py-2">Any data required</p>
        @else
            <tr class="shadow-sm text-center justify-between py-1">
                <td class="text-center px-3 text-xl font-bold p-3 w-1/4">Ad2Lynx status</td>
                <td class="text-center px-3 text-xl font-bold p-3 w-3/4">Related statuses</td>
            </tr>
            <tbody class="bg-white">
            @foreach($data as $category => $status_data)
                <tr class="shadow-sm">
                    <td class="text-center px-3 font-bold">{{ $category }}</td>
                    <td class="text-center px-3 grid grid-cols-3">
                        @foreach($status_data['related_statuses'] as $status_name => $status_prop)
                            <span class="whitespace-nowrap bg-green-300 p-3 rounded-md mx-4 text-xl my-3">{{ $status_name }}
                                <br><i class="text-red-600 cursor-pointer fas fa-minus-circle px-3" onclick="removeRelatedStatus({{$status_prop['id']}}, this)"></i>
                                @if($status_prop['status_locked'] == 1)
                                    <i class="cursor-not-allowed text-blue-600 ml-2 fas fa-lock"></i>
                                @endif
                                @if($status_prop['accept_payment'] == 1)
                                    <i class="cursor-not-allowed text-blue-600 ml-2 fas fa-dollar-sign"></i>
                                @endif
                            </span>
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endif
    </table>
    <div class="w-full m-auto my-8">
        <a href="{{ route('add-new-ad2lynx-status-category') }}"
           class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 text-xl">Add
            Ad2Lynx status</a>
    </div>
    @include('lead-collector.settings.status-scheme.error-message')
    @include('lead-collector.settings.status-scheme.add-status')
</div>
