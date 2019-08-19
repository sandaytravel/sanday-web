<table>
    <thead>
        <tr>
            <td style="font-weight: bold;">Reference#</td>
            <td style="font-weight: bold;">Booking Date</td>
            <td style="font-weight: bold;">Activity</td>
            <td style="font-weight: bold;">Package</td>
            <td style="font-weight: bold;">Traveler Name</td>
            <td style="font-weight: bold;">Booking Status</td>
            <td style="font-weight: bold;">Amount</td>
        </tr>
    </thead>
    <tbody>
        @php $totalEarning = 0; @endphp
        @if(count($bookings))
        @foreach($bookings as $key => $value)
        <tr>
            <td>{{$value->order_number}}</td>
            <td>{{$value->booking_date}}</td>
            <td>{{($value->activity != null) ? $value->activity->title : "--"}}</td>
            <td>{{($value->oredr_ietms != null) ? $value->oredr_ietms[0]->activitypackageoptions->package_title : "--"}}</td>
            <td>{{($value->user != null) ? $value->user->name : "--"}}</td>
            <td>
                @if($value->status == 0)
                {{"Pending"}}                                            
                @elseif($value->status == 1)
                {{"Canceled"}}
                @else
                {{"Confirmed"}}       
                @endif
            </td>
            <td>RM {{$value->order_total}} @php $totalEarning += $value->order_total; @endphp</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="6" style="text-align: right;"><b>Total Earning</b></td>
            <td><b>RM {{number_format($totalEarning,2)}}</b></td>
        </tr>
        @endif
    </tbody>
</table>