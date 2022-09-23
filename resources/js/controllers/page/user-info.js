import * as Google2FaAPI from '../../data/net/http/auth/google2fa';
import User from './../../models/user/User';
import UserDTO from './../../models/user/UserDTO';


const URI_MYINFO = '/self'
const URI_USER = '/members';
const URI_CHANGE_PASSWORD = '/members/password';
const URI_CHANGE_USER_INFO = '/members';
const URI_REQUEST_2FA_CODE = '/members/2fa-key';

const MSG_PASSWORD_CHANGED = "Password Changed. Please login again.";


/* --------------------------------- Vue App -------------------------------- */
const Views = {
    PersonalInfo: 1,
    ChangePassword: 2,
    Google2FA: 3,
    DeleteAccount: 4,
}

window.userInfoApp = new Vue({
    el: '#main',
    data: {
        user: null,
        qrCodeRecv: false,
        currentView: Views.PersonalInfo,
        showQR: false,
    },

    computed: {
        PersonalInfoView: function () {
            return (this.currentView === Views.PersonalInfo)
                    && (this.user != null);
        },

        ChangePasswordView: function () {
            return this.currentView === Views.ChangePassword;
        },

        Google2FAview: function () {
            return this.currentView === Views.Google2FA;
        },

        DeleteAccountView: function () {
            return this.currentView === Views.DeleteAccount;
        },
    },

    methods: {
        enable2FA: enable2FA,
        disable2FA: disable2FA,
        activate2FA: activate2FA,
        changeUserInfo: changeUserInfo,
        changePassword: changePassword,
        getMyInfo: getMyInfo,
        watchPersonalInfo: function() {this.currentView = Views.PersonalInfo},
        watchChangePassword: function() {this.currentView = Views.ChangePassword},
        watchGoogle2FA: function() {this.currentView = Views.Google2FA},
        watchDeleteAccount: function() {this.currentView = Views.DeleteAccount},
        deleteUser: deleteUser,
    },

    async created() {
        await this.getMyInfo();
        return;
    }
});


/* -------------------------------- API Calls ------------------------------- */

function changeUserInfo() {
    let input = document.querySelector('input[type="file"]');
    let data = new FormData();
    data.append('imgFile', input.files[0]);
    data.append('username', document.getElementById('username').value);

    fetch(URI_CHANGE_USER_INFO, {
        method: 'post',
        body: data,
        headers: {
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                window.location.href = window.location.href;
                return response.json();
            } else {
                window.alert(`${response.status}: Unknown error`);
            }
        })
        .catch(err => {
            console.error(`${err.name}: ${err.message}`);
        })

}

function changePassword() {
    fetch(URI_CHANGE_PASSWORD, {
        method: 'put',
        body: JSON.stringify({
            "currentPassword": String(document.getElementById('currentPassword').value).trim(),
            "newPassword": String(document.getElementById('newPassword').value).trim(),
            "confirmPassword": String(document.getElementById('confirmPassword').value).trim(),
        }),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                window.alert(MSG_PASSWORD_CHANGED);
                window.location.href = '/views/login';
            } else {
                response.json().then((jsonData) => {
                    window.alert(`Error(${response.status}): ${jsonData.error}`);
                });
            }
        })
        .catch(err => {
            console.error(`${err.name}: ${err.message}`);
            window.alert(`${err.name}: ${err.message}`);
        })
}



function deleteUser() {
    fetch(URI_USER, {
        method: 'delete',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                window.location.href = '/views/main';
            } else (response.json().then((jsonData) => {
                window.alert(`Error: ${jsonData.error}`);
            }))
        })
        .catch(err => {
            console.error(`${err.name}: ${err.message}`);
            window.alert(`${err.name}: ${err.message}`);
        });
}



function enable2FA() {
    fetch(URI_REQUEST_2FA_CODE, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                response.json().then((json) => {
                    this.showQR = true;
                    //document.getElementById("QRimage").style.display = "block";
                    this.$refs.QRimage.src = json.qrCodeUrl;
                    userInfoApp.qrCodeRecv = true;
                });
            } else {
                window.alert(`Error(${response.status})`);
            }
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            console.error(`${err.name}: ${err.message}`);
        });
}


function disable2FA() {
    fetch(URI_REQUEST_2FA_CODE, {
        method: 'delete',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                window.location.href = "/views/user-info";
            } else {
                window.alert(`Error(${response.status}): ${jsonData.error}`);
            }
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            console.error(`${err.name}: ${err.message}`);
        });
}


function activate2FA() {
    const otpSecret = String(document.getElementById("otpSecret").value).trim();

    Google2FaAPI.activate2FaKey(otpSecret)
    .then(() => {
        window.location.href =  window.location.href;
    })
    .catch(err => {
        window.alert(`${err.name}: ${err.message}`);
        console.error(`${err.name}: ${err.message}`);
    });
}


/* -------------- Page start up routine: fetch user information ------------- */
function getMyInfo() {
    return fetch(URI_MYINFO, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                return response.json();
            }
        })
        .then(json => {
            this.user = new User(new UserDTO(json));
        })
        .catch(err => {
            console.error(err);
        });
}
