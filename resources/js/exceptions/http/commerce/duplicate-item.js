/* Error thrown when product is already in cart */
class DuplicateItem extends Error {
    constructor() {
        super();
        this.name = "Duplicate Item";
        this.message = "Item is already in cart";
    }
};

export default DuplicateItem;
