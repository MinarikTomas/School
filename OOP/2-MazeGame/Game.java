package sk.stuba.fei.uim.oop;

import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class Game implements ActionListener, KeyListener, MouseListener, MouseMotionListener {
    public static final int MAZE_DIMX = 476;
    public static final int MAZE_DIMY = 476;
    public static final int SQUARE = 40;
    public static final int WALL = 3;

    private final JPanel topPanel;
    private final MazePanel mazePanel;
    private final JLabel counter;
    private final MyButton up;
    private final MyButton reset;
    private final MyButton left;
    private final MyButton down;
    private final MyButton right;

    private Maze maze;
    private int gameCounter;
    private final Player player;

    public Game(){
        gameCounter = 0;
        player = new Player();
        maze = new Maze(player);
        JFrame frame = new JFrame("Rook in a maze");
        JPanel mainPanel = new JPanel();
        topPanel = new JPanel();
        mazePanel = new MazePanel(maze, player);
        counter = new JLabel("Games Won: 0");
        up = new MyButton("UP", 150, 50);
        reset = new MyButton("RESET", 150, 50);
        left = new MyButton("LEFT", 150, 50);
        down = new MyButton("DOWN", 150, 50);
        right = new MyButton("RIGHT", 150, 50);

        topPanel.setLayout(new GridLayout(2, 3));
        frame.setSize(520, 650);
        counter.setPreferredSize(new Dimension(150, 50));

        setupMazePanel();
        setupButtons();

        mainPanel.add(topPanel);
        mainPanel.add(mazePanel);

        frame.add(mainPanel);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setVisible(true);
    }

    private void setupMazePanel(){
        mazePanel.setPreferredSize(new Dimension(MAZE_DIMX, MAZE_DIMY));
        mazePanel.setBackground(Color.WHITE);
        mazePanel.addKeyListener(this);
        mazePanel.addMouseListener(this);
        mazePanel.addMouseMotionListener(this);
    }

    private void setupButtons(){
        addActionListenerToButtons();
        addButtonsToTopPanel();
    }

    private void addActionListenerToButtons(){
        up.addActionListener(this);
        left.addActionListener(this);
        down.addActionListener(this);
        right.addActionListener(this);
        reset.addActionListener(this);
    }

    private void addButtonsToTopPanel(){
        topPanel.add(counter);
        topPanel.add(up);
        topPanel.add(reset);
        topPanel.add(left);
        topPanel.add(down);
        topPanel.add(right);
    }

    @Override
    public void actionPerformed(ActionEvent e) {
        if(e.getSource() == up){
            maze.moveUp();
        }
        if(e.getSource() == down){
            maze.moveDown();
        }
        if(e.getSource() == left){
            maze.moveLeft();
        }
        if(e.getSource() == right){
            maze.moveRight();
        }
        if(e.getSource() == reset){
            gameCounter = 0;
            newMaze();
        }
        mazePanel.repaint();
        checkIfFinishedAndStartNextMaze();
    }

    @Override
    public void keyPressed(KeyEvent e) {
        int key = e.getKeyCode();
        switch (key){
            case KeyEvent.VK_LEFT:
                maze.moveLeft();
                break;
            case KeyEvent.VK_RIGHT:
                maze.moveRight();
                break;
            case KeyEvent.VK_UP:
                maze.moveUp();
                break;
            case KeyEvent.VK_DOWN:
                maze.moveDown();
                break;
        }
        mazePanel.repaint();
        checkIfFinishedAndStartNextMaze();
    }

    @Override
    public void mouseClicked(MouseEvent e) {
        int x = e.getX();
        int y = e.getY();
        if(!cursorOnWall(x, y)){
            cursorOnTile(x, y);
        }
    }

    private void cursorOnTile(int x, int y) {
        x = convertToMazeCoordinates(x);
        y = convertToMazeCoordinates(y);
        if (cursorOnPlayer(x, y)) {
            clickedOnPlayer();
        } else {
            cursorOnTileWithoutPlayer(x, y);
        }
    }

    private void cursorOnTileWithoutPlayer(int x, int y){
        if(player.isClickActivated()){
            if (maze.isReachable(x, y)) {
                player.move(x, y);
                mazePanel.repaint();
                checkIfFinishedAndStartNextMaze();
            }
        }
    }

    private void clickedOnPlayer(){
        player.setClickActivated(!player.isClickActivated());
    }

    private boolean cursorOnWall(int x, int y){
        if(cursorOnField(x, y)) {
            for (int i = 1; i < 11; i++) {
                if ((x == i * (Game.SQUARE+Game.WALL) || (y == i * (Game.SQUARE+Game.WALL)))) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    private boolean cursorOnField(int x, int y){
        if((x >= Game.WALL) && (x <= Game.MAZE_DIMX - 1 - Game.WALL)){
            return (y >= Game.WALL) && (y <= Game.MAZE_DIMY - 1 - Game.WALL);
        }
        return false;

    }

    private boolean cursorOnPlayer(int x, int y){
        return (x == player.getX()) && (y == player.getY());
    }

    @Override
    public void mouseMoved(MouseEvent e) {
        if(player.isClickActivated()){
            int x = e.getX();
            int y = e.getY();
            if(!cursorOnWall(x, y)){
                x = convertToMazeCoordinates(x);
                y = convertToMazeCoordinates(y);
                if(maze.isReachable(x, y)){
                    mazePanel.setMousePosition(x, y);
                    mazePanel.setMouseOverReachable(true);
                }else{mazePanel.setMouseOverReachable(false);}
            }else{mazePanel.setMouseOverReachable(false);}
            mazePanel.repaint();
        }

    }

    private void checkIfFinishedAndStartNextMaze(){
        if(player.isInFinish()){
            gameCounter++;
            newMaze();
            mazePanel.repaint();
        }
    }

    private void newMaze(){
        player.reset();
        maze = new Maze(player);
        mazePanel.setNewMaze(maze);
        counter.setText("Games won: " + gameCounter);
    }

    private int convertToMazeCoordinates(int coordinate){
        return coordinate / (Game.SQUARE+Game.WALL);
    }


    @Override
    public void mousePressed(MouseEvent e) {

    }

    @Override
    public void mouseReleased(MouseEvent e) {

    }

    @Override
    public void mouseEntered(MouseEvent e) {

    }

    @Override
    public void mouseExited(MouseEvent e) {

    }

    @Override
    public void mouseDragged(MouseEvent e) {

    }

    @Override
    public void keyTyped(KeyEvent e) {

    }

    @Override
    public void keyReleased(KeyEvent e) {

    }

}
