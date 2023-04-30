"""This module contains an implementation of bakery algorithm.
Bakery algorithm assures mutual exclusion of N threads.
"""

__author__ = "Tomáš Minárik, Tomáš Vavro, Marián Šebeňa"
__email__ = "xminarikt1@stuba.sk"
__license__ = "MIT"

from fei.ppds import Thread
from time import sleep


def process(tid: int, n: int):
    """Simulates a process.
    Arguments:
        tid -- thread id
        n   -- number of threads
    """
    global num, choosing
    i = tid

    choosing[i] = 1
    num[i] = 1 + max(num)
    choosing[i] = 0

    for j in range(n):
        while choosing[j] == 1:
            continue
        while num[j] != 0 and (num[j] < num[i] or (num[j] == num[i] and j < i)):
            continue

    print(f"Process {tid} runs a complicated computation!")
    sleep(1)

    num[i] = 0


if __name__ == '__main__':
    NUM_THREADS = 8
    num = [0] * NUM_THREADS
    choosing = [0] * NUM_THREADS

    threads = [Thread(process, i, NUM_THREADS) for i in range(NUM_THREADS)]
    [t.join() for t in threads]
