package sk.stuba.fei.uim.oop;

import java.util.ArrayList;
import java.util.Random;

public class Cell {
    private int x;
    private int y;
    private ArrayList<Cell> neighbours;
    private ArrayList<Cell> connectedNeighbours;
    private boolean visited;
    private boolean player;
    private boolean finish;

    public Cell(int x, int y){
        this.x = x;
        this.y = y;
        finish = false;
        visited = false;
        neighbours = new ArrayList<>();
        connectedNeighbours = new ArrayList<>();
    }

    public void addNeighbour(Cell cell){
        neighbours.add(cell);
    }

    public boolean isVisited(){
        return visited;
    }

    public void setVisited(){
        visited = true;
    }

    private ArrayList<Cell> getUnvisitedNeighbours(){
        ArrayList<Cell> unvisitedNeighbours = new ArrayList<>();
        for(Cell cell : neighbours){
            if(!cell.isVisited()){
                unvisitedNeighbours.add(cell);
            }
        }
        return unvisitedNeighbours;
    }

    public Cell getRandomUnvisitedNeighbour(){
        var unvisitedNeighbours = getUnvisitedNeighbours();
        if(unvisitedNeighbours.isEmpty()){
            return null;
        }
        Random random = new Random();
        int index = random.nextInt(unvisitedNeighbours.size());
        return unvisitedNeighbours.get(index);
    }

    public void connectCells(Cell cell){
        connectedNeighbours.add(cell);
    }

    public boolean areConnected(Cell cell){
        return connectedNeighbours.contains(cell);
    }

    public void setFinish(){
        finish = true;
    }


}
