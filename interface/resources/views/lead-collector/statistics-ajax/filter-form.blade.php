{{ app('request')->input('a') }}


<div class="pt-3">
    <div class="p-4 drop-shadow-sm text-xl text-gray-700 text-center">
        <form id="form-statistics">
            <select id="select-partner" class="rounded-md p-2 mx-2 bg-green-200 active:bg-yellow-400">
                @forelse($data['partners'] as $k => $partner)
                    <option value="{{ $partner }}"
                            @if (app('request')->input('partner') == $partner) selected @endif
                    >{{ $partner }}</option>
                @empty
                    <option value="">Partner</option>
                @endforelse
            </select>

            <select id="select-tracker" class="rounded-md p-2 mx-2 bg-green-200 active:bg-yellow-400" name="tracker">
                @forelse($data['trackers'] as $k => $tracker)
                    <option value="{{ $tracker['t_id'] }}"
                            @if (app('request')->input('tracker') == $tracker['t_id']) selected @endif
                    >{{ $tracker['t_url'] }}</option>
                @empty
                    <option value="">Tracker</option>
                @endforelse
            </select>
            FROM <input id="select-from" class="rounded-md p-1 mx-2 bg-green-200 active:bg-yellow-400"
                        type="date" value="{{ app('request')->input('from') ?? date('Y-m-d') }}">
            TO <input id="select-to" class="rounded-md p-1 mx-2 bg-green-200 active:bg-yellow-400"
                      type="date" value="{{ app('request')->input('to') ?? date('Y-m-d') }}">
            <button type="submit" class="bg-yellow-400 rounded-md p-1 px-5 mx-2 text-black hover:bg-yellow-300">
                Filter
            </button>
        </form>
    </div>
</div>
