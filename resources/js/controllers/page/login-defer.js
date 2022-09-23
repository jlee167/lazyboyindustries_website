import * as OAuthUI from "../../libs/oAuth/ui_setup";
import {authWithOauth, authWithUsername} from "../../api/auth/auth";


/* --------------------------------- Vue App -------------------------------- */

// App
let loginApp = new Vue({
    el: "#view-login",
    data: {
        resourcesLoaded: false
    },
    methods: {
        nonSocialLogin: nonSocialLogin,
        setupOAuth: setupOAuth,
    },

    mounted: function() {
        this.setupOAuth();
    }
});


/**
 * Authenticate with Kakao OAuth
 *
 * @param {String} accessToken
 */
function kakaoLogin(accessToken) {
    authWithOauth(accessToken, 'kakao')
    .then(() => {
        window.location.href = redirectUrl;
    })
    .catch((err) => {
        window.alert(`${err.name}: ${err.message}`)
        console.trace();
    });
};


/* Authenticate with username and password */
function nonSocialLogin() {
    const username = document.getElementById('input_account').value;
    const password = document.getElementById('input_password').value;

    authWithUsername({
        username: username,
        password: password
    })
    .then(() => {
        window.location.href = redirectUrl;
    })
    .catch((err) => {
        window.alert(`${err.name}: ${err.message}`)
        console.trace();
    });
}


/**
 * Authenticate with Google OAuth
 *
 * @param {String} accessToken
 */
function googleLogin(accessToken) {
    authWithOauth(accessToken, 'google')
    .then(() => {
        window.location.href = redirectUrl;
    })
    .catch((err) => {
        window.alert(`${err.name}: ${err.message}`)
        console.trace();
    });
};


/* Initialize OAuth modules */
function setupOAuth(){
    OAuthUI.setupGoogleAuth(window.env.GOOGLE_APP_KEY, "googleAuthBtn", googleLogin);
    OAuthUI.setupKakaoAuth(window.env.KAKAO_APP_KEY, kakaoLogin);
}




/* ---------------------------- Page Init Routine --------------------------- */

/* Delays view output until resources are loaded */
window.onload = () => {
    setTimeout(() => {
        loginApp.resourcesLoaded=true;
    }, 200);
}
