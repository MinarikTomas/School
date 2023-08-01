import numpy as np
import matplotlib.pyplot as plt
import tensorflow as tf
from sklearn.metrics import confusion_matrix, ConfusionMatrixDisplay, classification_report
from keras.preprocessing.image import ImageDataGenerator
from keras.models import Sequential
from keras.layers import Conv2D, MaxPooling2D
from keras.layers import Activation, Dropout, Flatten, Dense, BatchNormalization
from keras import backend as K
from keras.optimizers import Adam, SGD
from keras.callbacks import EarlyStopping
import seaborn as sns
from keras.applications.mobilenet import MobileNet
from keras.applications.nasnet import NASNetMobile
from keras.applications.imagenet_utils import decode_predictions
import pandas as pd
from tensorflow.python.framework.test_ops import out_t

# mobilenet_model = MobileNet(weights='imagenet')
# nasnet_model = NASNetMobile(weights='imagenet')
# data_path = 'Zadanie3/'
#
# train_data_dir = data_path + 'train'
# img_height = 224
# img_width = 224
#
# if K.image_data_format() == 'channels_first':
#     input_shape = (3, img_width, img_height)
# else:
#     input_shape = (img_width, img_height, 3)
#
# datagen = ImageDataGenerator(rescale=1. / 255)
# #
# test_data = datagen.flow_from_directory(
#     train_data_dir,
#     target_size=(img_width, img_height),
#     batch_size=1,
#     class_mode=None,
#     shuffle=False
# )
#
# # pred = mobilenet_model.predict(test_data)
# pred = nasnet_model.predict(test_data)
#
# labels = decode_predictions(pred)
# labels_df = pd.DataFrame(labels)
# labels_df.to_csv('out.csv')

# def convert(cell):
#     index_start = cell.find(',')
#     index_end = cell.find(',', index_start+1)
#     value = cell[index_start+3: index_end-1]
#     return value
#
out_data = 'results.csv'
#
df = pd.read_csv(out_data)
df.drop(df.columns[0], axis=1, inplace=True)
#
# for index, row in df.iterrows():
#     df.iloc[index, 0] = convert(row[0])
#     df.iloc[index, 1] = convert(row[1])
#
# results = pd.DataFrame(columns=['Label', 'Top1', 'Top2', 'Top3'])
# grouped = df.groupby('label')
# for name, group in grouped:
#     series1 = group.iloc[:, 0]
#     series2 = group.iloc[:, 1]
#     predictions = pd.concat([series1, series2])
#     counts = predictions.value_counts().head(3)
#     results.loc[len(results.index)] = [name, counts.index[0], counts.index[1], counts.index[2]]
#
# results.to_csv('results.csv')
sports = []
n = 0
for index, row in df.iterrows():
    name = row[0]
    if name in [row[1], row[2], row[3]]:
        sports.append(name)
        n += 1

print(n)
print(sports)