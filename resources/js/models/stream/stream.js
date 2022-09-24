class Stream{

    videoUrl;
    audioUrl;
    videoFormat;

    static VideoFormats = Object.freeze({
        MJPEG: "MJPEG",
        RTMP: "RTMP"
    });

    static ViewModes = Object.freeze({
        DESKTOP: "Desktop",
        MOBILE: "Mobile",
    });


    constructor(DTO){
        this.videoUrl = DTO.videoUrl;
        this.audioUrl = DTO.audioUrl;
        this.videoFormat = DTO.protocol;
    }
}

export default Stream;
