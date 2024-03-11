@include('app.leads-flow.fix-leads-data')

<table class="w-max-content m-auto bg-white shadow-md bg-gray-200" id="leads-table">
  @if(empty($leads))
    <p class="text-center text-md py-2"> No data required</p>
  @else
    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" id="csrf-token">
    @include('app.parts.pagination')
    @foreach($leads as $item => $element)
      <tr class="shadow-sm text-center justify-between py-1">
        @if(
$element == 'country_code'   ||
$element == 'is_deleted'     ||
$element == 'updated_at'     ||
$element == 'product'        ||
$element == 'data_1'         ||
$element == 'data_2'         ||
$element == 'data_3'         ||
$element == 't_id')
          @continue
        @endif
        <td class="text-center px-3">Select</td>
        <td class="text-center px-3">Partner</td>
        <td class="text-center px-3">Click ID</td>
        <td class="text-center px-3">User name</td>
        <td class="text-center px-3">Phone</td>
        <td class="text-center px-3">Internal status</td>
        <td class="text-center px-3">Incoming status</td>
        <td class="text-center px-3">Offer ID</td>
        <td class="text-center px-3">Offer Name</td>
        <td class="text-center px-3">Created</td>
      </tr>
      @break
    @endforeach
    <tbody class="bg-white">
    @foreach($leads as $lead)
      <tr class="shadow-sm" id="{{ $lead->id }}" partner="{{ $lead->aff_network_name }}">
        @if(
$lead == 'country_code' ||
$lead == 'is_deleted'   ||
$lead == 'updated_at'   ||
$lead == 'product'      ||
$lead == 'data_1'       ||
$lead == 'data_2'       ||
$lead == 'data_3'       ||
$lead == 't_id'
            )
          @continue
        @endif

        <td class="text-center py-3 px-4">
          <input name="massAssign" type="checkbox" value="{{ $lead->click_id }}"
                 class="form-checkbox h-6 w-6 rounded-md text-red-500 border-2 border-black">
        </td>
        <td class="text-center px-3">
          {{ \Illuminate\Support\Str::limit($lead->aff_network_name, 22, $end='...') }}
        </td>
        <td class="text-center px-3">
          <a href="{{ route('lead', ['clickid' => trim($lead->click_id, '{}/\\')]) }}"
             class="bg-green-300 px-3 rounded-md">
            {{ $lead->click_id }}
          </a>
        </td>
        <td class="text-center px-3">
          {{ \Illuminate\Support\Str::limit($lead->name, 12, $end='...') }}</td>
        <td class="text-center px-3">
          {{ \Illuminate\Support\Str::limit($lead->phone, 16, $end='...') }}</td>
        <td class="text-center px-3">{{ $lead->category }}</td>
        <td class="text-center px-3">
          {{ \Illuminate\Support\Str::limit($lead->conversion_status, 14, $end='...') }}</td>
        <td class="text-center px-3">{{ $lead->offer_id }}</td>
        <td class="text-center px-3">
          {{ \Illuminate\Support\Str::limit($lead->offer_name, 32, $end='...') }}
        </td>
        <td class="text-center px-3">{{ date('d-m H:i', strtotime($lead->created_at)) }}</td>
        <td class="text-center px-3">
          <div class="dropdown">
            <button id="lc-menu" class="py-1 px-2 rounded bg-green-300 shadow-md ">Actions <i
                class="fas fa-torii-gate"></i></button>
            <span id="dropdown" class="overflow-auto z-10 hidden absolute bg-gray-100 border-2 rounded-sm">
                            <button value="{{ $lead->id }}" id="statusRefresher" data-id="{{ $lead->click_id }}"
                                    class=" p-2 block relative hover:bg-green-300 w-full shadow-sm">Refresh</button>
                            <button value="{{ $lead->id }}" id="leadReorder" data-id="{{ $lead->click_id }}"
                                    class=" p-2 block relative hover:bg-green-300 w-full shadow-sm">Reorder</button>
                            <button value="{{ $lead->click_id }}" id="editAndReorderLead" uname="{{ $lead->name }}"
                                    uphone="{{ $lead->phone }}" data-id="{{ $lead->id }}"
                                    class="p-2 block relative hover:bg-green-300 w-full shadow-sm">Edit and reorder</button>
                            <button value="{{ $lead->id }}" id="deleteLead" data-id="{{ $lead->unique_id }}"
                                    class="p-2 block relative hover:bg-red-700 hover:text-white w-full shadow-sm ">Remove</button>
                        </span>
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
</table>
@endif
</div>

<script>
  let canvas = document.getElementById('main-canvas'),
    inputs = canvas.querySelectorAll("input[type=checkbox]"),
    btnMassRefresher = document.getElementById('massStatusRefresher'),
    btnMassReorder = document.getElementById('massLeadReorder'),
    btnMassEdit = document.getElementById('massLeadEditor'),
    clicksArray = []
  ;

  btnMassEdit.addEventListener('click', function (e) {
    e.preventDefault();
    const availableData = checkLeadsUpdateAvailability(inputs);
    const partner = availableData.partner;
    const leads = availableData.leads;
    const leadUpdateForm = document.getElementById('lead_update_form');
    if (leads.length >= 1) {
      toggleLeadFixModal();
      setupPayload(partner);
      leadUpdateForm.addEventListener('submit', function (e) {
        e.preventDefault();
      });
      document.getElementById('submit-updated-leads').addEventListener('click', function (e) {
        const newProduct = document.getElementById('new-product').value;
        const newData1 = document.getElementById('data_1').value;
        const newData2 = document.getElementById('data_2').value;
        const newData3 = document.getElementById('data_3').value;
        const msg = createQuestion(newProduct, newData1, newData2, newData3);
        if (confirm(msg)) {
          document.getElementById('c-id-list-for-leads-update').value = leads;
          submitLeadsFix(leadUpdateForm);
        }
      });
    } else {
      alert('Лиды не выбраны.');
    }
  });

  function submitLeadsFix(form) {
    form = new FormData(form);
    const data = JSON.stringify(Object.fromEntries(form.entries()))
    const res = fetch("{{ route('update-leads-payload') }}", {
      method: 'POST',
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      body: data
    });
    res
      .then(function (response) {
        return response.json()
      })
      .then(function (response) {
        if (response['status'] === 'success') {
          document.getElementById("update-popup-header").innerHTML = "All leads successfully updated! Resend complete!";
          document.getElementById("update-popup-header").classList.add("font-green-700", "rounded-md", "bg-green-200", "p-1");
          document.getElementById("submit-updated-leads").disabled = true;
          inputs.forEach(function (item, i) {
            item.classList.add('text-blue-600');
            item.classList.remove('text-red-500');
          })
        } else if (response['status'] === "leads updated but not reordered") {
          let payload = response['status'] + "\n";
          payload += document.getElementById('c-id-list-for-leads-update').value;
          createFileAndDownload(payload)
        } else {
          let payload = response['status'] + "\n";
          payload += document.getElementById('c-id-list-for-leads-update').value;
          createFileAndDownload(payload)
        }
      })
  }

  function createFileAndDownload(content) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,'+ content);
    element.setAttribute('download', "problem-with-leads.csv");
    element.style.display = 'none';
    document.body.appendChild(element);
    element.click();
    document.body.removeChild(element);
  }

  function createQuestion(newProduct, newData1, newData2, newData3) {
    let result = "Check data and press 'ok' if everything right.\n";
    if (newProduct.trim().length >= 1) {
      result += "New product ->  " + newProduct + "\n"
    }
    if (newData1.trim().length >= 1) {
      result += "New Data-1 ->  " + newData1 + "\n"
    }
    if (newData2.trim().length >= 1) {
      result += "New Data-2 ->  " + newData2 + "\n"
    }
    if (newData3.trim().length >= 1) {
      result += "New Data-3 ->  " + newData3 + "\n"
    }
    return result;
  }

  function setupPayload(partner) {
    document.getElementById('fix-partner-name').innerHTML = partner;
  }

  function toggleLeadFixModal() {
    document.getElementById('fix-leads-table').classList.toggle('hidden');
  }

  function checkLeadsUpdateAvailability(leads) {
    const result = [];
    var tmp_partner = "";
    leads.forEach(function (element) {
      if (element.checked) {
        const elementsParent = element.parentElement.parentElement;
        if (tmp_partner === "") {
          tmp_partner = elementsParent.getAttributeNode('partner').nodeValue;
        }
        if (tmp_partner === elementsParent.getAttributeNode('partner').nodeValue) {
          result.push(element.value)
        }
      }
    })
    return {"leads": result, "partner": tmp_partner};
  }

  function submitUpdate(payload) {
    //
  }

  btnMassRefresher.addEventListener('click', function () {
    fillList('refresh');
  });

  btnMassReorder.addEventListener('click', function () {
    fillList('reorder');
  });

  function fillList(action) {
    clicksArray = [];
    inputs.forEach((el) => {
      if (el.checked) {
        if (!clicksArray.includes(el)) {
          clicksArray.push(el);
        }
      }
    });
    deliverList(action, clicksArray);
  }

  function deliverList(action, list) {
    const leads_queue = [];
    //
    for (const lead of list) {
      if (lead.classList.contains('text-red-500')) {
        leads_queue.push(lead.value);
        lead.classList.add('text-green-500');
        lead.classList.remove('text-red-500');
      } else {
        console.log(`${lead.value} already handled!\n`);
      }
    }
    var req = new XMLHttpRequest();
    req.open("POST", '/mass-assign', true);
    req.setRequestHeader("Content-type", "application/json");
    req.setRequestHeader('X-CSRF-TOKEN', document.getElementById('csrf-token').value);
    req.onload = function () {
      console.log(req.responseText)
    }
    req.send(JSON.stringify({'data': leads_queue, 'action': action}));
  }

  // Select all button action
  const selectAllButton = document.getElementById('select-all');
  selectAllButton.addEventListener('click', function () {
    const leads_table = document.getElementById('leads-table');
    const selector_inputs = leads_table.getElementsByTagName("input");
    for (let i = 0; i < selector_inputs.length; i++) {
      if (selector_inputs[i].type === "checkbox") {
        selector_inputs[i].checked = !selector_inputs[i].checked;
      }
    }
  })

</script>
