const URI_VERIFY_2FA = '/auth/2fa';

window.getOtpSecret = () => {
    return String(document.getElementById("otpSecret").value).trim();
}


/* Authenticate with server with Google OTP */
window.verify2FaKey = (secret) => {
    fetch(URI_VERIFY_2FA, {
        method: 'post',

        body: JSON.stringify({
            "secret": secret
        }),

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
    .then(response => {
        if (response.status == 200) {
            return response.json();
        } else {
            window.alert("Request returned with code " + String(response.status));
            return;
        }
    })
    .then(jsonData => {
        if (jsonData.result) {
            window.location.href = jsonData.redirectUrl;
        }
        else {
            window.alert(jsonData.error);
            return;
        }
    })
    .catch((error) => {
        console.error(error);
    });
}
