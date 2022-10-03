package sk.stuba.fei.uim.oop.assignment3.product;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;


@Service
public class ProductService implements IProductService{

    @Autowired
    private ProductRepository repository;

    @Override
    public List<Product> getAll(){
        return this.repository.findAll();
    }

    @Override
    public Product create(ProductRequest request) {
        Product newProduct = new Product();
        newProduct.setAmount(request.getAmount());
        newProduct.setDescription(request.getDescription());
        newProduct.setPrice(request.getPrice());
        newProduct.setName(request.getName());
        newProduct.setUnit(request.getUnit());
        return this.repository.save(newProduct);
    }

    @Override
    public Product getById(Long id) {
        return this.repository.findById(id).orElseThrow();
    }

    @Override
    public Product updateById(Long id, ProductRequest request) {
        Product product = this.repository.findById(id).orElseThrow();
        return this.repository.save(changeNameAndDescription(product, request.getName(), request.getDescription()));
    }

    private Product changeNameAndDescription(Product product, String name, String description){
        if(name != null){
            product.setName(name);
        }
        if(description != null){
            product.setDescription(description);
        }
        return product;
    }

    @Override
    public void deleteById(Long id){
        Product product = this.repository.findById(id).orElseThrow();
        this.repository.delete(product);
    }

    @Override
    public Product addAmount(Long id, ProductRequest request) {
        Product product = this.repository.findById(id).orElseThrow();
        product.setAmount(product.getAmount() + request.getAmount());
        return this.repository.save(product);
    }

    @Override
    public Product save(Product p) {
        return this.repository.save(p);
    }
}
