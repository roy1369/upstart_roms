<div>
    @php
        $amount = $getState(); // メソッドを呼び出す

        $amount = '有給残日数' . (string)$amount . '日';
    @endphp

    {{ $amount }}
</div>
