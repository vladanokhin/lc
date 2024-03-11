<div class="z-50 hidden mx-auto w-full bg-gray-700 inset-0 flex items-center fixed" id="fix-leads-table">
  <!-- Main modal -->
  <div id="authentication-modal" tabindex="-1" aria-hidden="true"
       class="fixed z-50 w-full overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative m-auto max-w-md max-h-full top-1/12  right-0 z-50 w-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <button type="button" onclick="toggleLeadFixModal()"
                class="absolute bg-gray-200 top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                data-modal-hide="authentication-modal">
          <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
               xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
          </svg>
          <span class="sr-only">Close modal</span>
        </button>
        <div class="px-6 py-6 lg:px-8">
          <h3 class="text-center mb-4 text-xl font-medium text-gray-900 dark:text-white" id="update-popup-header">Editor for <span
              class="font-bold" id="fix-partner-name"></span></h3>
          <form class="space-y-6" action="{{ route('update-leads-payload') }}" id="lead_update_form" method="post">
            @csrf
            <input type="hidden" id="c-id-list-for-leads-update" name="click-id-list">
            <div>
              <label for="new-product" class="block mb-2 text-md font-medium text-gray-900 dark:text-white">
                <p><span class="font-bold text-red-500">REQUIRED</span></p>
                <p>Enter new <span class="font-bold">stream-id</span> <span
                    class="text-sm text-blue-800">(offer-id, etc)</span></p>
              </label>
              <input type="text" name="new-product" id="new-product"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                     required>
            </div>

            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                <p><span class="font-bold text-blue-700">OPTIONAL</span></p>
                <p>Update custom <span class="font-bold">Data-1</span></p>
              </label>
              <input type="text" name="data_1" id="data_1"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            </div>

            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                <p><span class="font-bold text-blue-700">OPTIONAL</span></p>
                <p>Update custom <span class="font-bold">Data-2</span></p>
              </label>
              <input type="text" name="data_2" id="data_2"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            </div>

            <div>
              <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                <p><span class="font-bold text-blue-700">OPTIONAL</span></p>
                <p>Update custom <span class="font-bold">Data-3</span></p>
              </label>
              <input type="text" name="data_3" id="data_3"
                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            </div>

            <button id="submit-updated-leads"
                    class="text-lg w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              Update and <span class="font-bold">resend</span>
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
