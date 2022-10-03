package sk.stuba.fei.uim.oop;

import java.util.ArrayList;
import java.util.Random;

public class Player {
    private String name;
    private int money;
    private int position;
    private int roundsInPrison;

    public Player(String name) {
        this.name = name;
        this.money = 1300;
        this.position = 0;
        this.roundsInPrison = 0;
    }

    public String getName() {
        return name;
    }

    public int getPosition() {
        return position;
    }

    public int getMoney() {
        return money;
    }

    private int diceThrow(){
        Random rand = new Random();
        return rand.nextInt(6) + 1;
    }

    public void play(){
            int dice = diceThrow();
            System.out.println(this.name + " hadze kockou: " + dice);
            System.out.println("Ucet: " + this.money);
            int newPosition = (this.position + dice) % 24;
            if (crossedStart(newPosition)) {
                System.out.println("Presiel si cez start dostavas 100");
                incrementMoney(100);
            }
            move(newPosition);
    }

    public void decreasePrisonSentence(){
        roundsInPrison--;
    }

    public boolean crossedStart(int newPosition){
        return (newPosition != 0) && (newPosition - this.position < 0);
    }

    private void move(int newPosition){
        this.position = newPosition;
    }

    public void incrementMoney(int money){
        this.money += money;
    }

    public void decrementMoney(int money){this.money -= money;}

    public boolean hasEnoughMoney(int payment){
        return this.money >= payment;
    }

    public void goToPrison(){
        this.roundsInPrison = 2;
        this.position = 6;
    }

    public boolean isInPrison(){
        return roundsInPrison > 0;
    }

    public void goTo(int position){
        this.position = position;
    }

}