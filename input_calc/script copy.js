$(document).ready(function () {
    const maxTotal = $("#input1").attr('max'); // 合計の最大値

    function adjustValues(changedInput) {
        let input1 = parseInt($("#input1").val()) || 0;
        let input2 = parseInt($("#input2").val()) || 0;
        let input3 = parseInt($("#input3").val()) || 0;

        let total = input1 + input2 + input3;

        // 合計がmaxTotalを超えないように調整
        if (changedInput === "input1") {
            // input1の上限は maxTotal - input3
            if (input1 > maxTotal - input3) {
                input1 = maxTotal - input3;
            }
            if (input2 > 0) {
                input2 = maxTotal - input1 - input3; // 残りをinput2に割り当て
            }
        } else if (changedInput === "input3") {
            // input3の上限は maxTotal - input1
            if (input3 > maxTotal - input1) {
                input3 = maxTotal - input1;
            }
            if (input2 > 0) {
                input2 = maxTotal - input1 - input3; // 残りをinput2に割り当て
            }
        } else if (changedInput === "input2") {
            // input2の直接調整: 合計を超えた場合のみ調整
            if (total > maxTotal) {
                input2 -= total - maxTotal;
            }
        }

        // 負の値を防ぐ
        input1 = Math.max(0, input1);
        input2 = Math.max(0, input2);
        input3 = Math.max(0, input3);

        // 値を反映
        $("#input1").val(input1);
        $("#input2").val(input2);
        $("#input3").val(input3);
    }

    // 入力変更イベント
    $("#input1").on("input", function () {
        adjustValues("input1");
    });

    $("#input2").on("input", function () {
        adjustValues("input2");
    });

    $("#input3").on("input", function () {
        adjustValues("input3");
    });

    // 初期値の調整
    adjustValues();
});
