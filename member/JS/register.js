window.onload = function () {
    uid.addEventListener('blur', function () {
        fetch(`formuid.php?uid=${uid.value}`)
            .then(function (response) {
                return response.text();
            })
            .then(function (data) {
                data = data.trim();
                userinfo.innerHTML = data;
            });
    });
    email.addEventListener('blur', function () {
        fetch(`formemail.php?email=${email.value}`)
            .then(function (response) {
                return response.text();
            })
            .then(function (data) {
                data = data.trim();
                emailinfo.innerHTML = data;
            });
    });
    
}
