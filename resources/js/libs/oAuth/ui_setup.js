function attachSignin(element, onSuccessCallback) {
    auth2.attachClickHandler(element, {},
        function (googleUser) {
            onSuccessCallback(googleUser.getAuthResponse().id_token);
        },
        function (error) {
            //alert(JSON.stringify(error, undefined, 2));
        }
    );
}


function setupGoogleAuth(googleAppKey, attachElementID, onSuccessCallback) {
    gapi.load('auth2', function () {
        let auth2 = gapi.auth2.init({
            client_id: googleAppKey,
            cookiepolicy: 'single_host_origin',
        });

        auth2.attachClickHandler(document.getElementById(attachElementID), {},
            function (googleUser) {
                onSuccessCallback(googleUser.getAuthResponse().id_token);
            },
            function (error) {
                //alert(JSON.stringify(error, undefined, 2));
            }
        );

        //attachSignin(document.getElementById(attachElementID), onSuccessCallback);
    });
};


/* Set JavaScript Key of current app */
function setupKakaoAuth(kakaoAppKey, onSuccessCallback) {
    Kakao.init(kakaoAppKey);

    window.loginWithKakao = function () {
        Kakao.Auth.login({
            success: function (authObj) {
                var token_kakao = Kakao.Auth.getAccessToken();
                onSuccessCallback(token_kakao);
            },
            fail: function (err) {
                //alert(JSON.stringify(err))
            },
        })
    }
}

export {setupGoogleAuth, setupKakaoAuth};
