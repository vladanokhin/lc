@extends('app.layout')

@section('title', "$partnerName scheme")

@section('content')
  <div class="w-full pt-3 bg-gray-100">
    <div class="pt-3">
      <div class="p-4 drop-shadow-sm text-2xl text-gray-600 text-center">
        <h3 class="font-bold pl-2 uppercase">{{ $partnerName }}</h3>
      </div>
    </div>
    <div class="text-center my-6">
      <table class="w-8/12 m-auto bg-white shadow-md bg-gray-200 border-separate">
        @if(empty($scheme))
          <p class="text-center text-md py-2">Status scheme is empty</p>
        @else
          <tr class="shadow-sm text-center justify-between py-1">
            <td class="text-center px-3 text-xl font-bold p-3 w-1/4">Ad2Lynx status</td>
            <td class="text-center px-3 text-xl font-bold p-3 w-3/4">Related statuses</td>
          </tr>
          <tbody class="bg-white">
          @foreach($scheme as $status_category_name => $payload)
            <tr class="shadow-sm">
              <td class="text-center px-3 font-bold">{{ $status_category_name }}</td>
              @forelse ($payload as $status_payload)
                <td class="text-center px-3 grid grid-cols-3">
                  <span class="whitespace-nowrap bg-green-300 p-3 rounded-md mx-4 text-xl my-3">{{ $status_payload->incoming_status_name }}
                    <br><i class="text-red-600 cursor-pointer fas fa-minus-circle px-3"
                           onclick="removeRelatedStatus({{ $status_payload->id }}, this)"></i>
                    @if($status_payload->status_locked == 1)
                      <i class="cursor-not-allowed text-blue-600 ml-2 fas fa-lock"></i>
                    @endif
                    @if($status_payload->accept_payment == 1)
                      <i class="cursor-not-allowed text-blue-600 ml-2 fas fa-dollar-sign"></i>
                    @endif
                    @if($status_payload->add_event_2 == 1)
                      <span
                        class="cursor-not-allowed font-sans backdrop-blur-xl bg-blue-900 rounded-xl text-white px-2">2</span>
                    @endif
                  </span>
                </td>
              @empty
              @endforelse
            </tr>
          @endforeach
          </tbody>
        @endif
      </table>
      <div class="w-full m-auto my-8">
        @include("lead-collector.settings.status-scheme.add-status")
      </div>
      <div class="w-full m-auto my-8">
        <a href="{{ route('add-new-ad2lynx-status-category') }}"
           class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 text-xl">Add
          Ad2Lynx category
        </a>
      </div>
    </div>
  </div>


  <script>
    let commitStatus = document.getElementById("add_status_form");
    commitStatus.addEventListener("submit", function (e) {
      e.preventDefault();
      let elem = this.elements;
      var statusPayload = {};
      for (const elemElement of elem) {
        switch (elemElement.id) {
          case 'partner_name':
            statusPayload['partner_name'] = elemElement.value;
            break;

          case 'ad2lynx_status':
            statusPayload['ad2lynx_status'] = elemElement.value;
            break;

          case 'new_status':
            statusPayload['new_status'] = elemElement.value;
            break;

          case 'lock_lead_status':
            statusPayload['lock_lead_status'] = elemElement.checked;
            break;

          case 'accept_payment_for_status':
            statusPayload['accept_payment_for_status'] = elemElement.checked;
            break;

          case 'add_event_2':
            statusPayload['add_event_2'] = elemElement.checked;
            break;

          default:
            break;
        }
      }
      fetch('{{ route('statusSchemeAdd') }}', {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json'
        },
        method: 'POST',
        cache: 'no-cache',
        redirect: 'follow',
        body: JSON.stringify(statusPayload)
      })
        .then((response) => response.json())
        .then((data) => {
          if (data['status'] === 'ok') {
            location.reload();
          } else {
            document.getElementById('status-error-block').classList.remove('hidden');
            document.getElementById('status-error-message').innerHTML = data['status'];
            document.getElementById('status-error-closer').addEventListener('click', function (e) {
              e.preventDefault();
              document.getElementById('status-error-block').classList.add('hidden');
              document.getElementById('status-error-message').innerHTML = '';
            });
          }
        });
    });

    function removeRelatedStatus(id, elem) {
      let route = "{{ route('delete-related-status', ':id') }}".replace(':id', id);
      fetch(route, {
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Content-Type': 'application/json'
        },
        method: 'DELETE',
        cache: 'no-cache',
        redirect: 'follow',
      })
        .then(res => res.json())
        .then(function (res) {
          if (res['message'] === 'Status removed') {
            const iElems = elem.parentElement.querySelectorAll("i")
            elem.parentElement.classList.remove('bg-green-300')
            elem.parentElement.classList.add('bg-red-600')
            elem.parentElement.classList.add('text-white')
            for (const iElem of iElems) {
              iElem.remove()
            }
          } else {
            elem.parentElement.classList.remove('bg-green-300')
            elem.parentElement.classList.add('bg-teal-700')
            elem.parentElement.classList.add('text-white')
            const div = document.createElement("div");
            const p = document.createElement("p");
            p.innerHTML = "Error";
            p.classList.add('text-red-500')
            div.appendChild(p);
            elem.parentElement.querySelector("i").replaceWith(div)
          }
        })
    }
  </script>
@endsection
