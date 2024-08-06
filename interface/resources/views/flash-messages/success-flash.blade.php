<div class="flex justify-between hover:border-b-4 bg-green-400 bg-origin-border border-2 border-solid rounded-md text-white mt-2 mb-2 h-14" id="flash-message">
    <div class="flex">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="self-center mb-0.5 w-8 h-8 ml-3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <p class="self-center ml-1">{{ $message }}</p>
    </div>
    <div class="cursor-pointer mr-1 mt-1" id="js-btn-close-flash">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
    </div>
</div>
