package sk.stuba.fei.uim.oop;

import java.util.Scanner;

public class Property extends Field{
    private final int price;
    private final int rent;
    private Player owner;
    private  final String colorSet;

    public Property(int price, int rent, String colorSet, String name){
        this.price = price;
        this.rent = rent;
        this.colorSet = colorSet;
        this.name = name;
    }

    public boolean isOwned(){
        return owner != null;
    }

    public boolean isOwner(Player player){
        return player == owner;
    }

    @Override
    public String toString() {
        return super.toString() + "(Set: " + this.colorSet + ")";
    }

    @Override
    public boolean execute(Player player) {
        System.out.println(toString());
       if(!isOwned()){
           doesNotHaveAnOwner(player);
       }else{
           if(isOwner(player)){
               System.out.println("Vlastnis tuto nehnutelnost");
           }else{
               printDoesHaveAnOwner(player);
               if(player.hasEnoughMoney(this.rent)){
                   payRent(player);
               }else{
                   System.out.println("Nemas dostatok penazi na zaplatenie najmu");
                   return false;
               }
           }
       }
        return true;
    }

    private void doesNotHaveAnOwner(Player player){
        printDoesNotHaveAnOwner();
        if(player.hasEnoughMoney(this.price)){
            System.out.println("ano=1/nie=0");
            if(wantsToBuy()){
                this.owner = player;
                System.out.println("Kupil si " + this.name);
                player.decrementMoney(this.price);
            }
        }else{
            System.out.println("Nemas dostatok penazi");
        }
    }

    private void printDoesNotHaveAnOwner(){
        System.out.println("Tuto nehnutelnost este nikto nevlastni");
        System.out.println("Chces kupit tuto nehnutelnost za " + this.price);
    }

    private void payRent(Player player){
        player.decrementMoney(this.rent);
        owner.incrementMoney(this.rent);
    }

    private void printDoesHaveAnOwner(Player player){
        System.out.println("Tuto nehnutelnost vlastni " + this.owner.getName());
        System.out.println("Musis zaplatit: " + this.rent);
    }

    private boolean wantsToBuy(){
        int input = intInput();
        return input == 1;
    }

    private int  intInput(){
        int input = 2;
        Scanner sc = new Scanner(System.in);
        while((input != 1)&&(input !=0)) {
            try {
                input = sc.nextInt();
            } catch (java.util.InputMismatchException e) {
                System.out.println("Zly vstup, zadaj 1 alebo 0");
                sc.nextLine();
            }
        }
        return input;
    }

    public void removeOwner(){
        this.owner = null;
    }

}
