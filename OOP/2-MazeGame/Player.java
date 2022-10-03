package sk.stuba.fei.uim.oop;

public class Player {
    private int x;
    private int y;
    private boolean clickActivated;

    public Player(){
        x = 0;
        y = 0;
        clickActivated = false;
    }

    public void move(int x, int y){
        this.x = x;
        this.y = y;
        clickActivated = false;
    }

    public int getX() {
        return x;
    }

    public int getY() {
        return y;
    }

    public void setClickActivated(boolean active){
        clickActivated = active;
    }

    public boolean isClickActivated(){
        return clickActivated;
    }

    public void reset(){
        x = 0;
        y = 0;
        clickActivated = false;
    }

    public boolean isInFinish(){
        return (x == 10) && (y == 10);
    }

}
