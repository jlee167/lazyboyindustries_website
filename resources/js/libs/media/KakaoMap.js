
/* Exception thrown when Kakao Map cannot be imported */
export class KakaoImportError extends Error {
    constructor() {
        this.name =  "Kakao Import Error";
        this.message = "Kakamo Map could not be imported.";
    }
}


/* Kakao Map manager class */
export class KakaoMap {

    static defaultLat = 33.450701;
    static defaultLong = 126.570667;

    constructor(container) {
        if (kakao == undefined) {throw new KakaoImportError()};

        this.latitude = KakaoMap.defaultLat;
        this.longitude = KakaoMap.defaultLong;

        this.position = new kakao.maps.LatLng(this.latitude, this.longitude);
        let options = {
            center: this.position,
            level: 3
        };
        this.map = new kakao.maps.Map(container, options);
        this.marker = new kakao.maps.Marker({
            position: this.position
        });
        this.marker.setMap(this.map);
    }


    /**
     * Returns current position of the target
     *
     * @returns {Number, Number}
     */
    getPosition() {
        return {
            latitude: this.latitude,
            longitude: this.longitude
        }
    };


    /**
     * Set marker on the map.
     *
     * @param {Number} latitude
     * @param {Number} longitude
     */
    setPosition(latitude, longitude) {
        this.latitude = latitude;
        this.longitude = longitude;
        this.position = new kakao.maps.LatLng(this.latitude, this.longitude);
        this.marker.setPosition(this.position);
        this.map.setCenter(this.position);
    }
};

