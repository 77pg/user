window.onload = function () {
    uid.addEventListener('blur', function () {
        fetch(`e1.php?uid=${uid.value}`)
            .then(function (response) {
                return response.text();
            })
            .then(function (data) {
                data = data.trim();
                userinfo.innerHTML = data;
            });
    });
    
}
