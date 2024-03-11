<div id="question_area"
    class=" w-1/4 invisible z-40 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 fixed max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">You trying to remove
        <span class="px-2 py-1 bg-red-600 rounded-md text-white font-light mx-1" id="asked-category"></span> category!</h5>
    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">If you sure, press
        <span class="p-1 pl-2 rounded-md bg-gray-200 font-bold"> Remove! </span>
        , otherwise press <span class="p-1 pl-2 mr-1 rounded-md bg-gray-200 font-bold"> Leave </span> !</p>
    <div class="flex justify-between">
        <span onclick="closeCard('question_area')"
           class="cursor-pointer inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            Leave
        </span>
        <span onclick="sendDeleteRequest()"
           class="cursor-pointer inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
            Remove!
        </span>
    </div>
</div>
