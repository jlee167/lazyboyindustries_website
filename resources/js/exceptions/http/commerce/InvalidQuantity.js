/* Exception thrown when amount being put into cart is 0 or less */
class InvalidQuantity extends Error {
    constructor() {
        super();
        this.name = "Invalid Quantity";
        this.message = "Quantity should be and integer of 1 or more";
    }
};

export default InvalidQuantity;
