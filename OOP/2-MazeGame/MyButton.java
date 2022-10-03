package sk.stuba.fei.uim.oop;

import javax.swing.*;
import java.awt.*;

public class MyButton extends JButton {
    public MyButton(String string, int w, int h){
        setVisible(true);
        setFocusable(false);
        setText(string);
        setPreferredSize(new Dimension(w, h));
    }
}
