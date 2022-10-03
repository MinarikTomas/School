package sk.stuba.fei.uim.oop.assignment3.order;

import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ShopOrderRepository extends CrudRepository<ShopOrder, Long> {
}
