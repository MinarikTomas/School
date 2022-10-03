package sk.stuba.fei.uim.oop.assignment3.cart;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@RestController()
@RequestMapping("/cart")
public class CartController {
    @Autowired
    private ICartService service;

    @ResponseStatus(HttpStatus.CREATED)
    @PostMapping()
    public ResponseEntity<CartResponse> createCart(){
        return new ResponseEntity<>(new CartResponse(this.service.create()), HttpStatus.CREATED);
    }

    @GetMapping("/{id}")
    public CartResponse getCartById(@PathVariable("id") Long id){
        return new CartResponse(this.service.getById(id));
    }

    @DeleteMapping("/{id}")
    public void deleteCart(@PathVariable("id") Long id){
        this.service.deleteById(id);
    }

    @PostMapping("/{id}/add")
    public CartResponse addProductToCart(@PathVariable("id") Long id, @RequestBody CartAddRequest request){
        return new CartResponse(this.service.addProduct(id, request));
    }

    @GetMapping("/{id}/pay")
    public String payCart(@PathVariable("id") Long id){
        return String.valueOf(this.service.pay(id));
    }
}
