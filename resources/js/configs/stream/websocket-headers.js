
/*  WebSocket headers for comm between the browser
    and streaming server */
const Chat = {
    HEADER_CONNECTION: 'connection',
    HEADER_DISCONNECTION: 'disconnect',
    HEADER_CURRENT_USERS: "current users",
    HEADER_RESPONSE: "response",
    HEADER_CHAT_MESSAGE: 'chat message',
    HEADER_USER_JOIN: "user joined",
    HEADER_USER_LEFT: "user left",
    HEADER_GEO_DATA: 'GEO',
    HEADER_CLIENT_JWT: "CLIENT_JWT",
    HEADER_IMAGE_DATA: 'IMAGE_JPEG',
    HEADER_VIDEO_FORMAT: 'VIDEO_FORMAT',
    HEADER_USER_LIST: 'USER_LIST',
    HEADER_AUDIO_DATA: 'IMAGE_JPEG',

    ERR_INVALID_TOKEN: "INVALID_TOKEN",
    ERR_AUTH: "ERR_AUTH",
    ERR_NOT_GUARDIAN: "ERR_NOT_GUARDIAN",
    ERR_NOT_FOUND: "ERR_NOT_FOUND",
    ERR_TIMEOUT: "ERR_TIMEOUT",

    PRIVATE_KEY: "PRIVATEKEY",
    ID_TEST_CHANNEL: "TEST",
};

export default Chat;
