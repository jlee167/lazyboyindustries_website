import ModelMismatch from '../../exceptions/generic/ModelMismatch';


/* Base class for DTOs */
class DataTransferModel {

    constructor(){};

    /**
     * Throw error if the client-side model is not identical
     * to server-side model
     *
     * @param {*} clientModel   - Client side model
     * @param {*} serverModel   - Server side model
     */
    verifyDTO(clientModel, serverModel){
        for (const[key, value] of Object.entries(serverModel)) {
            if (clientModel.hasOwnProperty(key))
                clientModel[key] = value;
            else
                throw new ModelMismatch(key);
        }
    }
}
export default DataTransferModel;
