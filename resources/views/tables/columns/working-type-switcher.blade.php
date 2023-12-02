<div>
    @php
    switch ($getState()) {
        case 0:
            $workingType = 'A勤';
            break;

        case 1:
            $workingType = 'B勤';
            break;

        case 2:
            $workingType = 'C勤';
            break;

        case 3:
            $workingType = 'D勤';
            break;
    
        default:
            $workingType = '';
            break;
    }
    @endphp
    {{ $workingType }}
</div>

