package sk.stuba.fei.uim.oop.assignment3.product;

import java.util.List;

public interface IProductService {
    List<Product> getAll();
    Product create(ProductRequest request);
    Product getById(Long id);
    Product updateById(Long id, ProductRequest request);
    void deleteById(Long id);
    Product addAmount(Long id, ProductRequest request);
    Product save(Product p);
}
