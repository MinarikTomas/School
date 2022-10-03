package sk.stuba.fei.uim.oop;

import java.util.ArrayList;
import java.util.Collections;

public class Chance extends Field{

    private ArrayList<ChanceCard> chanceDeck;
    private int currentTopCard;

    public Chance(){
        chanceDeck = new ArrayList<>();
        currentTopCard = 0;
        this.name = "Chance";
        createDeck();
        shuffleDeck();
    }

    private void createDeck(){
        chanceDeck.add(new ChanceCardBirthday());
        chanceDeck.add(new ChanceCardStart());
        chanceDeck.add(new ChanceCardPrison());
        chanceDeck.add(new ChanceCardLottery());
        chanceDeck.add(new ChanceCardKentuckyAvenue());
    }

    private void shuffleDeck(){
        Collections.shuffle(chanceDeck);
    }

    public boolean execute(Player player){
        System.out.println(toString());
        chanceDeck.get(currentTopCard).executeCard(player);
        moveCard();
        return true;
    }

    private void moveCard(){
        currentTopCard++;
        if(currentTopCard > 4){
            shuffleDeck();
            currentTopCard = 0;
        }
    }
}
