<div>
    @php
        $paidHoliday = App\Models\PaidHoliday::where('id', $getState())->first();

        $user = App\Models\User::where('id', $paidHoliday->user_id)-> first();
        
        $joiningDate = $user->joining_date;
    @endphp

    {{ $joiningDate }}
</div>
