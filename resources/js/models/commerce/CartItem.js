class CartItem{
    productID;
    title;
    imgUrl;
    price;
    unit;
    quantity;

    constructor(DTO){
        this.productID = DTO.productID;
        this.title = DTO.title;
        this.imgUrl = DTO.imgUrl;
        this.price = DTO.price;
        this.unit = DTO.unit;
        this.quantity = DTO.quantity;
    }
}

export default CartItem;
