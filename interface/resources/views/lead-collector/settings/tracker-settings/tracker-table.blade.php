<div class="flex justify-center">
    <div class="grid grid-cols-6 gap-6 w-5/6 text-center sm:w-1/2 px-2 py-6 shadow-md bg-gray-50 rounded-md">
        @csrf
        <div class="col-span-6 sm:col-span-1 justify-center">
            <label for="t_id" class="block text-sm font-medium text-gray-700 text-xl">Tracker ID</label>
            <input name="t_id" type="text" id="tracker-id" placeholder="10"
                   value="{{ old('t_id', optional($tracker)['t_id'] ) }}"
                   class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
        </div>
        <div class="col-span-6 sm:col-span-5 sm:w-full">
            <label for="t_url" class="block text-sm font-medium text-gray-700 text-xl">Tracker endpoint
                URL</label>
            <input name="t_url" type="text" id="tracker-id" placeholder="domain.com"
                   value="{{ old('t_id', optional($tracker)['t_url']) }}"
                   class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
        </div>
        <div class="col-span-6 sm:col-span-6 sm:w-full">
            <label for="t_api_key" class="block text-sm font-medium text-gray-700 text-xl">Tracker API
                key</label>
            <input name="t_api_key" type="text" id="tracker-id"
                   value="{{ old('t_id', optional($tracker)['t_api_key']) }}"
                   placeholder="1000001a768088356b266709102e84d50bcf4c6"
                   class="text-center mt-1 focus:ring focus:border-blue-300
                           w-full shadow border text-3xl text-gray-700 sm:text-sm border-gray-300 rounded-md py-1">
        </div>
    </div>
</div>
