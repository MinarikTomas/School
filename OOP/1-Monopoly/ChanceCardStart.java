package sk.stuba.fei.uim.oop;

public class ChanceCardStart extends ChanceCard{
    @Override
    public void executeCard(Player player) {
        System.out.println("Karta: Chod na start dostanes 100");
        player.incrementMoney(100);
        player.goTo(0);
    }
}
