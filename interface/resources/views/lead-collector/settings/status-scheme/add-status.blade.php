<div class="text-center my-6">
  <div class="pt-3">
    <div class="p-4 drop-shadow-sm text-xl text-gray-600 text-center">
      <h3 class="font-bold pl-2 uppercase">
        Add status for <span class="font-black">{{ $partnerName }}</span>
      </h3>
    </div>
  </div>
  <div class="w-1/4 m-auto">
    <form id="add_status_form">
      <div class="mb-6">
        <input type="hidden" id="partner_name" value="{{ $partnerName }}">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Choose
          category</label>
        <select id="ad2lynx_status"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
          @foreach($ad2lynxCategories as $category)
            <option
              value="{{ strtolower($category->ad2lynx_statuses_id) }}">{{ $category->status_category }}</option>
          @endforeach
        </select>
      </div>
      <div class="mb-6">
        <label for="new_status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
          Enter the status you want to add
        </label>
        <input type="text" id="new_status"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               required>
      </div>
      <div class="flex justify-center form-check form-switch">
        <input
          class="form-check-input w-9 -ml-10  float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer"
          type="checkbox" role="switch" id="lock_lead_status">
        <label class="form-check-label inline-block text-gray-800" for="lock_lead_status">Lock
          lead on this status</label>
      </div>
      <div class="flex justify-center form-check form-switch m-2">
        <input
          class="form-check-input w-9 -ml-10  float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer"
          type="checkbox" role="switch" id="add_event_2">
        <label class="form-check-label inline-block text-gray-800" for="add_event_2">
          Add event 2 for status
        </label>
      </div>
      <div class="flex justify-center form-check mb-4">
        <input
          class="form-check-input w-9 -ml-10  float-left h-5 align-top bg-white bg-no-repeat bg-contain bg-gray-300 focus:outline-none cursor-pointer"
          type="checkbox" role="switch" id="accept_payment_for_status">
        <label class="form-check-label inline-block text-gray-800" for="accept_payment_for_status">Accept
          payment for this status</label>
      </div>
      <button type="submit" id="form-commit-btn"
              class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Commit
      </button>
    </form>
  </div>
</div>
