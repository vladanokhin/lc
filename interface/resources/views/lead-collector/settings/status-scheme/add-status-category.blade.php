<div class="text-center">
    <div class="pt-3">
        <div class="p-2 drop-shadow-sm text-xl text-gray-600 text-center">
            <h3 class="font-bold pl-2 uppercase">Add category</h3>
        </div>
    </div>
    <div class="w-1/4 m-auto shadow-md p-2 rounded-md bg-blue-50">
        <form method="post" action="{{ route('commit-new-ad2lynx-status-category') }}">
            @csrf
            <div class="mb-6">
                <label for="new_category" class="text-xl block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Enter category name
                </label>
                <input type="text" id="new_category" name="status_category"
                       class="text-xl text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>
            <div class="mb-6">
                <label for="weight" class="text-xl block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    Enter status weight (From 0 to 255)
                </label>
                <input type="number" id="weight" max="255" name="weight"
                       class="text-xl text-center bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block m-auto w-1/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                       required>
            </div>

            <button type="submit" id="form-commit-btn"
                    class=" my-2 text-xl text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Commit
            </button>
        </form>
    </div>
</div>
