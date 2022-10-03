package sk.stuba.fei.uim.oop;

public class ChanceCardKentuckyAvenue extends ChanceCard{
    @Override
    public void executeCard(Player player) {
        System.out.println("Karta: Chod na Kentucky Avanue ak prejdes cez start dostanes 100");
        if(player.crossedStart(13)){
            player.incrementMoney(100);
        }
        player.goTo(13);
    }
}
