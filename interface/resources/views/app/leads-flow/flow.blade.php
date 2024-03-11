@extends('app.layout')

@section('title', 'Leads flow')

@section('content')
    <div class="flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">
        @include('app.parts.lead-edit-and-resend-popup')
        <div class="pt-3">
            <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
                <h3 class="font-bold pl-2 uppercase">@yield('title')</h3>
            </div>
        </div>
        @include('lead-collector.form.filter')
        <div class="my-3 text-center">
                <span class="p2">
                    <a href="{{ route('scheduled-leads') }}" class="rounded bg-gray-700 text-white px-2 py-1">Go to scheduled leads</a>
                </span>
        </div>
        <div class="mb-6">
            <div class="p-2 text-lg" id="info">
                <p class="text-center">
                    Leads found:
                    <span class="rounded bg-gray-700 text-white px-2 py-1" id="count-founded"> {{ $count }} </span>
                </p>
            </div>
            <hr>
        </div>
        <div id="main-canvas">
            @include('lead-collector.leads-table')
        </div>
    </div>

    <script type="text/javascript">
        let refresherButton = document.querySelectorAll('#statusRefresher'),
            deleteButton = document.querySelectorAll('#deleteLead'),
            downloadButton = document.querySelector('#downloadLeads'),
            reorderButton = document.querySelectorAll('#leadReorder')
        ;

        refresherButton.forEach(function (item) {
            item.addEventListener('click', function () {
                let clickId = item.getAttribute('data-id');
                let url = "/refresh/" + clickId;
                let req = fetch(url, {
                    method: 'GET'
                });
                req.then((response) => {
                    console.log(response)
                    if (response['ok']) {
                        document.getElementById(item.value).classList.add('bg-green-200');
                    }
                });
                req.catch((response) => {
                    item.classList.remove('btn-dark');
                    item.classList.add('btn-danger');
                })
            });
        });

        deleteButton.forEach(function (item) {
            item.addEventListener('click', () => {
                let uniqueId = item.getAttribute('data-id');
                let url = "/delete/" + uniqueId;
                let req = fetch(url, {
                    method: 'GET'
                });
                req.then(response => {
                    if (response['ok']) {
                        document.getElementById(item.value).classList.add('bg-red-200');
                    }
                });
                req.catch((response) => {
                    console.log(response);
                });
            });
        });

        reorderButton.forEach(function (item) {
            item.addEventListener('click', function () {
                let uniqueId = item.getAttribute('data-id');
                let url = "/reorder/" + uniqueId;
                let req = fetch(url, {
                    method: 'GET'
                });
                req.then((response) => {
                    if (response['ok']) {
                        document.getElementById(item.value).classList.add('bg-indigo-200');
                    }
                });
                req.catch((response) => {
                    console.log(response)
                })
            });
        });

        let actionButton = document.querySelectorAll('#lc-menu'),
            dropdownButtons = document.querySelectorAll('#dropdown');

        actionButton.forEach(function (elem, index) {
            elem.addEventListener('click', () => {
                dropdownButtons.forEach((item, i) => {
                    if (i !== index) {
                        item.classList.add('hidden')
                    }
                });
                dropdownButtons[index].classList.toggle('hidden');
                document.getElementById('resend-button').disabled = false;
            })
        });

        let perPage = document.querySelectorAll('#per-page');

        // pagination
        perPage.forEach(function (item) {
            item.addEventListener('change', function () {
                let value = item.value;
                let url = new URLSearchParams(window.location.search);
                url.set('pp', value);
                document.location.search = url;
            });
        });

        // Edit and reorder area
        let buttons = document.querySelectorAll('#editAndReorderLead');
        let editorArea = document.getElementById('lead-editor-popup');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                editorArea.classList.remove('hidden');
                button.disabled = true;
                button.disabled = false;
                document.getElementById('enter-click-id').value = this.value;
                document.getElementById('enter-username').value = this.getAttribute('uname');
                document.getElementById('enter-user-phone').value = this.getAttribute('uphone');
            });
        });

        // Close editor area
        document.getElementById('edit-resend-close').addEventListener('click', function () {
            editorArea.classList.toggle('hidden');
        });

        // reordering lead
        let resendInit = document.getElementById('resend-button');
        resendInit.addEventListener('click', function (item) {
            let bt = this;
            bt.disabled = true;
            let clickId = document.getElementById('enter-click-id').value,
                newUsername = document.getElementById('enter-username').value,
                newUserPhone = document.getElementById('enter-user-phone').value,
                reorderUrl = "/reorder-edited?clickid=" + clickId + "&new_name=" + newUsername + "&new_phone=" + newUserPhone
            ;
            let req = fetch(reorderUrl, {
                method: 'GET'
            }).then(res => res.text())
                .then(body => {
                    try {
                        return JSON.parse(body);
                    } catch {
                        throw Error(body);
                    }
                })
                .then(function (data) {
                    let status = document.getElementById('reorder-status');
                    status.innerHTML = (data['status'] === 'same_data') ? 'Nothing to update &#10060;' :
                        (data['status'] === 'data_updated') ? 'Lead successfully reordered <span class="text-2xl">&#128025;</span>' :
                            '<span class="text-xl text-red-800">For unknown reason lead can`t be updated...</span>';
                    bt.disabled = false;
                })
                .catch((e) => {
                    console.log('Error: ', e)
                });
        });

        // leads downloading
        downloadButton.addEventListener('click', function (e) {
            e.preventDefault();
            let strGET = window.location.search.replace('?', '');
            if (strGET === '' || strGET === null) {
                if (false === confirm("Загрузка лидов без фильтра.\n\rПродолжить?")) return null;
            }
            const url = '/download?' + strGET;
            let response = fetch(url);
            response
                .then(res => res.blob())
                .then((data) => {
                    const filename = '<?= 'leads-' . date('Y-m-d-') . time() . '.csv' ?>';
                    const file = document.createElement("a");
                    file.href = window.URL.createObjectURL(data);
                    file.download = filename;
                    file.click();
                });
        });
    </script>
@endsection()
