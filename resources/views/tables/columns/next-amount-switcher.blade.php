<div>
    @php
    $nextPaidHoliday = '';
    if (!is_null($getState())) {
        // $getState()の値を取得
        $getStateValue = $getState();

        // DateTimeオブジェクトを作成し、指定された日付を解析
        $dateObject = new \DateTime($getStateValue);

        // 2023年6月1日の形式にフォーマット
        $formattedDate = $dateObject->format('Y年n月j日');

        // 変換された日付を含む文言の作成
        $nextPaidHoliday = '次回有給取得予定日 ' . $formattedDate;
    }
    @endphp

    {{ $nextPaidHoliday }}
</div>