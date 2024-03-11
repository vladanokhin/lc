@extends('app.layout')

@section('title', 'Ad2Lynx status scheme')

@section('content')
    <div class="w-full  pt-3 bg-gray-100">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        @if ($errors->any())
            <div class="text-center my-3">
                <div class="p-4 drop-shadow-sm text-xl text-gray-600 text-center">
                    <div role="alert" class="w-1/2 m-auto">
                        <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                            Attention!
                        </div>
                        <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><h3 class="font-bold pl-2 uppercase">{{ $error }}</h3></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @include('lead-collector.settings.status-scheme.add-status-category')
        <div class="text-center my-6">
            <div class="pt-3">
                <div class="p-4 drop-shadow-sm text-xl text-gray-600 text-center">
                    <h3 class="font-bold pl-2 uppercase">Existing categories</h3>
                </div>
            </div>
            <table class="w-4/12 m-auto bg-white shadow-md bg-gray-200 border-separate">
                @if(empty($data))
                    <p class="text-center text-md py-2">Any data required</p>
                @else
                    <tr class="shadow-sm text-center justify-between">
                        <td class="text-center px-2 text-xl font-bold p-3 w-3/4 bg-blue-50">Ad2Lynx status</td>
                        <td class="text-center px-2 text-xl font-bold p-3 w-1/4 bg-blue-50">Status weight</td>
                    </tr>
                    <tbody class="bg-white">
                    @foreach($data as $datum)
                        <tr class="shadow-sm">
                            <td class="text-center px-3 font-bold p-4 text-xl">
                                <span onclick="removeCategory(this, {{ $datum }})"
                                      class="mx-4 text-red-600 p-2 rounded-md hover:shadow-xl hover:bg-gray-100 cursor-pointer">
                                        <i class="fas fa-trash"></i>
                                </span>{{ $datum['status_category'] }}
                            </td>
                            <td class="text-center px-3 font-bold">{{ $datum['weight'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                @endif
            </table>
            @include('lead-collector.settings.status-scheme.error-message')
        </div>
    </div>
    @include('lead-collector.settings.status-scheme.ask-user-about-remove')

    <script>
        var categoryRow = null,
            categoryElemId = null;

        function removeCategory(categoryRow, payload) {
            const status_category_text = document.getElementById('asked-category');
            status_category_text.innerHTML = payload['status_category'];
            const question_area = document.getElementById('question_area');
            question_area.classList.remove('invisible')
            this.categoryRow = categoryRow;
            this.categoryElemId = payload['ad2lynx_statuses_id'];
        }

        function closeCard(area) {
            document.getElementById(area).classList.add("invisible");
            document.getElementById('asked-category').innerHTML = '';
        }

        function sendDeleteRequest() {
            var url = '{{ route('delete-ad2lynx-status-category', ':id') }}';
            url = url.replace(':id', this.categoryElemId);
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
                .then(res => res.json())
                // .catch(err)
                .then(function (res) {
                    const el = this.categoryRow.parentElement;
                    el.parentElement.remove();
                    closeCard('question_area')
                })
        }
    </script>
@endsection
