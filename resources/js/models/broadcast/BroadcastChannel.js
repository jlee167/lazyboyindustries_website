import DataTransferModel from "../base/DataTransferModel";

/* Broadcast stream metadata container class */
class BroadcastChannel extends DataTransferModel{

    #jwt;
    guardians = new Array();

    constructor(){
        super();
    }

    setJWT(jwt) {
        this.jwt = jwt;
    }


    get jwt() {
        return this.#jwt;
    }
}

export default BroadcastChannel;
