/* Exception thrown when stock level is lower then requested amount */
class InsufficientStock extends Error {
    constructor() {
        super();
        this.name = "Insufficient stock";
        this.message = "Please check our stock level first";
    }
};

export default InsufficientStock;
