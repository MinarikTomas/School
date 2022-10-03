package sk.stuba.fei.uim.oop.assignment3.order;

import lombok.Getter;

@Getter
public class ShopOrderResponse {
    private Long productId;
    private int amount;

    public ShopOrderResponse(ShopOrder o){
        this.amount = o.getAmount();
        this.productId = o.getProduct().getId();
    }
}
