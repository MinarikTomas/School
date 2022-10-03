package sk.stuba.fei.uim.oop.assignment3.cart;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import sk.stuba.fei.uim.oop.assignment3.exceptions.CartIsAlreadyPaidException;
import sk.stuba.fei.uim.oop.assignment3.exceptions.NotEnoughProductException;
import sk.stuba.fei.uim.oop.assignment3.order.ShopOrder;
import sk.stuba.fei.uim.oop.assignment3.order.ShopOrderRepository;
import sk.stuba.fei.uim.oop.assignment3.product.IProductService;
import sk.stuba.fei.uim.oop.assignment3.product.Product;

@Service
public class CartService implements ICartService{
    @Autowired
    private CartRepository repository;
    @Autowired
    private IProductService productService;
    @Autowired
    private ShopOrderRepository shopOrderRepository;

    @Override
    public Cart create() {
        return this.repository.save(new Cart());
    }

    @Override
    public Cart getById(Long id) {
        return this.repository.findById(id).orElseThrow();
    }

    @Override
    public void deleteById(Long id) {
        Cart cart = this.repository.findById(id).orElseThrow();
        this.repository.delete(cart);
    }

    @Override
    public Cart addProduct(Long id, CartAddRequest request) {
        Cart cart = this.repository.findById(id).orElseThrow();
        throwExceptionIfAlreadyPaid(cart);
        Product product = this.productService.getById(request.getProductId());
        throwExceptionIfNotEnoughProduct(request.getAmount(), product);
        if(orderAlreadyExists(cart, request.getProductId())){
            addToExistingOrder(cart, product, request.getAmount());
        }else {
            addNewOrder(cart, product, request.getAmount());
        }
        updateProductAmount(product, request.getAmount());
        return this.repository.save(cart);
    }

    private void updateProductAmount(Product product, int amount){
        product.setAmount(product.getAmount() - amount);
        this.productService.save(product);
    }

    private void addNewOrder(Cart cart, Product product, int amount){
        ShopOrder shopOrder = new ShopOrder(cart, product, amount);
        shopOrder = this.shopOrderRepository.save(shopOrder);
        cart.getShopOrders().add(shopOrder);
        product.getShopOrders().add(shopOrder);
    }

    private void addToExistingOrder(Cart cart, Product product, int amount){
        for(ShopOrder order: cart.getShopOrders()){
            if(order.getProduct().getId().equals(product.getId())){
                order.setAmount(order.getAmount() + amount);
                this.shopOrderRepository.save(order);
            }
        }
    }

    private void throwExceptionIfNotEnoughProduct(int amount, Product product){
        if(amount > product.getAmount()){
            throw new NotEnoughProductException();
        }
    }

    private boolean orderAlreadyExists(Cart cart, Long productId){
        for(ShopOrder order: cart.getShopOrders()){
            if(order.getProduct().getId().equals(productId)){
                return true;
            }
        }
        return false;
    }

    @Override
    public double pay(Long id) {
        Cart cart = this.repository.findById(id).orElseThrow();
        throwExceptionIfAlreadyPaid(cart);
        cart.setPayed(true);
        this.repository.save(cart);
        return getCartPrice(cart);
    }

    private double getCartPrice(Cart cart){
        double price = 0.0;
        for(ShopOrder shopOrder : cart.getShopOrders()){
            price += shopOrder.getAmount()* shopOrder.getProduct().getPrice();
        }
        return price;
    }

    private void throwExceptionIfAlreadyPaid(Cart cart){
        if(cart.isPayed()){
            throw new CartIsAlreadyPaidException();
        }
    }
}
