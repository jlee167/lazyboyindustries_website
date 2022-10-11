import DataTransferModel from "../../libs/model/dto-base";

class CartItemDTO extends DataTransferModel {

    id;
    uid;
    product_id;
    title;
    img_url;
    price_credits;
    quantity;
    last_updated;
    description;
    active;

    constructor(json) {
        super();
        super.verifyDTO(this, json);
    }
}

export default CartItemDTO;
