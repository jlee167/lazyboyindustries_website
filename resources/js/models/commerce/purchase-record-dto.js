import DataTransferModel from '../../libs/model/dto-base';

class PurchaseRecordDTO extends DataTransferModel{

    id;
    uid;
    title;
    transaction_id;
    product_id;
    quantity;
    unit_price;
    date;
    authorized;
    rating_timestamp;
    credits_expended;
    stock_after_transaction;
    value;
    comment;

    constructor(initData){
        super();
        super.verifyDTO(this, initData);
    }
}


export default PurchaseRecordDTO;
