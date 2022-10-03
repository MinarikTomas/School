package sk.stuba.fei.uim.oop;

public class Prison extends Field{

    public Prison(){
        this.name = "Prison";
    }

    @Override
    public boolean execute(Player player) {
        System.out.println(toString());
        System.out.println("Si len na navsteve");
        return true;
    }
}
