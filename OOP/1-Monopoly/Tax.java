package sk.stuba.fei.uim.oop;

public class Tax extends Field{

    private int tax;

    public Tax(int tax) {
        this.tax = tax;
        this.name = "Tax";
    }

    public int getTax() {
        return tax;
    }

    @Override
    public boolean execute(Player player) {
        System.out.println(toString());
        System.out.println("Musis zaplatit: " + this.tax);
        if(player.hasEnoughMoney(this.tax)){
            player.decrementMoney(this.tax);
        }else{
            System.out.println("Nemas dostatok penazi na zaplatenie dane");
            return false;
        }
        return true;
    }
}
