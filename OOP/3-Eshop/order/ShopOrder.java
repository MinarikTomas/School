package sk.stuba.fei.uim.oop.assignment3.order;

import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import sk.stuba.fei.uim.oop.assignment3.cart.Cart;
import sk.stuba.fei.uim.oop.assignment3.product.Product;

import javax.persistence.*;

@Entity
@Getter
@Setter
@NoArgsConstructor
public class ShopOrder {
    @Id
    @GeneratedValue(strategy = GenerationType.AUTO)
    private Long id;

    @ManyToOne
    private Product product;

    @ManyToOne
    private Cart cart;

    private int amount;

    public ShopOrder(Cart cart, Product product, int amount){
        this.amount = amount;
        this.product = product;
        this.cart = cart;
    }

}
