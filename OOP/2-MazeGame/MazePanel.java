package sk.stuba.fei.uim.oop;

import javax.swing.*;
import java.awt.*;

public class MazePanel extends JPanel{

    private Maze maze;
    private final Player player;
    private int mouseX;
    private int mouseY;
    private boolean mouseOverReachable;

    public MazePanel(Maze maze, Player player) {
        setFocusable(true);
        this.player = player;
        this.maze = maze;
    }

    public void setMouseOverReachable(boolean mouseOverReachable) {
        this.mouseOverReachable = mouseOverReachable;
    }

    public void setMousePosition(int x, int y){
        mouseX = x;
        mouseY = y;
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
        Graphics2D g2 = (Graphics2D) g;
        g2.setStroke(new BasicStroke(Game.WALL));
        paint(g2);
    }

    private void paint(Graphics2D g2){
        paintBorder(g2);
        paintMaze(g2);
        paintStart(g2);
        paintFinish(g2);
        paintPlayer(g2);
        if(player.isClickActivated() && mouseOverReachable){
            paintReachablePoint(g2);
        }
    }

    private void paintBorder(Graphics2D g2) {
        g2.drawRect(1, 2, Game.MAZE_DIMX-Game.WALL, Game.MAZE_DIMY-Game.WALL);
    }

    private void paintMaze(Graphics2D g2) {
        for (int row = 0; row < 11; row++) {
            for (int col = 0; col < 11; col++) {
                if (col < 10) {
                    paintHorizontalWall(row, col, g2);
                }
                if (row < 10) {
                    paintVerticalWall(row, col, g2);
                }
            }
        }
    }

    private void paintHorizontalWall(int row, int col, Graphics2D g2){
        if (!(maze.getCell(row, col).areConnected(maze.getCell(row, col + 1)))) {
            int x1 = (col+1)*(Game.SQUARE+Game.WALL);
            int y1 = row*(Game.SQUARE+Game.WALL);
            int y2 = y1 + (Game.SQUARE+Game.WALL);
            g2.drawLine(x1, y1, x1, y2);
        }
    }

    private void paintVerticalWall(int row, int col, Graphics2D g2){
        if (!(maze.getCell(row, col).areConnected(maze.getCell(row + 1, col)))) {
            int x1 = col*(Game.SQUARE+Game.WALL);
            int y1 = (row+1)*(Game.SQUARE+Game.WALL);
            int x2 = x1 + (Game.SQUARE+Game.WALL);
            g2.drawLine(x1, y1, x2, y1);
        }
    }

    private void paintStart(Graphics2D g2){
        g2.setColor(Color.GREEN);
        g2.fillRect(3, 3, Game.SQUARE - 2, Game.SQUARE - 1);
        g2.setColor(Color.BLACK);
    }

    private void paintFinish(Graphics2D g2){
        g2.setColor(Color.RED);
        g2.fillRect(Game.MAZE_DIMX-Game.SQUARE-Game.WALL-1, Game.MAZE_DIMY-Game.SQUARE-Game.WALL-2, Game.SQUARE+1, Game.SQUARE+3);
        g2.setColor(Color.BLACK);
    }

    private void paintPlayer(Graphics2D g2){
        int x = player.getX();
        int y = player.getY();
        g2.setColor(Color.BLUE);
        g2.fillOval(Game.WALL+(x*(Game.SQUARE+Game.WALL)), Game.WALL+(y*(Game.SQUARE+Game.WALL)), Game.SQUARE-2, Game.SQUARE-2);
        g2.setColor(Color.BLACK);
    }

    private void paintReachablePoint(Graphics2D g2){
        g2.setColor(Color.BLUE);
        g2.drawOval(Game.WALL+10+(mouseX*(Game.SQUARE+Game.WALL)), Game.WALL+10+(mouseY*(Game.SQUARE+Game.WALL)), 20, 20);
        g2.setColor(Color.BLACK);
    }

    public void setNewMaze(Maze maze){
        this.maze = maze;
    }
}

