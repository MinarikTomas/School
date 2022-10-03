package sk.stuba.fei.uim.oop;


public class Maze {
    private final Cell[][] cells;
    private final Player player;
    public Maze(Player player){
        this.player = player;
        cells = new Cell[11][11];
        createMaze();
    }

    public Cell getCell(int row, int col){
        return cells[row][col];
    }

    private void generateCells(){
        for(int x = 0; x < 11; x++){
            for(int y = 0; y < 11; y++){
                cells[x][y] = new Cell(x, y);
            }
        }
    }

    private void addNeighbours(){
        for(int x = 0; x < 11; x++){
            for(int y = 0; y < 11; y++) {
                if(y > 0){
                    cells[x][y].addNeighbour(cells[x][y-1]);
                }
                if(y < 10){
                    cells[x][y].addNeighbour(cells[x][y+1]);
                }
                if(x > 0){
                    cells[x][y].addNeighbour(cells[x-1][y]);
                }
                if(x < 10){
                    cells[x][y].addNeighbour(cells[x+1][y]);
                }
            }
        }
    }

    private void createMaze(){
        generateCells();
        addNeighbours();
        var startCell = cells[0][0];
        randomizedDFS(startCell);
    }

    private void randomizedDFS(Cell cell){
        cell.setVisited();
        Cell nextCell = cell.getRandomUnvisitedNeighbour();
        while(nextCell != null){
            cell.connectCells(nextCell);
            nextCell.connectCells(cell);
            randomizedDFS(nextCell);
            nextCell = cell.getRandomUnvisitedNeighbour();
        }
    }

    public void moveLeft(){
        if(player.getX() != 0){
            move(-1, 0);
        }
    }

    public void moveRight(){
        if(player.getX() != 10){
            move(1, 0);
        }
    }

    public void moveUp(){
        if(player.getY() != 0){
            move(0, -1);
        }
    }

    public void moveDown(){
        if(player.getY() != 10){
            move(0, 1);
        }
    }

    public void move(int x, int y){
        if(cells[player.getY()][player.getX()].areConnected(cells[player.getY() + y][player.getX() + x])){
            player.move(player.getX() + x, player.getY() + y);
        }
    }

    public boolean isReachable(int x, int y){
        if(isReachableHorizontal(x, y)){
            return true;
        }
        return isReachableVertical(x, y);

    }

    private boolean isReachableHorizontal(int x, int y){
        if(y == player.getY()){
            for(int i = Math.min(x, player.getX()); i < Math.max(x, player.getX()); i++){
                if(!cells[y][i].areConnected(cells[y][i+1])){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    private boolean isReachableVertical(int x, int y){
        if(x == player.getX()){
            for(int i = Math.min(y, player.getY()); i < Math.max(y, player.getY()); i++){
                if(!cells[i][x].areConnected(cells[i+1][x])){
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
