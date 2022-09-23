/* Authentication failure on WebSocket channel */
class SocketAuthFail extends Error {
    constructor() {
        super();
        this.name = "SocketAuthFail";
        this.message = "Websocket Authentication Failed!";
    }
};

export default SocketAuthFail;
