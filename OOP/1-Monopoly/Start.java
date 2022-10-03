package sk.stuba.fei.uim.oop;

public class Start extends Field{

    public Start() {
        this.name = "Start";
    }
    @Override
    public boolean execute(Player player) {
        System.out.println(toString());
        System.out.println("Dostavas 100");
        player.incrementMoney(100);
        return true;
    }
}
