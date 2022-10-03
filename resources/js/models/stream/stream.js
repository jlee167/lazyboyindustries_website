class Stream{

    videoUrl;
    audioUrl;
    geoLocationUrl;
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
        this.geoLocationUrl = DTO.geoLocationUrl;
        this.videoFormat = DTO.protocol;
    }
}

export default Stream;
