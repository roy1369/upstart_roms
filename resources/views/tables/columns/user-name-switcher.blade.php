<div>
    @php
        $user = App\Models\User::where('id', $getState())->first();
        $user = $user->name;
    @endphp
    {{ $user }}
</div>
