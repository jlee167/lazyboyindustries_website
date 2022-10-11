class CartItem{
    productID;
    title;
    imgUrl;
    price;
    quantity;

    constructor(DTO){
        this.productID = DTO.product_id;
        this.title = DTO.title;
        this.imgUrl = DTO.img_url;
        this.price = DTO.price_credits;
        this.quantity = DTO.quantity;
    }
}


export default CartItem;
