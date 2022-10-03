package sk.stuba.fei.uim.oop;

public abstract class Field {

    protected String name;

    public abstract boolean execute(Player player);

    @Override
    public String toString() {
        return "Nachadzas sa na policku " + name;
    }
}
