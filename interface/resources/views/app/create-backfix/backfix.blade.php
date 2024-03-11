@extends('app.layout')

@section('title', 'Create your backfix')

@section('content')
    <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        <div class="text-center">
            <div class="text-xl uppercase text-gray-600 my-4">For prelanding</div>
            <span>
                <p>First endpoint
                    <select id="p-f-e" class="py-2 my-2 text-center px-1 bg-indigo-100 rounded-md">
                        <option value="to_showcase">To showcase</option>
                        <option value="to_offer">To offer page</option>
                        <option value="to_offer2">To second offer page</option>
                        <option value="to_path2">To path two</option>
                        <option value="none">None</option>
                        <option value="disallowHistory">Kill history &#129313;</option>
{{--                    </select> Use anchor <input type="checkbox" class="w-8" id="prelandAnchor">--}}
                    </select>
                    <input type="hidden" class="w-8" id="prelandAnchor">
                </p>
                <p>DELAY (in seconds) <input type="text" class="py-1 my-2 w-12 text-center px-2  rounded-md bg-gray-200"
                                             id="prelandingDelay" placeholder="0"></p>
                <p>Last endpoint
                    <select id="p-l-e" class="py-2 my-2 text-center px-1 bg-indigo-100 rounded-md">
                        <option value="to_showcase">To showcase</option>
                        <option value="to_offer">To offer page</option>
                        <option value="to_offer2">To second offer page</option>
                        <option value="to_path2">To path two</option>
                        <option value="none">None</option>
                        <option value="disallowHistory">Kill history &#129313;</option>
                    </select>
                </p>
            </span>


            <div class="text-xl uppercase text-gray-600 my-4">For landing</div>
            <span>
                <p>First endpoint
                    <select id="l-f-e" class="py-2 my-2 text-center px-1 bg-indigo-100 rounded-md">
                        <option value="to_showcase">To showcase</option>
                        <option value="to_offer">To offer page</option>
                        <option value="to_offer2">To second offer page</option>
                        <option value="to_path2">To path two</option>
                        <option value="none">None</option>
                        <option value="disallowHistory">Kill history &#129313;</option>
                    </select>
{{--                    Use anchor <input type="checkbox" class="w-8" id="landAnchor">--}}
                    <input type="hidden" class="w-8" id="landAnchor">
                </p>

                <p>DELAY (in seconds) <input type="text" class="py-1 my-2 w-12 text-center px-2 rounded-md bg-gray-200"
                                             id="landingDelay" placeholder="0"></p>
                <p>Last endpoint
                    <select id="l-l-e" class="py-2 my-2 text-center px-1 bg-indigo-100 rounded-md">
                        <option value="to_showcase">To showcase</option>
                        <option value="to_offer">To offer page</option>
                        <option value="to_offer2">To second offer page</option>
                        <option value="to_path2">To path two</option>
                        <option value="none">None</option>
                        <option value="disallowHistory">Kill history &#129313;</option>
                    </select>
                </p>
            </span>
            <span>
                <button class="px-3 py-1 my-5 border-black border-2 rounded-xl text-xl tracking-wider bg-white
                hover:bg-black hover:text-white active:bg-gray-800 uppercase" id="create-backfix">
                    &#128126; MAKE MORE LEADS &#128126;</button>
            </span>

            <div class="content-center hidden" id="backfix-result-block">
                <p class="uppercase py-4 text-xl tracking-wider">Result</p>
                <p class="rounded-md bg-white w-auto text-l py-2 px-5">
                    <span id="backfix-result" class="mr-12"></span>
{{--                    <button class="rounded-xl bg-indigo-200 px-2 py-1" id="copy-bf">--}}
{{--                        <i class="far fa-copy"></i>--}}
{{--                    </button>--}}
                </p>
            </div>
        </div>

        <div class="text-center hidden">
            <div class="text-xl uppercase text-gray-600 my-4">decode backfix hash</div>

            <div>
                <form id="form-decoder">
                <input type="text" id="to-decode" class="py-2 my-2 text-center px-1 bg-indigo-100 rounded-md text-xl w-1/2">
                    <p><input type="submit" value="Decode please"
                           class="px-3 py-1 my-5 border-black border-2 rounded-xl text-xl tracking-wider bg-white
                hover:bg-black hover:text-white active:bg-gray-800 uppercase"></p>
                </form>
            </div>
            <p id="decoded"></p>
        </div>
    </div>

    <script>
        let resultBlock = document.getElementById('backfix-result-block'),
            backfixResultArea = document.getElementById('backfix-result'),
            createButton = document.getElementById('create-backfix'),

            prelandDelay = null,
            prelandFirstEndpoint = null,
            prelandLastEndpoint = null,
            prelandAnchor = null,

            landDelay = null,
            landFirstEndpoint = null,
            landLastEndpoint = null,
            landAnchor = null,
            backfix = null,
            bfLander = null,
            bfOffer = null
        ;

        createButton.addEventListener('click', function (e) {
            e.preventDefault();
            resultBlock.classList.remove('hidden');

            prelandDelay = document.getElementById('prelandingDelay').value;
            prelandFirstEndpoint = document.getElementById('p-f-e').value;
            prelandLastEndpoint = document.getElementById('p-l-e').value;
            prelandAnchor = (document.getElementById('prelandAnchor').checked === true) ? "1" : "0";

            landDelay = document.getElementById('landingDelay').value;
            landFirstEndpoint = document.getElementById('l-f-e').value;
            landLastEndpoint = document.getElementById('l-l-e').value;
            landAnchor = (document.getElementById('landAnchor').checked === true) ? "1" : "0";

            if (prelandDelay === "") {
                prelandDelay = 0;
            }
            if (landDelay === "") {
                landDelay = 0;
            }

            bfLander = prelandFirstEndpoint+'-'+prelandDelay+'-'+prelandLastEndpoint+'-'+prelandAnchor;
            bfOffer = landFirstEndpoint+'-'+landDelay+'-'+landLastEndpoint+'-'+landAnchor;
            backfix = '&bf_lander='+bfLander+'&bf_offer='+bfOffer;
            backfixResultArea.innerText = backfix;
            document.getElementById('backfix-result').setAttribute('value', backfix);
        });
    </script>
@endsection
