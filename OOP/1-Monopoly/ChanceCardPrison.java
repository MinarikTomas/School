package sk.stuba.fei.uim.oop;

public class ChanceCardPrison extends ChanceCard{

    @Override
    public void executeCard(Player player) {
        System.out.println("Karta: Ides do vazenia na 2 kola");
        player.goToPrison();
    }
}
