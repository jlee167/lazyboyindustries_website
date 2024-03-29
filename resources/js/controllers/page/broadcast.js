import MjpegPlayer from "../../utils/media/mjpeg-player.js";
import WebSocketHeaders from "../../configs/stream/websocket-headers.js";
import { KakaoMap } from "../../utils/media/kakao-map.js";
import User from '../../models/user/user';
import UserDTO from '../../models/user/user-dto';
import Stream from "../../models/stream/stream.js";
import StreamDTO from "../../models/stream/stream-dto.js";
import { getLocation, getStream, getToken } from "../../data/net/http/stream/stream.js";
import { getSelfProfile } from "../../data/net/http/user/user.js";


videojs.Hls.xhr.beforeRequest = function (options) {
    //options.header = { token:"token-string" };
    //options.uri = options.uri + "?token=token-string";
    //return options;
}


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
        guardians: new Array(),

        joined: false,
        viewMode: null,
        loginState: false,

        socket: null,

        /* Video & Audio */
        mjpegPlayer: new MjpegPlayer(),
        audioPlayer: null,
        stream: null,

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

        /* Worker Handles */
        locationFetcher: null,
    },

    created: function () {
        this.viewMode = window.env.MOBILE_MODE_ENABLED ?
                            Stream.ViewModes.MOBILE : Stream.ViewModes.DESKTOP;
    },

    mounted: async function () {

        // Get data from REST API
        this.user = await this.getUser();
        this.webToken = await this.getStreamToken();
        this.stream = await this.getStreamInfo(this.webToken);
        console.log(this.stream);

        // Initialize Components
        this.initDOMrefs();
        this.$nextTick(() => {
            this.initMediaPlayer();
            this.initMap();
        });

        //Setup Websocket
        this.initWebSocket();
        const chatSetupWorker = setInterval(() => {
            if (this.socket.connected) {
                this.addChatListeners();
                this.joinChatting();
                clearInterval(chatSetupWorker);
            }
        }, 50);

        this.startLocationFetcher();
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
            return this.viewMode === Stream.ViewModes.DESKTOP;
        },

        mobileMode: function () {
            return this.viewMode === Stream.ViewModes.MOBILE;
        },
        rtmpMode: function () {
            return this.stream?.videoFormat == Stream.VideoFormats.RTMP;
        },

        mjpegMode: function () {
            return this.stream?.videoFormat == Stream.VideoFormats.MJPEG;
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
        addChatListeners: addChatListeners,
        runTestMode: runTestMode,
        joinChatting: joinChatting,
        updateLocation: updateLocation,
        startLocationFetcher: startLocationFetcher,
        stopLocationFetcher: stopLocationFetcher,
    },
});


/* Bring-up video players */
function initMediaPlayer() {
    switch (this.stream.videoFormat) {
        case Stream.VideoFormats.MJPEG:
            console.log("Video Format: MJPEG")
            __initHLSAudioPlayer(this);
            __initMjpegVideoPlayer(this);
            break;

        case Stream.VideoFormats.RTMP:
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
    app.mjpegPlayer.setSrc(app.stream.videoUrl);

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
    app.audioPlayer.src(app.stream.audioUrl);
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
        player.src(app.stream.videoUrl);
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
    return await getSelfProfile()
        .then(json => {
            return Promise.resolve(new User(new UserDTO(json)));
        })
        .catch(error => {
            window.alert(error);
        });
}


async function getStreamToken() {
    if (this.viewMode == Stream.ViewModes.MOBILE) {
        return window.env.JWT_FROM_MOBILE_DEVICE;
    }


    return await getToken(streamID)
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
    return await getStream(
        window.env.STREAM_URL,
        window.env.STREAM_PORT,
        streamID,
        token,
    )
        .then(json => {
            const stream = new Stream(new StreamDTO(json));
            return Promise.resolve(stream);
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
    this.chatMsgInput.value = new String();
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


function addChatListeners() {

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
            this.chatMsgInput.value = new String();
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
    getLocation(
        window.env.STREAM_URL,
        window.env.STREAM_PORT,
        streamID,
        this.webToken
    )
        .then((data) => {
            console.log(data);
            this.map.setPosition(data.latitude, data.longitude);
        })
        .catch((err) => {
            console.warn(`${err.name}:${err.message}`);
        });
}


function startLocationFetcher() {
    this.locationFetcher = setInterval(() => {
        this.updateLocation();
    }, 5000);
}


function stopLocationFetcher() {
    clearInterval(this.locationFetcher);
    this.locationFetcher = null;
}


