<x-filament::widget>
    <x-filament::card>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Laravel Reverse Geocoding</title>
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <script>
                    // LaravelのconfigからGoogle Maps APIキーを取得
                    var googleMapsApiKey = '{{ config('services.google_maps.api_key') }}';

                    // マップの初期化を行う関数
                    function initMap() {
                        // ウェブブラウザが位置情報取得をサポートしているか確認
                        if (navigator.geolocation) {
                            // 現在の位置情報を取得し、取得成功時にgetAddress関数を呼び出す
                            navigator.geolocation.getCurrentPosition(getAddress);
                        } else {
                            // 位置情報取得がサポートされていない場合のエラーメッセージ
                            console.error("このブラウザは位置情報の取得をサポートしていません。");
                        }
                    }

                    // 緯度経度から住所を取得する関数
                    function getAddress(position) {
                        // Google Maps Geocoderオブジェクトの作成
                        var geocoder = new google.maps.Geocoder();
                        // 現在の緯度経度情報
                        var latlng = {lat: position.coords.latitude, lng: position.coords.longitude};

                        // Geocoderを使用して緯度経度から住所を取得
                        geocoder.geocode({'location': latlng}, function(results, status) {
                            if (status === 'OK') {
                                // Geocodingが成功した場合
                                if (results[0]) {
                                    // 取得した住所を表示エリアに表示
                                    var address = results[0].formatted_address;

                                    // 「日本、〒（郵便番号）」までの部分を削除
                                    address = address.replace(/^日本、〒\d+-\d+\s*/, '');

                                    document.getElementById('address').innerText = address;

                                    $(document).ready(function() {
                                    // Ajaxを使用してサーバーにデータを送信
                                        $.ajax({
                                            type: 'POST',
                                            url: '/save-location', // 保存処理を行うルートへのURL）
                                            data: {
                                                address: address,
                                            },
                                            headers: {
                                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                            }
                                            
                                        })
                                        .done((data) => {
                                            console.log('データベースに保存されました。');
                                        })
                                        .fail((adta) => {
                                            console.error('データベース保存エラー:', error);
                                        });
                                    });
                                    
                                } else {
                                    // 結果がない場合のエラーメッセージ
                                    console.error('結果が見つかりませんでした');
                                }
                            } else {
                                // Geocodingが失敗した場合のエラーメッセージ
                                console.error('Geocodingに失敗しました。ステータス: ' + status);
                            }
                        });
                    }
                </script>
                <!-- Google Maps JavaScript APIの読み込み -->
                <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" defer></script>
                <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            </head>
            <body>
                <!-- 住所を表示するためのエリア -->
                <div style="font-weight: bold; font-size: 1.2em;">現在の住所</div>
                <div id="address"></div>
            </body>
        </html>

    </x-filament::card>
</x-filament::widget>
