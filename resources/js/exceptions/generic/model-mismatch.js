/* Exception class thrown when DTO and model does not match */
class ModelMismatch extends Error {
    constructor(missingKey) {
        super();
        this.name = "Model Mismatch Exception";
        this.message = `Server-client model mismatch! Client side model does not have Key ${missingKey}`;
    }
};

export default ModelMismatch;
