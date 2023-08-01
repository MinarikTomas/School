import os
import pandas as pd
from matplotlib import pyplot as plt
from matplotlib import image as mpimg
from PIL import Image
from colorthief import ColorThief

data_dir = 'Zadanie3/train'

def class_count():
    data = []
    for class_name in os.listdir(data_dir):
        path = data_dir + '/' + class_name
        files = os.listdir(path)
        data.append([class_name, len(files)])

        plt.title(class_name)
        plt.xlabel("X pixel scaling")
        plt.ylabel("Y pixels scaling")

        img_path = path + '/' + files[0]
        image = mpimg.imread(img_path)
        plt.imshow(image)
        plt.show()

        img = Image.open(img_path)
        colors = img.getcolors(img.size[0]*img.size[1])
        print()
        size = (128, 128)
        img = img.resize(size)
        img.save('resize.jpg')
        break

    df = pd.DataFrame(data, columns=['Name', 'Count'])
    df.to_csv('class_count.csv')

# data_df = pd.read_csv('class_count.csv')
# data_df.plot(x='Name', y='Count', kind='bar', figsize=(15, 5))
# plt.tight_layout()
# plt.show()

def get_most_common_colors_from_class(class_name):
    path = 'Zadanie3/test' + '/' + class_name
    files = os.listdir(path)
    for index in range(5):
        img_path = path + '/' + files[index]
        ct = ColorThief(img_path)
        palette = ct.get_palette(color_count=5)

        plt.subplot(5, 1, index + 1)
        plt.imshow([[palette[i] for i in range(5)]])
        ax = plt.gca()
        ax.get_yaxis().set_visible(False)
        ax.get_xaxis().set_visible(False)

    plt.suptitle(class_name)
    plot_path = 'colors/' + class_name + '.png'
    plt.savefig(plot_path)

def get_most_common_colors():
    for class_name in os.listdir('Zadanie3/test'):
        get_most_common_colors_from_class(class_name)
# get_most_common_colors_from_class('curling')
get_most_common_colors()