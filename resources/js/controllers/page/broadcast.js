import MjpegPlayer from "../../utils/media/mjpeg-player.js";
import WebSocketHeaders from "../../configs/stream/websocket-headers.js";
import NotFound from '../../exceptions/generic/not-found';
import { KakaoMap } from "../../utils/media/kakao-map.js";
import PcmPlayer from "../../utils/media/pcm-player";
import User from '../../models/user/user';
import UserDTO from '../../models/user/user-dto';
import StreamNotFound from '../../exceptions/http/stream/stream-not-found';
import TokenNotFound from '../../exceptions/http/auth/token-not-found';




videojs.Hls.xhr.beforeRequest = function (options) {
    //options.header = { token:"token-string" };
    //options.uri = options.uri + "?token=token-string";
    //return options;
}


/* Enum Variables */
const VideoFormat = Object.freeze({
    MJPEG: "MJPEG",
    RTMP: "RTMP"
});

const ViewMode = Object.freeze({
    DESKTOP: "Desktop",
    MOBILE: "Mobile",
});

/* Page/Stream info */
const urlParts = window.location.href.split('/');
const streamID = Number(urlParts[urlParts.length - 1]);




/* -------------------------------------------------------------------------- */
/*                             VUE Application                                */
/* -------------------------------------------------------------------------- */
window.broadcastApp = new Vue({
    el: '#container-top',

    data: {
        user: null,
        webToken: null,
        operators: [{
            imageUrl: '/images/GitHub-Mark-Light-32px.png',
            username: "lazyboy",
            status: 'AWAY'
        }],
        guardians: new Array(),

        joined: false,
        viewMode: window.env.MOBILE_MODE_ENABLED ? ViewMode.MOBILE : ViewMode.DESKTOP,
        loginState: false,

        socket: null,

        /* Video & Audio */
        mjpegPlayer: new MjpegPlayer(),
        audioPlayer: null,
        videoUrl: null,
        audioUrl: null,
        videoFormat: VideoFormat.MJPEG,
        videoActive: false,

        /* Map */
        map: null,

        /* Chatting */
        messages: new Array(),
        autoScrollReady: false,
        messageView: null,
        chatView: null,
        chatMsgFrom: null,
        chatMsgInput: null,
        msgID: 0,
    },


    mounted: async function () {

        // Get data from REST API
        this.user = await this.getUser();
        this.webToken = await this.getStreamToken();
        const streamInfo = await this.getStreamInfo(this.webToken);

        this.videoFormat = streamInfo.protocol;
        this.videoUrl = streamInfo.videoUrl;
        if (this.videoFormat == VideoFormat.MJPEG)
            this.audioUrl = streamInfo.audioUrl;

        // Initialize Components
        this.initDOMrefs();
        this.initWebSocket();
        const setupChatWorker = setInterval(() => {
            if (this.socket.connected) {
                this.setupChatting();
                this.joinChatting();
                clearInterval(setupChatWorker);
            }
        }, 50);
        this.initMediaPlayer();
        this.initMap();
    },

    beforeUpdate: function () {
        // Chat auto scroll logic
        if (this.scrollBarAtBottom)
            this.autoScrollReady = true;
    },

    updated: function () {
        // Chat auto scroll logic
        if (this.autoScrollReady) {
            this.chatView.scrollTop = this.chatView.scrollHeight;
            this.autoScrollReady = false;
        }
    },

    computed: {
        scrollBarAtBottom: function () {
            return this.chatView.scrollTop + this.chatView.clientHeight >= this.chatView.scrollHeight;
        },

        desktopMode: function () {
            return this.viewMode === ViewMode.DESKTOP;
        },

        mobileMode: function () {
            return this.viewMode === ViewMode.MOBILE;
        },

        rtmpMode: function () {
            return this.videoFormat == VideoFormat.RTMP;
        },

        mjpegMode: function () {
            return this.videoFormat == VideoFormat.MJPEG;
        },
    },

    methods: {
        getUser: getUser,
        getStreamToken: getStreamToken,
        getStreamInfo: getStreamInfo,
        initDOMrefs: initDOMrefs,
        initWebSocket: initWebSocket,
        initMap: initMap,
        initMediaPlayer: initMediaPlayer,
        setupChatting: setupChatting,
        runTestMode: runTestMode,
        joinChatting: joinChatting,
        updateLocation: updateLocation,
    },
});


/* Bring-up video players */
function initMediaPlayer() {
    switch (this.videoFormat) {
        case VideoFormat.MJPEG:
            console.log("Video Format: MJPEG")
            __initHLSAudioPlayer(this);
            __initMjpegVideoPlayer(this);
            break;

        case VideoFormat.RTMP:
            console.log("Video Format: HLS");
            __initHLSVideoPlayer(this);
            break;

        default:
            break;
    }
}


/**
 * Bring-up MJPEG player
 *
 * @param {Vue} app
 */
function __initMjpegVideoPlayer(app) {
    app.mjpegView = document.getElementById("mjpegView");
    app.mjpegPlayer.setCanvas(app.mjpegView);
    app.mjpegPlayer.setSrc(app.videoUrl
        //`${window.env.STREAM_URL}:${window.env.STREAM_PORT}/stream/${streamID}/mjpeg.jpg`
    );

    app.mjpegView.addEventListener('click', () => {
        if (app.mjpegPlayer.isRunning()) {
            console.warn("Video Stopped");
            app.mjpegPlayer.stop();
            app.audioPlayer.pause();
        } else {
            console.warn("Video Started");
            app.mjpegPlayer.run();
            app.audioPlayer.play();
        }
    })
}


/**
 * Bring-up HLS Audio player
 *
 * @param {Vue} app
 */
function __initHLSAudioPlayer(app) {
    app.audioPlayer = videojs('audioPlayer');
    app.audioPlayer.src(app.audioUrl);
    document.getElementById("audioPlayer").style.display = "none";
}


/**
 * Bring-up HLS Video player
 *
 * @param {Vue} app
 */
function __initHLSVideoPlayer(app) {
    setTimeout(() => {
        let player = videojs('hlsPlayer');
        player.src(app.videoUrl);
        player.play();
    }, 500);
}


/* Bring-up Map module and bind with map view */
function initMap() {
    const mapContainer = document.getElementById('map');

    try {
        this.map = new KakaoMap(mapContainer);
    } catch (err) {
        const warning = `Could not load kakao service. Location broadcast is off`;
        console.error(`${err.name}: ${err.message}`);
        console.warn(warning);
        this.messages.push({
            id: this.msgID++,
            username: new String(),
            content: warning,
        });
    }
}


/* Connect websocket to streaming server */
function initWebSocket() {
    this.socket = io(`${window.env.STREAM_URL}:${window.env.STREAM_PORT}`);
}


/* Init references to HTML elements */
function initDOMrefs() {
    this.chatView = document.getElementById('text-area');
    this.messageView = document.getElementById('messageView');
    this.chatMsgFrom = document.getElementById('form');
    this.chatMsgInput = document.getElementById('input');
}


/* Set application in test mode.
   Map markers will just circle around randomly in test mode.  */
function runTestMode() {
    let lat = 33.450701;
    setInterval(() => {
        lat += 0.000020;
        this.map.setPosition(lat, 126.570667);
    }, 1000);
}


async function getUser() {
    return fetch('/self', {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status === 200) {
                return response.json();
            } else {
                throw "ERROR: RESPONSE CODE " + String(response.status);
            }
        })
        .then(json => {
            return Promise.resolve(new User(new UserDTO(json)));
        })
        .catch(error => {
            window.alert(error);
        });
}


async function getStreamToken() {
    if (this.viewMode == ViewMode.MOBILE) {
        return window.env.JWT_FROM_MOBILE_DEVICE;
    }


    return fetch(`/${streamID}/webtoken`, {
        method: 'get',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.env.CSRF_TOKEN
        }
    })
        .then(response => {
            if (response.status == 200) {
                return response.json();
            } else {
                throw new TokenNotFound();
            }
        })
        .then((json) => {
            return Promise.resolve(json.token);
        })
        .catch(err => {
            window.alert(`${err.name}: ${err.message}`);
            window.history.back();
            return Promise.reject();
        });
}


async function getStreamInfo(token) {
    return fetch(`${window.env.STREAM_URL}:${window.env.STREAM_PORT}/stream/${streamID}`, {
            method: 'get',
            headers: {
                'Content-Type': 'application/json',
                'webToken': token
            },
        })
            .then(response => {
                if (response.status == 200) {
                    return response.json();
                } else {
                    throw new StreamNotFound();
                }
            })
            .then(json => {
                return Promise.resolve({
                    protocol: json.protocol,
                    videoUrl: json.videoUrl,
                    audioUrl: json.audioUrl,
                });
            })
            .catch(err => {
                window.alert(`${err.name}: ${err.message}`);
                window.history.back();
                return Promise.reject();
            });
}


function joinChatting() {
    let packet = JSON.stringify({
        streamID: streamID,
        userID: this.user.id,
        username: this.user.username,
        imageUrl: this.user.imageUrl,
        webToken: this.webToken,
    });
    this.socket.emit(WebSocketHeaders.HEADER_USER_JOIN, packet);
    this.chatMsgInput.value = '';
}


function __handleSockError(res) {
    switch (res) {
        /* Todo: Turn into Exception classes */
        case WebSocketHeaders.ERR_AUTH:
            return "Auth Failed";

        case WebSocketHeaders.ERR_INVALID_TOKEN:
            return "Invalid JWT";

        case WebSocketHeaders.ERR_NOT_FOUND:
            return "Error: Not found";

        case WebSocketHeaders.ERR_NOT_GUARDIAN:
            return "You are not guardian of this user";

        default: return false;
    }
}


function setupChatting() {

    /*
        @DOM Event
        Send chatting message to server
    */
    this.chatMsgFrom.addEventListener('submit', (e) => {
        e.preventDefault();
        if (this.chatMsgInput.value) {
            let packet = JSON.stringify({
                streamID: streamID,
                userID: this.user.id,
                username: this.user.username,
                message: input.value,
                imageUrl: this.user.imageUrl,
                webToken: this.webToken,
            });
            this.socket.emit(WebSocketHeaders.HEADER_CHAT_MESSAGE, packet);
            this.chatMsgInput.value = '';
        }
    });


    /*
        @Websocket Listener
        New user joined chat
    */
    this.socket.on(WebSocketHeaders.HEADER_USER_JOIN, (recv) => {
        if (__handleSockError(recv)) {
            this.messages.push({
                id: this.msgID++,
                username: new String(),
                content: recv
            });
            return;
        }

        let packet = JSON.parse(recv);
        this.joined = true;

        this.messages.push({
            id: this.msgID++,
            username: packet.username,
            content: `${packet.username} Joined chat`
        });
    });


    /*
        @Websocket Listener
        New incoming chat message
    */
    this.socket.on(WebSocketHeaders.HEADER_CHAT_MESSAGE, (recv) => {
        if (__handleSockError(recv)) {
            this.messages.push({
                id: this.msgID++,
                username: new String(),
                content: recv
            });
            return;
        }

        let packet = JSON.parse(recv);
        this.messages.push({
            id: this.msgID++,
            username: packet.username,
            content: packet.message
        });
    });


    /*
        @Websocket Listener
        User Disconnected
    */
    this.socket.on(WebSocketHeaders.HEADER_DISCONNECTION, (recv) => {
        if (!this.joined)
            return;
    });


    /*
        @Websocket Listener
        Updated list of active chatting users.
    */
    this.socket.on(WebSocketHeaders.HEADER_USER_LIST, (recv) => {
        const packet = JSON.parse(recv);
        this.guardians = packet;
    });
}


function updateLocation() {
    fetch(`${window.env.STREAM_URL}:${window.env.STREAM_PORT}/stream/${streamID}/geo`, {
        method: 'get'
    })
        .then((res) => {
            if (res.status != 200) {
                let err = new NotFound();
                err.setMessage("Location data request returned 404");
                throw err;
            }
            else
                return res.json();
        })
        .then((data) => {
            this.map.setPosition(data.latitude, data, longitude);
        })
        .catch((err) => {
            console.warn(`${err.name}:${err.message}`);
        });
}
