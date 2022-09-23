import DataTransferModel from "../base/DataTransferModel";

class CartItemDTO extends DataTransferModel{

    productID;
    title;
    imgUrl;
    price;
    unit;
    quantity;

    constructor(json){
        super();
        super.verifyDTO(this, json);
    }
}

export default CartItemDTO;
