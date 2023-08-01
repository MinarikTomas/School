import pandas as pd
from keras.utils.version_utils import training
from sklearn.preprocessing import MinMaxScaler
from sklearn.model_selection import train_test_split
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.optimizers import Adam
from keras.callbacks import EarlyStopping
import matplotlib.pyplot as plt
import numpy as np
import seaborn as sn
import plotly.express as px
import plotly.graph_objects as go
from sklearn.metrics import confusion_matrix, classification_report

TEST_DATA_PATH = "test.csv"
TRAIN_DATA_PATH = "train.csv"
TOP_TAGS = ['Indie', 'Singleplayer', 'Action', 'Casual', 'Adventure', '2D', 'Strategy', 'Simulation', 'RPG', 'Puzzle']


def trim_tag(tag):
    end = tag.find(tag[0], 1)
    return tag[1:end]


def get_unique_tags(data):
    unique_tags = []
    all_tags = []
    for cell in data:
        if cell != "[]":
            game_tags = cell[1:].split(", ")    # remove "{" and split
            for tag in game_tags:
                tag = trim_tag(tag)
                all_tags.append(tag)
                if tag not in unique_tags:
                    unique_tags.append(tag)
    df = pd.DataFrame(all_tags, columns=['Tags'])
    print(df.value_counts().head(20))
    return unique_tags


def assign_value_to_tag(tag, index, data):
    if tag == 'Indie':
        data.at[index, 'Indie'] = 1
    elif tag == 'Action':
        data.at[index, 'Action'] = 1
    elif tag == 'Casual':
        data.at[index, 'Casual'] = 1
    elif tag == 'Adventure':
        data.at[index, 'Adventure'] = 1
    elif tag == 'Singleplayer':
        data.at[index, 'Singleplayer'] = 1
    elif tag == '2D':
        data.at[index, '2D'] = 1
    elif tag == 'Strategy':
        data.at[index, 'Strategy'] = 1
    elif tag == 'Simulation':
        data.at[index, 'Simulation'] = 1
    elif tag == 'RPG':
        data.at[index, 'RPG'] = 1
    elif tag == 'Puzzle':
        data.at[index, 'Puzzle'] = 1


def extract_tags(data, index):
    if data.at[index, 'D_tags'] != "[]":
        game_tags = data.at[index, 'D_tags'][1:].split(", ")
        for tag in game_tags:
            tag = trim_tag(tag)
            assign_value_to_tag(tag, index, data)


def get_number_of_owners(data, index):
    top = data.at[index, 'D_owners'].split(' .. ')[0]
    top = int(top.replace(',', ''))
    bot = data.at[index, 'D_owners'].split(' .. ')[1]
    bot = int(bot.replace(',', ''))
    data.at[index, 'D_owners'] = ((bot + top)/2)


def get_release_year(data, index):
    data.at[index, 'D_release_date'] = int(data.at[index, 'D_release_date'][-4:])


def drop(data):
    data.dropna(inplace=True)
    data.drop(
        columns=['D_appid', 'D_name', 'positive', 'negative', 'D_reviews', 'VYMAZAT_price', 'coming_soon',
                 'D_genre', 'english', 'developer_est', 'D_publisher', 'D_developer',
                 'Addictive', 'Beautiful', 'ccu', 'is_single_player', 'has_controller_support', 'is_early_access',
                 'Classic', 'Masterpiece', 'Classic', 'Replay Value', 'Well-Written',
                 'Lore-Rich', 'mature_content', 'Competitive', 'Difficult', 'Emotional', 'Epic', 'Funny', 'Short',
                 'Cult Classic'], inplace=True)


def add_genre_columns(data):
    data['Indie'] = 0
    data['Action'] = 0
    data['Casual'] = 0
    data['Adventure'] = 0
    data['Singleplayer'] = 0
    data['2D'] = 0
    data['Strategy'] = 0
    data['Simulation'] = 0
    data['RPG'] = 0
    data['Puzzle'] = 0


def edit_data(data):
    drop(data)
    add_genre_columns(data)
    for index, row in data.iterrows():
        extract_tags(data, index)
        get_number_of_owners(data, index)
        get_release_year(data, index)
    data.drop(columns=['D_tags'], inplace=True)
    data.replace({True: 1, False: 0}, inplace=True)


def normalize(fit, data):
    return pd.DataFrame(fit.transform(data), columns=data.columns)


def prepare_data(test, train):
    edit_data(test)
    edit_data(train)

    fit_min_max = MinMaxScaler().fit(train)
    train = normalize(fit_min_max, train)
    test = normalize(fit_min_max, test)

    test.to_csv('test_edited.csv')
    train.to_csv('train_edited.csv')


def display_plots(training):
    # https://machinelearningmastery.com/display-deep-learning-model-training-history-in-keras/
    # summarize history for accuracy
    plt.plot(training.history['accuracy'])
    plt.plot(training.history['val_accuracy'])
    plt.title('model accuracy')
    plt.ylabel('accuracy')
    plt.xlabel('epoch')
    plt.legend(['accuracy', 'val_accuracy'], loc='upper left')
    plt.show()
    # summarize history for loss
    plt.plot(training.history['loss'])
    plt.plot(training.history['val_loss'])
    plt.title('model loss')
    plt.ylabel('loss')
    plt.xlabel('epoch')
    plt.legend(['loss', 'val_loss'], loc='upper right')
    plt.show()


def display_conf_matrix(cm):
    #https://vitalflux.com/python-draw-confusion-matrix-matplotlib/
    fig, ax = plt.subplots(figsize=(7.5, 7.5))
    ax.matshow(cm, cmap=plt.cm.Blues, alpha=0.3)
    for i in range(cm.shape[0]):
        for j in range(cm.shape[1]):
            ax.text(x=j, y=i, s=cm[i, j], va='center', ha='center', size='xx-large')

    plt.xlabel('Predictions', fontsize=18)
    plt.ylabel('Actuals', fontsize=18)
    plt.title('Confusion Matrix', fontsize=18)
    plt.show()


if __name__ == '__main__':
    test_data = pd.read_csv(TEST_DATA_PATH)
    train_data = pd.read_csv(TRAIN_DATA_PATH)
    #
    # prepare_data(test_data, train_data)
    # print(train_data.shape)

    # train_data.drop(columns=train_data.columns[0], axis=1, inplace=True)
    # test_data.drop(columns=test_data.columns[0], axis=1, inplace=True)
    #
    # free = train_data.query("is_free == 1").sample(n=4500, random_state=125)
    # not_free = train_data.query("is_free == 0").sample(n=4500, random_state=125)
    # train_data = pd.concat([free, not_free])
    #
    # y = train_data['is_free']
    # x = train_data.drop(columns=['is_free'])
    #
    # y_test = test_data['is_free']
    # x_test = test_data.drop(columns=['is_free'])
    #
    # x_train, x_valid, y_train, y_valid = train_test_split(x, y, test_size=0.2, random_state=30)
    #
    # model = Sequential()
    # model.add(Dense(14, input_shape=(x.shape[1], ), activation='relu'))
    # # model.add(Dense(4, activation='relu'))
    # # model.add(Dense(3, activation='relu'))
    # model.add(Dense(1, activation='sigmoid'))
    # optimizer = Adam(learning_rate=0.01)
    # model.compile(loss='binary_crossentropy', optimizer=optimizer, metrics=['accuracy'])
    # my_callbacks = [EarlyStopping(monitor='val_loss', patience=300)]
    # training = model.fit(x_train, y_train, epochs=1500, batch_size=100, validation_data=(x_valid, y_valid),
    #                      callbacks=my_callbacks)
    #
    # _, accuracy = model.evaluate(x, y)
    # print('Accuracy: %.2f' % (accuracy * 100))
    #
    # display_plots(training)
    #
    # predicted = np.round(model.predict(x_test), 0)
    # test_matrix = confusion_matrix(y_test, predicted)
    # display_conf_matrix(test_matrix)
    #
    # print(classification_report(y_test, predicted))
    #
    # predicted = np.round(model.predict(x_train), 0)
    # train_matrix = confusion_matrix(y_train, predicted)
    # display_conf_matrix(train_matrix)
