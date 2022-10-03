package sk.stuba.fei.uim.oop;

public class Police extends Field{

    public Police(){
        this.name = "Police";
    }

    @Override
    public boolean execute(Player player) {
        System.out.println(toString());
        System.out.println("Ides do vazenia na 2 kola");
        player.goToPrison();
        return true;
    }
}
