<script>
    let selectPartner = document.getElementById('select-partner').value,
        selectTracker = document.getElementById('select-tracker').value,
        selectDateFrom = document.getElementById('select-from').value,
        selectDateTo = document.getElementById('select-to').value,
        formStatistics = document.getElementById('form-statistics'),
        newPath = null
    ;
    formStatistics.addEventListener('change', (event) => {
        event.preventDefault();
        selectPartner = document.getElementById('select-partner').value;
        selectTracker = document.getElementById('select-tracker').value;
        selectDateFrom = document.getElementById('select-from').value;
        selectDateTo = document.getElementById('select-to').value;
    });
    formStatistics.addEventListener('submit', (event) => {
        event.preventDefault();

        const TOKEN = document.querySelector('meta[name="csrf-token"]').content
        var payload = {
            partner: selectPartner,
            tracker: selectTracker,
            from: selectDateFrom,
            to: selectDateTo
        };
        var req = new XMLHttpRequest();
        req.open('POST', '/stat-ajax', true);
        req.setRequestHeader('Content-Type', 'application/json');
        req.setRequestHeader('X-CSRF-TOKEN', TOKEN);
        req.onload = function () {
            // Successful response
            var response = req.responseText;
            response = JSON.parse(response);
            var partnerName = Object.keys(response)[0];
            var statisticsPayload = response[Object.keys(response)[0]];

            // Canvas with all statistics data
            var statisticsCanvas = document.getElementById('leads-stats');
            statisticsCanvas.replaceChildren();

            // Span in the canvas with partners name
            var spanWithPartnerName = document.createElement('span');
            spanWithPartnerName.classList
                .add('w-full', 'text-xl', 'font-bold', 'text-center');
            spanWithPartnerName.innerText = partnerName;

            //
            var blockForPartnersName = document.createElement('div');
            blockForPartnersName.classList.add('py-3')
            blockForPartnersName.append(spanWithPartnerName);

            //
            var leadsQuantityWithConcreteStatus = document.createElement('span');
            leadsQuantityWithConcreteStatus.classList.add('p-2', 'rounded-md', 'bg-green-300');
            leadsQuantityWithConcreteStatus.innerText = 'asdasd';

            // All payload appending to main block : statisticsCanvas
            statisticsCanvas.append(blockForPartnersName);

            // Area where we display conversion status name and its quantity
            var rowWithPartnersStatistics = document.createElement('div');
            rowWithPartnersStatistics.classList.add('mb-4', 'w-full');
            rowWithPartnersStatistics.append();

            // Filling page with stats information
            for (const status in statisticsPayload) {
                var statisticsCell = document.createElement('div');
                statisticsCell.classList
                    .add('w-1/6', 'mr-2', 'mb-2', 'inline-block', 'rounded-md', 'bg-gray-200', 'p-2', 'text-xl', 'text-center')

                var statusName = document.createElement('a')
                statusName.href =
                    `{{ route('leads') }}?aff_network_name=${selectPartner}&conversion_status=${status}&created_at_from=${selectDateFrom}&created_at_to=${selectDateTo}&t_id=${selectTracker}`;
                statusName.classList.add('px-1', 'py-1', 'mx-1', 'rounded-md', 'hover:bg-orange-100');
                statusName.innerText = status.toUpperCase() + ": ";

                var blockUWithStatusName = document.createElement('u');
                blockUWithStatusName.classList.add('uppercase')
                blockUWithStatusName.innerText = statisticsPayload[status]

                var spanWithQuantity = document.createElement('span');
                spanWithQuantity.classList
                    .add('p-2', 'rounded-md', 'bg-green-300');
                spanWithQuantity.append(blockUWithStatusName)
                statisticsCell.append(statusName)
                statisticsCell.append(spanWithQuantity)
                rowWithPartnersStatistics.append(statisticsCell)
            }
            statisticsCanvas.append(rowWithPartnersStatistics)
        };
        req.send(JSON.stringify(payload));
    });
</script>
