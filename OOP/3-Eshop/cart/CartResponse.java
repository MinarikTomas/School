package sk.stuba.fei.uim.oop.assignment3.cart;

import lombok.Getter;
import sk.stuba.fei.uim.oop.assignment3.order.ShopOrderResponse;

import java.util.List;
import java.util.stream.Collectors;

@Getter
public class CartResponse {
    private Long id;
    private List<ShopOrderResponse> shoppingList;
    private boolean payed;

    public CartResponse(Cart c){
        this.id = c.getId();
        this.shoppingList = c.getShopOrders().stream().map(ShopOrderResponse::new).collect(Collectors.toList());
        this.payed = c.isPayed();
    }
}
