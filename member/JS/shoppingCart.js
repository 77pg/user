// 購物車商品數量加減功能
document.addEventListener('DOMContentLoaded', function () {
    var decreaseButtons = document.querySelectorAll('.decreaseBtn');
    var increaseButtons = document.querySelectorAll('.increaseBtn');

    decreaseButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var quantityDiv = btn.closest('.quantity');
            var numDiv = quantityDiv.querySelector('.num');
            var stock = parseInt(quantityDiv.getAttribute('data-stock'), 10); // 假設您也存儲了庫存在data-stock屬性中
            var currentCount = parseInt(numDiv.innerHTML, 10);

            if (currentCount > 1) {
                currentCount--;
                numDiv.innerHTML = currentCount;
            }
            updateSubtotal(quantityDiv);
        });
    });

    increaseButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var quantityDiv = btn.closest('.quantity');
            var numDiv = quantityDiv.querySelector('.num');
            var stock = parseInt(quantityDiv.getAttribute('data-stock'), 10);
            var currentCount = parseInt(numDiv.innerHTML, 10);

            if (currentCount < stock) {
                currentCount++;
                numDiv.innerHTML = currentCount;
            }
            updateSubtotal(quantityDiv);
        });
    });

    // 更新小計的價格
    function updateSubtotal(quantityDiv) {
        var itemId = quantityDiv.getAttribute('data-item-id');
        var price = parseInt(quantityDiv.getAttribute('data-price'));
        var numDiv = quantityDiv.querySelector('.num');
        var currentCount = parseInt(numDiv.innerHTML, 10);

        var subtotal = price * currentCount;

        var subtotalElement = document.querySelector('[data-subtotal="' + itemId + '"]');
        if (subtotalElement) {
            subtotalElement.textContent = 'NT' + subtotal.toLocaleString();
        }
        // 小記更新時 同步更新總計
        updateTotal();
    }

    // 更新商品總計函式
    function updateTotal() {
        var subtotals = document.querySelectorAll('[data-subtotal]');
        var calculatedTotal = 0;

        subtotals.forEach(function (subtotalElement) {
            calculatedTotal += parseFloat(subtotalElement.textContent.replace('NT', '').replace(/,/g, ''));
        });

        // 動態調整運費
        let freightValueElement = document.getElementById("freightValue");

        if (calculatedTotal >= 1000) {
            freightValueElement.textContent = "0";
        } else {
            freightValueElement.textContent = "999";
        }

        var freight = parseFloat(freightValueElement.textContent); // 從freightValueElement中獲取最新的運費
        var discount = parseFloat(document.querySelector('.discount .nt').textContent);

        calculatedTotal += freight;
        calculatedTotal -= discount;

        document.querySelector('#totalValue.count').textContent = calculatedTotal.toLocaleString();
    }

});