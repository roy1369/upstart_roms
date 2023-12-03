<div>
    @php
    switch ($getState()) {
        case 0:
            $type = '打刻修正';
            break;

        case 1:
            $type = '有給申請';
            break;

        case 2:
            $type = '交通費申請';
            break;

        default:
            $type = '';
            break;
    }
    @endphp
    {{ $type }}
</div>

