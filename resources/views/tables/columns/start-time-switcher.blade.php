<div>
    @php
    $startTime = '';
    if (!is_null($getState())) {
        // $getState()の値を取得
        $getStateValue = $getState();

        // DateTimeオブジェクトを作成し、指定された日付を解析
        $dateObject = new \DateTime($getStateValue);

        // 2023年6月1日の形式にフォーマット
        $formattedDate = $dateObject->format('G時i分');

        // 変換された日付を含む文言の作成
        $startTime = '出勤時間：' . $formattedDate;
    }
    @endphp

    {{ $startTime }}
</div>