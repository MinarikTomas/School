package sk.stuba.fei.uim.oop;

import java.util.ArrayList;
import java.util.Scanner;

public class Game {
    private final ArrayList<Player> players;
    private int numberOfPlayers;
    private final Board board;

    public Game(){
        this.players = new ArrayList<>();
        this.board = new Board();
        setup();
        play();
    }

    private void setNumberOfPlayers(){
        while(!isInRange()){
            intInput();
        }
    }

    private void  intInput(){
        Scanner sc = new Scanner(System.in);
        System.out.print("Zadaj pocet hracov(2-6): ");
        try{numberOfPlayers = sc.nextInt();}
        catch(java.util.InputMismatchException e){
            System.out.println("Zly vstup");
            sc.nextLine();
        }
    }

    private boolean isInRange(){
        return numberOfPlayers <= 6 && numberOfPlayers >= 2;
    }

    public void setPlayers(){
        Scanner sc = new Scanner(System.in);
        for(int i = 0; i < numberOfPlayers;i++){
            System.out.format("Hrac cislo %d: ",i+1);
            Player player = new Player(sc.nextLine());
            players.add(player);
        }
    }

    private void setup(){
        setNumberOfPlayers();
        setPlayers();
    }

    private void play(){
        while(!isGameEnded()){
            playRound();
        }
        System.out.println("\033[0;31m" + "Vyhrava hrac " + players.get(0).getName());
        System.out.println("Zostatok na ucte: " + players.get(0).getMoney() + "\033[0m");
    }

    private boolean isGameEnded(){
        return numberOfPlayers == 1;
    }

    private void playRound(){
        for(int i = 0; i < numberOfPlayers; i++){
            onePlayerTurn(players.get(i));
        }
    }

    private void onePlayerTurn(Player player){
        if(player.isInPrison()){
            System.out.println(player.getName());
            System.out.println("Si vo vazeni");
            player.decreasePrisonSentence();
        }else {
            player.play();
            if (board.board[player.getPosition()].execute(player)) {
                System.out.println("Zostatok na ucte: " + player.getMoney());
            } else {
                System.out.println("\033[0;31m" + "Hra pre teba konci" + "\033[0m");
                removePlayer(player);
                System.out.println("Zostavajuci hraci " + toStringPlayers());
            }
        }
        System.out.println("---------------------------------------");
    }

    private void removePlayer(Player player){
        numberOfPlayers--;
        players.remove(player);
        board.removePlayersProperties(player);
    }

    private String toStringPlayers() {
        StringBuilder sb = new StringBuilder();
        for (Player player : players) {
            sb.append(player.getName());
            sb.append(" ");
        }
        return sb.toString();
    }
}
