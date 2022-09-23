
import AuthFailure from '../../exceptions/http/auth/AuthFailure';
import * as AuthAPI from '../../api/auth/auth';
import * as UserAPI from '../../api/user/user';



/* -------------------------------------------------------------------------- */
/*                           Navbar Vue Application                           */
/* -------------------------------------------------------------------------- */
window.navbarApp = new Vue({
    el: "#mainNavbar",
    data: {
        show: true,
        prevColor: null,
        navColor: "rgba(18, 18, 18, 0.9) !important",
        bottomBorder: "",//"1px rgba(0,27,55,0.1) solid",
    },
    methods: {
        enableModal: enableModal,
        logout: logout,
    }
});


/* Activates resume modal */
function enableModal() {
    document.body.style.height = '100vh';
    if (!window.hasOwnProperty('fullScroll'))
        document.body.style.overflowY = 'hidden';
    else
        scrollApp.disable();
    modalApp.showModal = true;
}


/**
 * Logout
 *
 * @param {String} username
 * @param {String} password
 */
function logout() {

    AuthAPI.logout()
    .then(() => {
        window.location.reload();
    })
    .catch(err => {
        window.alert(`${err.name}: ${err.message}`);
    })
}




/* ----------------------------- Startup Routine ---------------------------- */

/* Setup login button */
const targetUrl = window.location.href.split(window.env.STREAM_URL);
if (document.getElementById('signBtn')) {
    document.getElementById('signBtn').href = "/login/redirect" + targetUrl[targetUrl.length - 1];
}

/* Register navbar collape & expand callbacks */
$('#collapsibleNavbar').on('show.bs.collapse', function () {
    navbarApp.prevColor = navbarApp.navColor;
    navbarApp.navColor = "#121212 !important";
});
$('#collapsibleNavbar').on('hidden.bs.collapse', function () {
    navbarApp.navColor = navbarApp.prevColor;
});


/* Setup user profile image */
UserAPI.getSelfImage()
.then(json => {
    let image = document.getElementById('profileImage');
    image.src = json.url;
})
.catch(err => {
    if (err instanceof AuthFailure) {
        return;
    } else {
        console.trace();
        window.alert(`${err.name}: ${err.message}`);
    }
});
