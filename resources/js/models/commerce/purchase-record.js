import ProductReview from "./product-review";

class PurchaseRecord{

    constructor(DTO) {
        this.id = DTO.id;
        this.title = DTO.title;
        this.unitPrice = DTO.unit_price;
        this.quantity = DTO.quantity;
        this.expense = DTO.credits_expended;
        this.transactionID = DTO.transaction_id;
        this.reviewed = DTO.value != null;
        this.review = new ProductReview({
            purchaseID: DTO.id,
            value: DTO.value,
            comment: DTO.comment
        });
        this.date = DTO.date;
        this.reviewActive = false;
    };
}


export default PurchaseRecord;
