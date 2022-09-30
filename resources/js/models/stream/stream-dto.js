import DataTransferModel from '../../libs/model/dto-base';

class StreamDTO extends DataTransferModel {

    videoUrl;
    audioUrl;
    geoLocationUrl;
    protocol;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}

export default StreamDTO;
