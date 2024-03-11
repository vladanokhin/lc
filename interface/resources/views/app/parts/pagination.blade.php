<div class="w-1/2 justify-center mt-3 py-6 px-0 m-auto">
  <p class="inline-flex text-md">
        <span>
            {{ $leads->appends(request()->query())->links('vendor.pagination.tailwind') }}
        </span>
    <span class="ml-8"> Per page:
      <select name="per-page" id="per-page" class="px-1 rounded bg-purple-50 border-2">
        <option value="1"></option>
        <option value="15">15</option>
        <option value="30">30 default</option>
        <option value="45">45</option>
        <option value="60">60</option>
      </select>
    </span>

    <span class="dropdown mx-6">
      <button id="select-all"
              class="px-3 rounded bg-red-400 shadow-md hover:bg-red-300">Select all</button>
    </span>

    <span class="dropdown mx-6">
      <button id="lc-menu" class="px-3 rounded bg-red-400 shadow-md hover:bg-red-300">Mass actions
        <i class="fas fa-reply-all"></i>
      </button>
      <span id="dropdown" class="overflow-auto z-10 hidden absolute bg-gray-100 border-2 rounded-sm">
        <button id="massStatusRefresher"
                class="p-4 block relative font-xl hover:bg-blue-300 w-full shadow-sm">Refresh</button>
        <button id="massLeadReorder"
                class="p-4 block relative font-xl hover:bg-blue-300 w-full shadow-sm">Reorder</button>
        <button id="massLeadEditor"
                class="p-4 block relative font-xl hover:bg-blue-300 w-full shadow-sm">Edit selected</button>
      </span>
    </span>
  </p>
</div>
