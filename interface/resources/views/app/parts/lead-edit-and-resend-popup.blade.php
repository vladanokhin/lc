<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="lead-editor-popup" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!--
          Background overlay, show/hide based on modal state.
        -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gray-200 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-center mb-3 py-5 text-xl leading-6 font-medium text-gray-900" id="modal-title">
                            Edit and reorder lead below:
                        </h3>
                        <h5 class="text-center mb-3 text-green-600 font-medium" id="reorder-status">
                        </h5>
                        <div class="mt-2 grid grid-cols-2 gap-y-2">
                            <span class="text-center uppercase">click id:</span>
                            <span><input class="rounded-md w-5/6 text-gray-800 font-medium px-1 py-1 text-center" id="enter-click-id" disabled></span>

                            <span class="text-center uppercase">user name:</span>
                            <span><input class="rounded-md w-5/6 text-gray-800 font-medium px-1 py-1 text-center" id="enter-username"></span>

                            <span class="text-center uppercase">phone:</span>
                            <span><input class="rounded-md w-5/6 text-gray-800 font-medium px-1 py-1 text-center" id="enter-user-phone"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-200 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="resend-button" type="button" class="disabled:opacity-50 bg-green-500 hover:bg-green-600 text-white font-bold px-3 py-1 mx-2 my-1 border-b-4 border-green-500 hover:border-green-400 rounded-md">
                    Resend
                </button>
                <button type="button" id="edit-resend-close" class="bg-indigo-100 text-black hover:bg-indigo-200 text-white px-3 py-1 mx-2 my-1 border-b-4 border-indigo-100 hover:border-indigo-400 rounded-md">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
