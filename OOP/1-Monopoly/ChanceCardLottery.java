package sk.stuba.fei.uim.oop;

public class ChanceCardLottery extends ChanceCard{
    @Override
    public void executeCard(Player player) {
        System.out.println("Karta: Vyhral si v loterii 50");
        player.incrementMoney(50);
    }
}
