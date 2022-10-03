package sk.stuba.fei.uim.oop;

public class ChanceCardBirthday extends ChanceCard{
    @Override
    public void executeCard(Player player) {
        System.out.println("Karta: Mas narodeniny od banky dostanes 25");
        player.incrementMoney(25);
    }
}
