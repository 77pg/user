async function checkout() {
    // 取得購物車中每項商品的名稱和價格
    const pnameElements = document.querySelectorAll('.pname[data-pname]');
    const priceElements = document.querySelectorAll('.price[data-price]');
    let productNames = [];
    let productPrices = [];

    pnameElements.forEach((el, index) => {
        productNames.push(el.getAttribute('data-pname'));
        productPrices.push(priceElements[index].getAttribute('data-price'));
    });

    // 將商品名稱陣列組合成一個用#隔開的字串
    const itemNameString = productNames.join('#');

    // 取得其他輸入框的值
    const name = document.getElementById('name').value;
    const phone = document.getElementById('phone').value;
    const city = document.getElementById('city').value;
    const district = document.getElementById('district').value;
    const address = document.getElementById('address').value;
    const totalValue = document.querySelector('#totalValue.count').textContent.trim();
    const totalAmount = Number(totalValue.replace(/,/g, ''));
    const fullAddress = `${city}${district}${address}`;

    const response = await fetch('CreateCreditOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ 
            itemNameString, 
            productPrices,
            totalAmount,
            name,
            phone,
            fullAddress
        })
    });
    // 從回傳的資料中解構賦值checkoutForm，並傳入變數
    const { checkoutForm } = await response.json();

    console.log('Response: ', response);
    console.log('Checkout Form: ', checkoutForm);

    // 將 checkoutForm 的內容加入至網頁中
    document.getElementById('ecpayFormContainer').innerHTML = checkoutForm;

    // 使用 setTimeout 確保表單已載入，然後執行 submit
    setTimeout(function() {
        document.getElementById("ecpay-form").submit();
    }, 1000); 
}


// async function checkout() {
//     const itemName = document.getElementById('itemName').value;
//     const totalAmount = document.getElementById('totalAmount').value;

//     const response = await fetch('../src/MyShop/CreateCreditOrder.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         // 將商品名稱和總金額轉成 JSON 格式
//         body: JSON.stringify({ 
//             itemName, 
//             totalAmount 
//         })
//     });

    
//     // 從回傳的資料中解構賦值checkoutForm，並傳入變數
//     const { checkoutForm } = await response.json();
    
//     console.log('Response: ', response);
//     console.log('Checkout Form: ', checkoutForm);

//     // 將 checkoutForm 的內容加入至網頁中
//     document.getElementById('ecpayFormContainer').innerHTML = checkoutForm;
    
//     // 使用 setTimeout 確保表單已載入，然後執行 submit
//     setTimeout(function() {
//         document.getElementById("ecpay-form").submit();
//     }, 1000); 
// }
