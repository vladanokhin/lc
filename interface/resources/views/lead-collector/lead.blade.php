<a href="{{ url()->previous() }}">Return</a>
<div class="col-md-12 text-center">lead info</div>

<table style="border: 1px solid black; width: 90%; margin: 0 auto;">
    <tr>
        @foreach($lead as $item => $data)
            <th style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 2px;">{{ $item }}</th>
        @endforeach
    </tr>
    <tbody>
    <tr>
        @foreach($lead as $item => $data)
            <td style="border-right: 1px solid black; border-bottom: 1px solid black; padding: 2px;">{{ $data }}</td>
        @endforeach
    </tr>
    </tbody>
</table>
