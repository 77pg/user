document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('addForm');

    addForm.addEventListener('submit', function (event) {
        event.preventDefault(); // 阻止表單預設提交行為

        // 獲取表單字段的值
        const uid = document.getElementById('uid').value;
        const cname = document.getElementById('cname').value;
        const pwd = document.getElementById('upwd').value;
        const cphone = document.getElementById('cphone').value;
        const uemail = document.getElementById('uemail').value;
        const gender = document.querySelector('input[name="gender"]:checked').value;
        const state = document.querySelector('input[name="state"]:checked').value;

        // 使用 AJAX 傳遞表單數據到 PHP
        fetch('api/add.php', {
            method: 'POST',
            body: JSON.stringify({
                uid: uid,
                cname: cname,
                upwd: pwd,
                cphone: cphone,
                uemail: uemail,
                gender: gender,
                state: state
            }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.text())  // 將回應的內容轉換為純文本
        .then(data => {
            if (data === '會員新增成功') {
                alert('成功新增會員');
            } else {
                alert('新增失敗，此帳號已註冊過');
            }
        })
        .catch(error => {
            console.error('提交表單時出錯：', error);
            alert('提交表單時出錯');
        });
    });
});
