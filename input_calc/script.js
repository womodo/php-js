$(document).ready(function () {
    const maxTotal = $("#input1").attr('max'); // 合計の最大値

    
    $("#input1").on("input", function () {
        let input1 = parseInt($("#input1").val()) || 0;
        let input2 = parseInt($("#input2").val()) || 0;
        let input3 = parseInt($("#input3").val()) || 0;
        
        // input1の上限は maxTotal - input3
        if (input1 > maxTotal - input3) {
            input1 = maxTotal - input3;
        }
        if (input2 > 0) {
            // 残りをinput2に割り当て
            input2 = maxTotal - input1 - input3;
        }

        // 負の値を防ぐ
        input1 = Math.max(0, input1);
        input2 = Math.max(0, input2);

        // 値を反映
        $("#input1").val(input1);
        $("#input2").val(input2);
    });


    $("#input2").on("input", function () {
        let input1 = parseInt($("#input1").val()) || 0;
        let input2 = parseInt($("#input2").val()) || 0;
        let input3 = parseInt($("#input3").val()) || 0;

        let total = input1 + input2 + input3;

        // input2の直接調整: 合計を超えた場合のみ調整
        if (total > maxTotal) {
            input2 -= total - maxTotal;
        }
    });


    $("#input3").on("input", function () {
        let input1 = parseInt($("#input1").val()) || 0;
        let input2 = parseInt($("#input2").val()) || 0;
        let input3 = parseInt($("#input3").val()) || 0;
        
        // input1の上限は maxTotal - input3
        if (input3 > maxTotal - input1) {
            input3 = maxTotal - input1;
        }
        if (input2 > 0) {
            // 残りをinput2に割り当て
            input2 = maxTotal - input1 - input3;
        }

        // 負の値を防ぐ
        input3 = Math.max(0, input3);
        input2 = Math.max(0, input2);

        // 値を反映
        $("#input3").val(input3);
        $("#input2").val(input2);
    });
});
