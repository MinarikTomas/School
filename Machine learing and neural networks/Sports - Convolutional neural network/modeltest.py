from tensorflow import keras
from keras import backend as K
from keras.preprocessing.image import ImageDataGenerator
import pandas as pd
import os

model = keras.models.load_model('models/3')

img_width = 128
img_height = 128

if K.image_data_format() == 'channels_first':
    input_shape = (3, img_width, img_height)
else:
    input_shape = (img_width, img_height, 3)


test_datagen = ImageDataGenerator(rescale=1. / 255)
data_dir = 'Sports/'

test_data = test_datagen.flow_from_directory(
    data_dir,
    target_size=(img_width, img_height),
    batch_size=1,
    class_mode=None,
    shuffle=False
)
classes = os.listdir('Zadanie3/test')
prediction = model.predict(test_data)

df1 = pd.DataFrame({'Class': classes,
                    'Pred1': prediction[0],
                    'Pred2': prediction[1],
                    'Pred3': prediction[2],
                    'Pred4': prediction[3],
                    'Pred5': prediction[4],
                    'Pred6': prediction[5],
                    'Pred7': prediction[6]
                    })

for i in range(7):
    df1.sort_values(by=['Pred' + str(i+1)], inplace=True, ascending=False)
    print(df1[['Class', 'Pred' + str(i+1)]].head())
