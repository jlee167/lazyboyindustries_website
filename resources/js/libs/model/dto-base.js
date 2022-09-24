import ModelMismatch from '../../exceptions/generic/model-mismatch';


/* Base class for DTOs */
class DataTransferModel {

    constructor(){};

    /**
     * Throw error if the client-side model is not identical
     * to server-side model
     *
     * @param {*} clientModel
     * @param {*} transferedObject
     */
    verifyDTO(clientModel, transferedObject){
        for (const[key, value] of Object.entries(transferedObject)) {
            if (clientModel.hasOwnProperty(key))
                clientModel[key] = value;
            else
                throw new ModelMismatch(key);
        }
    }
}
export default DataTransferModel;
