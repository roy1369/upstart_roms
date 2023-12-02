<div>
    @php
    switch ($getState()) {
        case 0:
            $workingAddress = 'A店';
            break;

        case 1:
            $workingAddress = 'B店';
            break;

        case 2:
            $workingAddress = 'C店';
            break;

        case 3:
            $workingAddress = 'D店';
            break;
    
        case 4:
            $workingAddress = 'E店';
            break;
    
        default:
            $workingAddress = '';
            break;
    }
    @endphp
    {{ $workingAddress }}
</div>
