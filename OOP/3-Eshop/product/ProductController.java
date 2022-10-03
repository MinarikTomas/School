package sk.stuba.fei.uim.oop.assignment3.product;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;
import java.util.stream.Collectors;

@RestController
@RequestMapping("/product")
public class ProductController {

    @Autowired
    private IProductService service;

    @GetMapping()
    public List<ProductResponse> getAllProducts(){
        return this.service.getAll().stream().map(ProductResponse::new).collect(Collectors.toList());
    }

    @PostMapping()
    public ResponseEntity<ProductResponse> addProduct(@RequestBody ProductRequest request){
        return new ResponseEntity<>(new ProductResponse(this.service.create(request)), HttpStatus.CREATED);
    }

    @GetMapping("/{id}")
    public ProductResponse getProductById(@PathVariable("id") Long id){
        return new ProductResponse(this.service.getById(id));
    }

    @PutMapping("/{id}")
    public ProductResponse updateProduct(@PathVariable("id") Long id, @RequestBody ProductRequest request ){
        return new ProductResponse(this.service.updateById(id, request));
    }

    @DeleteMapping("/{id}")
    public void deleteProduct(@PathVariable("id") Long id){
        this.service.deleteById(id);
    }

    @GetMapping("/{id}/amount")
    public ProductAmountResponse getProductAmount(@PathVariable("id") Long id){
        return new ProductAmountResponse(this.service.getById(id));
    }

    @PostMapping("/{id}/amount")
    public ProductAmountResponse addProductAmount(@PathVariable("id") Long id, @RequestBody ProductRequest request){
        return new ProductAmountResponse(this.service.addAmount(id, request));
    }

}
