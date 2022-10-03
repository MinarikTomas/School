package sk.stuba.fei.uim.oop.assignment3.cart;

import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
public class CartAddRequest {
    private Long productId;
    private int amount;
}
