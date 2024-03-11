<div class="mt-6 container mx-auto">

  <div class="shadow-md px-4 py-2 bg-gray-50">
    <form action="/leads" method="GET">
      <div class="grid grid-cols-3 gap-x-2 gap-x-4 gap-y-7">
        <div>
          <label for="c_id">Click id:</label>
          <input type="text" name="click_id" id="c_id" value="{{ $_GET['click_id'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

          <label for="partner">Partner:</label>
          <input type="text" name="aff_network_name" id="partner"
                 value="{{ $_GET['aff_network_name'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div>
          <label for="status">Status:</label>
          <input type="text" name="conversion_status" id="status"
                 value="{{ $_GET['conversion_status'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <label for="country">Country (GEO):</label>
          <input type="text" name="country_code" id="country"
                 value="{{ $_GET['country_code'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div>
          <label for="offer_id">Offer ID:</label>
          <input type="text" name="offer_id" id="offer_id"
                 value="{{ $_GET['offer_id'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
          <label for="offer_name">Offer name:</label>
          <input type="text" name="offer_name" id="offer_name"
                 value="{{ $_GET['offer_name'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div>
          <label for="date_from">From:</label>
          <input type="date" id="date_from" name="created_at_from"
                 value="{{ $_GET['created_at_from'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

          <label for="date_to">To:</label>
          <input type="date" id="date_to" name="created_at_to"
                 value="{{ $_GET['created_at_to'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-full py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="text-center">
          <label for="t_id">Tracker id:</label><br>
          <input type="text" id="t_id" name="t_id"
                 value="{{ $_GET['t_id'] ?? '' }}"
                 class="shadow appearance-none text-center  border rounded w-1/6 py-2 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

        </div>

      </div>

      <div class="mt-3 pt-1 flex items-center pl-2 border border-gray-200 rounded dark:border-gray-700 w-1/6">
        <label for="strong_search" class="w-2/3 py-1 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Strong search</label>
        <input type="checkbox" name="strong_search" id="strong_search" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mr-10">
      </div>

      <div class="mt-3 pt-1 flex items-center pl-2 border border-gray-200 rounded dark:border-gray-700 w-1/6">
        <label for="with_email" class="w-2/3 py-1 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Only with E-Mails</label>
        <input type="checkbox" name="with_email" id="with_email" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 mr-10">
      </div>


      <div class="form-row mt-2">
        <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1 mx-2 my-1 border-b-4 border-green-500 hover:border-green-400 rounded-md">
          Search
        </button>
      </div>
      <div>
        <button id="downloadLeads"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold px-3 py-1 mx-2 my-1 border-b-4 border-yellow-500 hover:border-yellow-500 rounded">
          Download CSV
        </button>
      </div>
    </form>

    <form action="{{ route('leads') }}" method="get" class="mb-4">
      <div class="form-row">
        <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1 mx-2 my-1 border-b-4 border-green-500 hover:border-green-400 rounded">
          Show All Leads
        </button>
      </div>
    </form>
  </div>
</div>
