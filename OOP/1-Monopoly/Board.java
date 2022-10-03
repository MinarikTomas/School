package sk.stuba.fei.uim.oop;

public class Board {

    protected Field[] board;

    public Board(){
        board = new Field[24];
        createBoard();
    }

    private void createBoard(){
        addProperties();
        addCorners();
        addChance();
    }

    private void addCorners(){
        board[0] = new Start();
        board[6] = new Prison();
        board[12] = new Tax(300);
        board[18] = new Police();
    }

    private void addChance(){
        board[3] = new Chance();
        board[9] = board[3];
        board[15] = board[3];
        board[21] = board[3];
    }

    private void addProperties(){
        createBrownSet();
        createGreySet();
        createPinkSet();
        createOrangeSet();
        createRedSet();
        createYellowSet();
        createGreenSet();
        createBlueSet();
    }

    private void createBrownSet(){
        board[1] = new Property(100, 70, "Hneda", "Mediterranean Avenue");
        board[2] = new Property(100, 70, "Hneda", "Baltic Avenue");
    }

    private void createGreySet(){
        board[4] = new Property(140, 120, "Siva", "Oriental Avenue");
        board[5] = new Property(140, 120, "Siva", "Vermont Avenue");
    }

    private void createPinkSet(){
        board[7] = new Property(180, 140, "Ruzova", "States Avenue");
        board[8] = new Property(180, 140, "Ruzova", "Virginia Avenue");
    }

    private void createOrangeSet(){
        board[10] = new Property(210, 155, "Oranzova", "Tennessee Avenue");
        board[11] = new Property(210, 155, "Oranzova", "New York Avenue");
    }

    private void createRedSet(){
        board[13] = new Property(260, 180, "Cervena", "Kentucky Avenue");
        board[14] = new Property(260, 180, "Cervena", "Indiana Avenue");
    }

    private void createYellowSet(){
        board[16] = new Property(300, 200, "Zlta", "Atlantic Avenue");
        board[17] = new Property(300, 200, "Zlta", "Ventnor Avenue");
    }

    private void createGreenSet(){
        board[19] = new Property(340, 220, "Zelena", "Pacific Avenue");
        board[20] = new Property(340, 220, "Zelena", "North Carolina Avenue");
    }

    private void createBlueSet(){
        board[22] = new Property(380, 240, "Modra", "Park Place");
        board[23] = new Property(380, 240, "Modra", "Boardwalk");
    }

    public void removePlayersProperties(Player player){
        for(Field field: board){
            if(field instanceof Property){
                if(((Property) field).isOwner(player)){
                    ((Property) field).removeOwner();
                }
            }
        }
    }

}
