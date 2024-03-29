import DataTransferModel from '../../libs/model/dto-base';

class UserDTO extends DataTransferModel {

    id;
    username;
    email;
    stream_key;
    status;
    is2FAenabled;
    auth_provider;
    image_url;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}

export default UserDTO;
