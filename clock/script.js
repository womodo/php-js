// script.js
document.addEventListener('DOMContentLoaded', function () {
    // チャートを描画するためのDOM要素を取得
    var chartDom = document.getElementById('myChart');
    // EChartsオブジェクトをインスタンス化
    var myChart = echarts.init(chartDom);
    
    // 時間割データ
    var data = [
        { value: 60, name: '数学' },
        { value: 60, name: '英語' },
        { value: 15, name: '休憩' },
        { value: 60, name: '理科' },
        { value: 45, name: '昼食' },
        { value: 60, name: '体育' },
        { value: 60, name: '社会' },
        { value: 60, name: '自習' }
    ];
    
    // チャートオプション
    var option = {
        title: {
            text: '時間割円グラフ',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b} : {c} 分 ({d}%)'
        },
        series: [
            {
                name: '時間割',
                type: 'pie',
                radius: '55%',
                center: ['50%', '60%'],
                data: data,
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                },
                label: {
                    show: true, // ラベルを表示するかどうか
                    formatter: '{b} ({c}分)' // ラベルのフォーマット
                }
            }
        ]
    };
    
    // チャートにオプションを設定
    myChart.setOption(option);
});
